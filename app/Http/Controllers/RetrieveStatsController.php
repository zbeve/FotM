<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Models\Current;

class RetrieveStatsController extends Controller
{

    public function post(Request $request) {
      $user = $this->retrieveRecentlyPlayed()['user_id'];
      $action = $request->action;
      if (request()->ajax()) {
        switch($action) {
          case 'like':
            return RetrieveStatsController::updateLikes($request->title, $user);
            break;
        }
      }
    }

    public function updateLikes($title, $user) {
      DB::table('current')->where('title', $title)->increment('likes', 1, ['liked_by' => $user]);
      // $likes = DB::table('current')->where('title', $title)->pluck('likes');
      // return response($likes[0]);
    }

    public function loadHome() {
        $music = $this->retrieveRecentlyPlayed();
        $this->getUserTotals($music['user_id']);

        if($music['user_id'] == "zbeve") {
          $data = $this->retrievePlaylists()->original;
          DB::table('playlists')->truncate();
          for ($i = 0; $i < 5; $i++) {
            DB::table('playlists')->insert([
              'name' => $data->items[$i]->name,
              'image' => $data->items[$i]->images[0]->url,
              'link' => $data->items[$i]->external_urls->spotify,
            ]);
          }
          DB::table('current_key')->insert([
            'playlist_id' => $data->items[0]->id,
            'month' => rtrim(explode("[", $data->items[0]->name)[1], "]")
          ]);
        }
        $playlists = DB::table('playlists')->get();

        if (DB::table('recently_played')->where('user', $music['user_id'])->get()->count() != 0) {
          DB::table('recently_played')->where('user', $music['user_id'])->delete();
        }

        for ($i = 0; $i < 5; $i++) {
          DB::table('recently_played')->insert([
            'title' => $music['recents']->items[$i]->track->name,
            'artist' => $music['recents']->items[$i]->track->artists[0]->name,
            'album' => $music['recents']->items[$i]->track->album->name,
            'user' => $music['user_id'],
            'preview_url' => $music['recents']->items[$i]->track->preview_url,
            'spotify_url' => $music['recents']->items[$i]->track->external_urls->spotify,
            'image_url' => $music['recents']->items[$i]->track->album->images[0]->url,
          ]);
        }

        if (session()->has('spotify_token')) {
            return view('layouts.home')->with('data', $playlists);
        }
        return view('layouts.login');
    }

// RETIRVE RELEVANT PLAYLISTS FOR HOME
    public function retrievePlaylists() {
      $client = new Client();
      try {
          $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
          return response()->json(json_decode($response->getBody()));
      }
      catch (\Exception $e) {

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

          $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
          return response()->json(json_decode($response->getBody()));
      }
    }


// RETRIEVE USER DATA
    public function retrieveMVP() {
      $users = DB::table('users')->get();

// INSTEAD, TRY CALCULATING A 'SCORE' AND ENTERING INTO THE USERS TABLE
// MAKE SONG COUNT EITHER EXPONENTIAL DECAY OR LOGARITHMIC. CAN LEAVE LIKES LINEAR.

      // $likes = 0;
      // $songs = 0;
      // dd($users);
      // foreach($users as $user) {
      //   if ($user->like_count > $likes)
      //     $likes = $user->like_count;
      //   if ($user->track_count > $songs)
      //     $songs = $user->track_count;
      // }
      $client = new Client();
      try {
        // NEEDS TO BE UPDATED FROM STATIC
          $response = $client->get("https://api.spotify.com/v1/users/zbeve", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
          return response()->json(json_decode($response->getBody()));
      }
      catch (\Exception $e) {

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

          $response = $client->get("https://api.spotify.com/v1/users/zbeve", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
          return response()->json(json_decode($response->getBody()));
      }
    }

// RETIRVE RELEVANT PLAYLIST INFORMATION
    public function retrieveData() {
      $current_playlist_key = DB::table('current_key')->where('month', date('F'))->pluck('playlist_id');
      $client = new Client();
        try {
          // NEEDS TO BE UPDATED TO PULL FROM DATABASE
          $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists/$current_playlist_key[0]/tracks?offset=0", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
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
              $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists/$current_playlist_key[0]/tracks?offset=0", [
                  'headers' => [
                      'Accept' => 'application/json',
                      'Authorization' => 'Bearer ' . session('spotify_token'),
                  ],
              ]);
            }
          $data = response()->json(json_decode($response->getBody()));
          if (Current::count() != $data->original->total) {
            $offsetCount = intval(floor($data->original->total / 100));
            $offset = intval(100);
            for ($i = 0; $i < $offsetCount; $i++) {
              $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists/$current_playlist_key[0]/tracks?offset=$offset", [
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
            $last_entry = DB::table('current')->orderBy('date_added', 'desc')->first();
            $count = DB::table('current')->count();
            for ($i = 0; $i < count($data->original->items); $i++) {
              if ($count == 0 || strtotime($data->original->items[$i]->added_at) > strtotime($last_entry->date_added)) {
                DB::table('current')->insert([
                  'title' => $data->original->items[$i]->track->name,
                  'artist' => $data->original->items[$i]->track->artists[0]->name,
                  'album' => $data->original->items[$i]->track->album->name,
                  'user' => $this->returnUserName($data->original->items[$i]->added_by->href),
                  'spotify_id' => $data->original->items[$i]->added_by->id,
                  'likes' => 0,
                  'preview_url' => $data->original->items[$i]->track->preview_url,
                ]);
              }
            }
          }
          $playlistData = DB::table('current')->get()->toArray();
          return $playlistData;
    }

    public function retrieveRecentlyPlayed() {
      $client = new Client();
      try {
          $user = $client->get("https://api.spotify.com/v1/me", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
          $response = $client->get("https://api.spotify.com/v1/me/player/recently-played", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
      }
      catch (\Exception $e) {

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
          $user = $client->get("https://api.spotify.com/v1/me", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
          $response = $client->get("https://api.spotify.com/v1/me/player/recently-played", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
      }
      $user = response()->json(json_decode($user->getBody()));
      $recently_played = response()->json(json_decode($response->getBody()));

      $data = ([
        'user_id' => $user->original->id,
        'recents' => $recently_played->original,
      ]);

      return $data;
    }

    public function getUserTotals($id) {
      $likes = DB::table('current')->where('spotify_id', $id)->sum('likes');
      $songs = DB::table('current')->where('spotify_id', $id)->count();
      $date = date('m/d/y');

      if (DB::table('users')->where('spotify_id', $id)->first() != null) {
        DB::table('users')->where('spotify_id', $id)->update([
          'like_count' => $likes,
          'track_count' => $songs,
          'last_login' => $date,
        ]);
      }
      else {
        DB::table('users')->insert([
          'spotify_id' => $id,
          'like_count' => $likes,
          'track_count' => $songs,
          'last_login' => $date,
        ]);
      }
    }

    public function viewCurrentMonth() {
      $data = $this->retrieveData();
      if (session()->has('spotify_token')) {
          return view('layouts.playlist')->with('data', $data);
      }
      return view('layouts.login');
    }

    public function returnMVP() {
      $data = $this->retrieveMVP();
      $recents = DB::table('recently_played')->where('user', $data->original->id)->get();
      $user = DB::table('users')->where('spotify_id', $data->original->id)->first();
      return view('layouts.mvp')->with('data', $data->original)->with('song', $recents)->with('user', $user);
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
}
