<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class PlaceHolderController extends Controller
{
    public function index()
    {
        $posts = null;
        try {
            $client = new Client();
            $res = $client->get('https://jsonplaceholder.typicode.com/posts')->getBody();
            $posts = json_decode($res);
        } catch (ClientException $e) {
            dd($e->getMessage());
        }


        return view('posts')->with([
            'posts' => array_reverse($posts),
            'view' => request()->has('view')
        ]);
    }

    public function create(Request $req)
    {
        try {
            $client = new Client();

            $response = $client->post('https://jsonplaceholder.typicode.com/posts', [
                'json' => [
                    'title' => $req->get('titulo'),
                    'body' => $req->get('body'),
                    'userId' => 1,
                ],
            ]);

            $body = $response->getBody();
            $json = json_decode($body, true);
            // buscar post

        } catch (ClientException $e) {
            dd($e->getMessage());
        }
        return redirect()->action([PlaceHolderController::class, 'index']);
    }
}
