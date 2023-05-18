<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class SpotifyController extends Controller
{
    public function index()
    {
        $code = request()->get('code');

        if (!$code) {
            $message = 'Necesitas loguearte';
            return view('home', with([]));
        }

        try {
            $client = new Client();
            $response = $client->post('https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => 'http://127.0.0.1:8000'
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . base64_encode(env('SPOT_CLIENT') . ':' . env('SPOT_SECRET'))
                ]
            ]);
            $body = $response->getBody();
            $token = json_decode($body)->access_token;

            $data = $client->get('https://api.spotify.com/v1/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ])->getBody();

            $datos = json_decode($data);
            $results = DB::select('select * from users where email = :email', ['email' => $datos->email]);

            if (count($results) > 0) {
            } else {
                $user = new User();
                $user->name = $datos->display_name;
                $user->email = $datos->email;
                $user->token = $token;
                $user->save();
            }

            // dd(json_decode($data));
        } catch (Exception $e) {
            dd('ds');
            dd($e->getMessage());
        }

        Cookie::queue(Cookie::make('api-token', $token, 60));

        return view('perfil')->with(['perfil' => $datos]);
    }

    public function login()
    {
        function generateRandomString($length = 10)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        $state = generateRandomString(16);
        $scope = 'user-read-private user-read-email';

        $params = Arr::query([
            'response_type' => 'code',
            'client_id' => env('SPOT_CLIENT'),
            'scope' => $scope,
            'redirect_uri' => 'http://127.0.0.1:8000',
            'state' => $state
        ]);

        return redirect('https://accounts.spotify.com/authorize?' . $params);
    }

    public function pista(Request $req)
    {
        $user = Cookie::get('api-token');

        try {
            if (!$user) {
                return redirect('/');
            }
            $client = new Client();

            $log = $client->get('https://api.spotify.com/v1/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $user
                ]
            ])->getBody();
            $usr = json_decode($log);


            if (!$req->get('track') || is_null($req->get('track'))) {
                return view('perfil')->with([
                    'message' => null,
                    'error' => 'campo obligatorio',
                    'perfil' => $usr
                ]);
            }
            $data = $client->get('https://api.spotify.com/v1/search?type=track&q=' . $req->get('track'), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $user
                ]
            ])->getBody();



            $message = json_decode($data)->tracks;
        } catch (Exception $e) {
            return redirect()->back();
        }

        return view('perfil')->with([
            'message' => $message,
            'error' => null,
            'perfil' => $usr
        ]);
    }
}
