@extends('default')

@section('pageTitle')
Welcome
@endsection

@section('content')

<div class="row" id="login">
  <div class="col-lg-12 text-center">
    <h1 class="mx-auto">Login to Spotify</h1>
    <a href="https://accounts.spotify.com/en/authorize?client_id={{env('SPOTIFY_KEY')}}&response_type=code&redirect_uri={{urlencode(env('SPOTIFY_REDIRECT_URI'))}}&scope=user-read-private%20user-read-email&state=34fFs29kd09" class="btn btn-outline-success btn-lg btn-block" role="button">Login</a>
  </div>
</div>


@endsection
