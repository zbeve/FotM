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
          <div class="col-3">
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
        </div>
      </li>
      @foreach ($data as $song)
        <li id="song" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <div class="col-3">
              {{ $song->title }}
            </div>
            <div class="col-3">
              {{ $song->artist }}
            </div>
            <div class="col-3">
              {{ $song->album }}
            </div>
            <div class="col-2">
              {{ $song->user }}
            </div>
            <div class="col-1">
              <span class="badge badge-primary badge-pill">{{ $song->likes }}</span>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
</div>

@endsection
