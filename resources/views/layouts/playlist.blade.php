@extends('default')

@section('pageTitle')
Playlist
@endsection

@include('partials.nav')

@section('content')

<div id="playlist" class="row justify-content-center">
  <div class="col-xl-12"
    <ul class="list-group list-group-flush">
      <li id="headers" class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
          <div class="col-3">
            <h5>Title</h5>
          </div>
          <div class="col-2">
            <h5>Artist</h5>
          </div>
          <div class="col-3">
            <h5>Album</h5>
          </div>
          <div class="col-2">
            <h5>User</h5>
          </div>
          <div class="col-1">
            <h5>Likes</h5>
          </div>
          <div class="col-1">
          </div>
        </div>
      </li>
    </ul>
    <ul id="songs" class="list-group list-group-flush">
      @foreach ($data as $song)
        <li class="song list-group-item list-group-item-action flex-column align-items-start" value="{{ $song->preview_url }}">
          <div class="d-flex w-100 justify-content-between">
            <div class="col-3 title">
              {{ $song->title }}
            </div>
            <div class="col-2 artist">
              {{ $song->artist }}
            </div>
            <div class="col-3 album">
              {{ $song->album }}
            </div>
            <div class="col-2 user">
              {{ $song->user }}
            </div>
            <div class="col-1 likes">
              <span class="badge badge-primary badge-pill">{{ $song->likes }}</span>
            </div>
            <div class="col-1">
              <button type="button" class="btn like btn-outline-success"><i class="fas fa-heart"></i></button>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
</div>

<nav id="player-bg" class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
  <audio id="player" preload="auto" src="" autoplay controls>
  </audio>
</nav>

@endsection
