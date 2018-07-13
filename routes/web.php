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

// Route::get('/home', function() {
//   return view('layouts.home');
// });

Route::get('/callback', 'AuthenticateSpotifyController@spotifyCallback');
// Route::get('/denied', 'AuthenticateSpotifyController@denied');
// Route::get('/login/refresh', 'AuthenticateSpotifyController@spotifyRefresh');

Route::get('/home', 'RetrieveStatsController@loadHome');
Route::get('/playlist', 'RetrieveStatsController@viewCurrentMonth');
Route::get('/mvp', 'RetrieveStatsController@returnMVP');

Route::post('/playlist/ajax/like', 'RetrieveStatsController@post');
Route::get('/playlist/ajax/like', 'RetrieveStatsController@get');
