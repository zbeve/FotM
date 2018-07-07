<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Http\Requests;

class RetrieveStatsController extends Controller
{
    public function retrieveData()
    {
      $client = new Client();
        try {
            $response = $client->get("https://api.spotify.com/v1/me/playlists", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('spotify_token'),
                ],
            ]);

            // dd($reponse);
            return response()->json(json_decode($response->getBody())->items);
        } catch (\Exception $e) {

            $refreshToken = $client->post('https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'client_id' => env('SPOTIFY_KEY'),
                    'client_secret' => env('SPOTIFY_SECRET'),
                    'grant_type' => 'refresh_token',
                    'refresh_token' => session('spotify_refresh'),
                    'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
                ]
            ]);

            $refreshToken = json_decode($refreshToken->getBody());

            session(['spotify_token' => $refreshToken->access_token]);

            if (isset($refreshToken->refresh_token)) {
                session(['spotify_refresh' => $refreshToken->refresh_token]);
            }

            $response = $client->get("https://api.spotify.com/v1/me/playlists", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('spotify_token'),
                ],
            ]);
            // dd($response);
            return response()->json(json_decode($response->getBody())->items);
        }
    }

    public function viewStats()
    {
        $test = $this->retrieveData();
        dd($test);
        if (session()->has('spotify_token')) {
            return view('layouts.home');
        }

        return redirect('/login/spotify');
    }

    public function retrieveAnalysedTrackData(\GuzzleHttp\Client $httpClient, Request $request)
    {
        $response = $httpClient->get("https://api.spotify.com/v1/audio-features/{$request->input('track')}", [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('spotify_token'),
            ],
        ]);

        return response()->json(json_decode($response->getBody()));
    }
}
