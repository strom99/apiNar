<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TheMovieController extends Controller
{

    public function login()
    {
        $client = new Client();
        $token = $client->request('GET', 'https://api.themoviedb.org/3/authentication/token/new', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('THE_TOKENL'),
                'accept' => 'application/json',
            ],
        ]);
        $datos = json_decode($token->getBody());
        $request_token = $datos->request_token;

        $redirect_url = env('APP_URL') . '/create-session-id';

        return redirect(
            'https://www.themoviedb.org/authenticate/' . $request_token . '?redirect_to=' . $redirect_url
        );
    }

    public function createSessionId()
    {
        $request_token = request()->get('request_token');

        $client = new Client();

        $response = $client->request('POST', 'https://api.themoviedb.org/3/authentication/session/new', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('THE_TOKENL'),
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
            'body' => json_encode([
                'request_token' => $request_token
            ])
        ]);

        $session_id = json_decode($response->getBody())->session_id;

        return redirect('/listas')->withCookie('session_id', $session_id);
    }

    public function listas()
    {
        $session_id = Cookie::get('session_id');

        $client = new Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/account?session_id=' . $session_id, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('THE_TOKENL'),
                'accept' => 'application/json',
            ],
        ]);

        $account_id = json_decode($response->getBody())->id;

        $response = $client->request('GET', 'https://api.themoviedb.org/3/account/' . $account_id . '/lists?page=1', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('THE_TOKENL'),
                'accept' => 'application/json',
            ],
        ]);

        $listas = json_decode($response->getBody());

        return view('listas', [
            'listas' => $listas
        ]);
    }

    public function createLista()
    {
        $session_id = Cookie::get('session_id');
        $client = new Client();
        $client->request('POST', 'https://api.themoviedb.org/3/list?session_id=' . $session_id, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('THE_TOKENL'),
                'accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'name' => request()->get('name'),
                'description' => request()->get('description')
            ])
        ]);

        return redirect('/listas');
    }
}
