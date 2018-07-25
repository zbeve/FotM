<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Models\Current;

class RetrievePlaylists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RetrievePlaylists:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve Playlists';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $client = new Client();
      try {
          $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists", [
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

          $response = $client->get("https://api.spotify.com/v1/users/zbeve/playlists", [
              'headers' => [
                  'Accept' => 'application/json',
                  'Authorization' => 'Bearer ' . session('spotify_token'),
              ],
          ]);
      }
      $data = response()->json(json_decode($response->getBody()));
      $count = DB::table('playlists')->count();
      if ($count == 5) {
        DB::table('playlists')->orderBy('timestamp', 'desc')->first()->delete();
      }
      // INSERT NEW PLAYLISTS
    }
}
