<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {

        $client = new Client();
        $response = $client->post('https://accounts.spotify.com/api/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('SPOT_CLIENT'),
                'client_secret' => env('SPOT_SECRET')
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $body = $response->getBody();
        $token = json_decode($body)->access_token;
        // dd($token);

        return redirect()->route('perfil');
    }

    public function perfil($token)
    {
        $user = new Client();
        $res = $user->get(
            'https://api.spotify.com/v1/me',
            [
                'headers' => [
                    'Authorization' => "Bearer " . $token,

                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]
        );

        $body2 = $res->getBody();
        $userData = json_decode($body2);
    }
}
