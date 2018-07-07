@extends('default')

@section('pageTitle')
Welcome
@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-lg-12 text-center">
      <h1 class="mt-5">A Bootstrap 4 Starter Template</h1>
      <p class="lead">Complete with pre-defined file paths and responsive navigation!</p>
      <ul class="list-unstyled">
        <li>Bootstrap 4.1.1</li>
        <li>jQuery 3.3.1</li>
        <a href="https://accounts.spotify.com/en/authorize?client_id={{env('SPOTIFY_KEY')}}&response_type=code&redirect_uri={{urlencode(env('SPOTIFY_REDIRECT_URI'))}}&scope=user-read-private%20user-read-email&state=34fFs29kd09" class="btn btn-default" role="button">Login</a>
      </ul>
    </div>
  </div>
</div>

@endsection
