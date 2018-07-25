@extends('default')

@section('pageTitle')
Playlist
@endsection

@include('partials.nav')

@section('content')

<div id="playlist" class="row justify-content-center">
  <div class="col-xl-12">
    <ul id="songs" class="list-group list-group-flush">
      @foreach ($data as $song)
        <li class="song list-group-item list-group-item-action flex-column align-items-start" value="{{ $song->preview_url }}">
          <div class="d-flex w-100 justify-content-between">
            <div class="col-10">
              <div class="row">
                <div class="col-12 title">
                  {{ $song->title }}
                </div>
              </div>
              <div class="row">
                <div class="col-12 details">
                  {{ $song->artist }} | {{ $song->album }} | {{ $song->user }}
                </div>
              </div>
            </div>
            <div class="col-2">
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
