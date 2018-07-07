<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','AuthenticateSpotifyController@checkLogin');

// Route::get('/login', function() {
//   return view('layouts.login');
// });

Route::get('/callback', 'AuthenticateSpotifyController@spotifyCallback');
// Route::get('/denied', 'AuthenticateSpotifyController@denied');
// Route::get('/login/refresh', 'AuthenticateSpotifyController@spotifyRefresh');

Route::get('/home', 'RetrieveStatsController@viewStats');
