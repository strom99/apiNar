 <?php

    use App\Http\Controllers\Controller;
    use App\Http\Controllers\PlaceHolderController;
    use App\Http\Controllers\SpotifyController;
    use App\Http\Controllers\TheMovieController;
    use App\Models\User;
    use GuzzleHttp\Client;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Route;

    /*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    Route::get('/', [SpotifyController::class, 'index']);
    Route::post('/login', [SpotifyController::class, 'login']);
    Route::get('/pista', [SpotifyController::class, 'pista']);


    Route::get('/login-moviedb', [TheMovieController::class, 'login']);
    Route::get('/create-session-id', [TheMovieController::class, 'createSessionId']);
    Route::get('/listas', [TheMovieController::class, 'listas']);
    Route::post('/create-lista', [TheMovieController::class, 'createLista']);
    // Route::get('/login', [TheMovieController::class, 'index']);
    // Route::post('/create', [PlaceHolderController::class, 'create']);


    // Route::get('/', function () {

    //     $code = request()->get('code');

    //     if (!$code) {
    //         return 'Logear para continuar <a href="/login">Entrar</a>';
    //     }

    //     try {
    //         $client = new Client();
    //         $response = $client->post('https://accounts.spotify.com/api/token', [
    //             'form_params' => [
    //                 'grant_type' => 'authorization_code',
    //                 'code' => $code,
    //                 'redirect_uri' => 'http://127.0.0.1:8000'
    //             ],
    //             'headers' => [
    //                 'Content-Type' => 'application/x-www-form-urlencoded',
    //                 'Authorization' => 'Basic ' . base64_encode(env('SPOT_CLIENT') . ':' . env('SPOT_SECRET'))
    //             ]
    //         ]);
    //         $body = $response->getBody();
    //         $token = json_decode($body)->access_token;

    //         $data = $client->get('https://api.spotify.com/v1/me', [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $token
    //             ]
    //         ])->getBody();

    //         $datos = json_decode($data);
    //         $results = DB::select('select * from users where email = :email', ['email' => $datos->email]);

    //         if (count($results) > 0) {
    //             dd('datos');
    //         } else {
    //             $user = new User();
    //             $user->name = $datos->display_name;
    //             $user->email = $datos->email;
    //             $user->token = $token;
    //             $user->save();
    //             dd($results);
    //         }

    //         return redirect()->back()->with([
    //             'message' => $datos
    //         ]);            // guardar usuario base de datos

    //         // dd(json_decode($data));
    //     } catch (Exception $e) {
    //         dd($e->getMessage());
    //     }
    // });

    // Route::get('/login', function () {
    //     function generateRandomString($length = 10)
    //     {
    //         $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //         $charactersLength = strlen($characters);
    //         $randomString = '';
    //         for ($i = 0; $i < $length; $i++) {
    //             $randomString .= $characters[random_int(0, $charactersLength - 1)];
    //         }
    //         return $randomString;
    //     }

    //     $state = generateRandomString(16);
    //     $scope = 'user-read-private user-read-email';

    //     $params = Arr::query([
    //         'response_type' => 'code',
    //         'client_id' => env('SPOT_CLIENT'),
    //         'scope' => $scope,
    //         'redirect_uri' => 'http://127.0.0.1:8000',
    //         'state' => $state
    //     ]);

    //     return redirect('https://accounts.spotify.com/authorize?' . $params);
    // });
