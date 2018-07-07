<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use Socialite;

class AuthenticateSpotifyController extends Controller
{
    public function checkLogin() {
      if (session()->has('spotify_token'))
        return redirect('/home');
      else
        return view('layouts.login');
    }

    public function spotifyCallback()
    {
        $httpClient = new Client();
        if (isset($_GET['error']))
        {
            return redirect('/denied');
        }
        $response = $httpClient->post('https://accounts.spotify.com/api/token', [
            'form_params' => [
                'client_id' => env('SPOTIFY_KEY'),
                'client_secret' => env('SPOTIFY_SECRET'),
                'grant_type' => 'authorization_code',
                'code' => $_GET['code'],

                'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
            ]
        ]);

        session(['spotify_token' => json_decode($response->getBody())->access_token]);
        session(['spotify_refresh' => json_decode($response->getBody())->refresh_token]);
        return redirect('/');
    }

    // public function denied()
    // {
    //     return view('denied');
    // }
}
