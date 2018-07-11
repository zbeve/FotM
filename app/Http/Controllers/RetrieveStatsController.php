<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Models\Current;

class RetrieveStatsController extends Controller
{
    public function retrievePlaylists() {
      $client = new Client();
      // try {
          $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
          return response()->json(json_decode($response->getBody()));
      // } 
      // catch (\Exception $e) {
      //
      //     $refreshToken = $client->post('https://accounts.spotify.com/api/token', [
      //         'form_params' => [
      //             'client_id' => env('SPOTIFY_KEY'),
      //             'client_secret' => env('SPOTIFY_SECRET'),
      //             'grant_type' => 'refresh_token',
      //             'refresh_token' => session('spotify_refresh'),
      //             'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
      //         ]
      //     ]);
      //
      //     $refreshToken = json_decode($refreshToken->getBody());
      //
      //     session(['spotify_token' => $refreshToken->access_token]);
      //
      //     if (isset($refreshToken->refresh_token)) {
      //         session(['spotify_refresh' => $refreshToken->refresh_token]);
      //     }
      //
      //     $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists", [
      //         'headers' => [
      //             'Accept' => 'application/json',
      //             'Authorization' => 'Bearer ' . session('spotify_token'),
      //         ],
      //     ]);
      //     return response()->json(json_decode($response->getBody()));
      // }
    }

    public function retrieveData() {
      $client = new Client();
        // try {
          $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists/2KFfK5Qnxul570CykzM912/tracks?offset=0", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
          $data = response()->json(json_decode($response->getBody()));
          if (Current::count() != $data->original->total) {
            $offsetCount = intval(floor($data->original->total / 100));
            $offset = intval(100);
            for ($i = 0; $i < $offsetCount; $i++) {
              $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists/2KFfK5Qnxul570CykzM912/tracks?offset=$offset", [
                  'headers' => [
                      'Accept' => 'application/json',
                      'Authorization' => 'Bearer ' . session('spotify_token'),
                  ],
              ]);
              $offsetData = response()->json(json_decode($response->getBody()));
              foreach ($offsetData->original->items as $extra)
                array_push($data->original->items, $extra);
              $offset + 100;
            }

            for ($i = Current::count(); $i < count($data->original->items); $i++) {
              DB::table('current')->insert([
                'title' => $data->original->items[$i]->track->name,
                'artist' => $data->original->items[$i]->track->artists[0]->name,
                'album' => $data->original->items[$i]->track->album->name,
                'user' => $this->returnUserName($data->original->items[$i]->added_by->href),
                'likes' => 0,
              ]);
            }
          }
          $playlistData = DB::table('current')->get()->toArray();
          return $playlistData;
        // } catch (\Exception $e) {

        //     $refreshToken = $client->post('https://accounts.spotify.com/api/token', [
        //         'form_params' => [
        //             'client_id' => env('SPOTIFY_KEY'),
        //             'client_secret' => env('SPOTIFY_SECRET'),
        //             'grant_type' => 'refresh_token',
        //             'refresh_token' => session('spotify_refresh'),
        //             'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
        //         ]
        //     ]);
        //
        //     $refreshToken = json_decode($refreshToken->getBody());
        //
        //     session(['spotify_token' => $refreshToken->access_token]);
        //
        //     if (isset($refreshToken->refresh_token)) {
        //         session(['spotify_refresh' => $refreshToken->refresh_token]);
        //     }
        //
        //     $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists/2KFfK5Qnxul570CykzM912/tracks?offset=0", [
        //         'headers' => [
        //             'Accept' => 'application/json',
        //             'Authorization' => 'Bearer ' . session('spotify_token'),
        //         ],
        //     ]);
        //     $data = response()->json(json_decode($response->getBody()));
        //     $offsetCount = intval(floor($data->original->total / 100));
        //     $offset = intval(100);
        //     for ($i = 0; $i < $offsetCount; $i++) {
        //       $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists/2KFfK5Qnxul570CykzM912/tracks?offset=$offset", [
        //           'headers' => [
        //               'Accept' => 'application/json',
        //               'Authorization' => 'Bearer ' . session('spotify_token'),
        //           ],
        //       ]);
        //       $offsetData = response()->json(json_decode($response->getBody()));
        //       foreach ($offsetData->original->items as $extra)
        //         array_push($data->original->items, $extra);
        //       $offset + 100;
        //     }
        //     dd($data);
        //     if (Current::count() != $data->original->total) {
        //       foreach ($data->original->items as $song) {
        //         DB::table('current')->insert([
        //           'title' => $song->track->name,
        //           'artist' => $song->track->artists[0]->name,
        //           'album' => $song->track->album->name,
        //           'user' => null,
        //           'likes' => 0,
        //         ]);
        //       }
        //     }
        //     return $data;
        // }
    }

    public function viewPlaylists() {
        $data = $this->retrievePlaylists();
        if (session()->has('spotify_token')) {
            return view('layouts.home')->with('data', $data->original);
        }
        return view('layouts.login');
    }

    public function viewCurrentMonth() {
      $data = $this->retrieveData();
      if (session()->has('spotify_token')) {
          return view('layouts.playlist')->with('data', $data);
      }
      return view('layouts.login');
    }

    public function returnUserName($userHref) {
      $client = new Client();
      $response = $client->get($userHref, [
          'headers' => [
              'Accept' => 'application/json',
              'Authorization' => 'Bearer ' . session('spotify_token'),
          ],
      ]);
      $userData = response()->json(json_decode($response->getBody()));
      if ($userData->original->display_name != null)
        return $userData->original->display_name;
      else
        return $userData->original->id;
    }

    // public function retrieveAnalysedTrackData(\GuzzleHttp\Client $httpClient, Request $request)
    // {
    //     $response = $httpClient->get("https://api.spotify.com/v1/audio-features/{$request->input('track')}", [
    //         'headers' => [
    //             'Accept' => 'application/json',
    //             'Authorization' => 'Bearer ' . session('spotify_token'),
    //         ],
    //     ]);
    //     return response()->json(json_decode($response->getBody()));
    // }
}
