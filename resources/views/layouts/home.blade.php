@extends('default')

@section('pageTitle')
Home
@endsection

@include('partials.nav')

@section('content')


<div id="main-card" class="card mb-3">
  <div class="img-container">
    <img class="card-img-top" src="{{ $data->images[0]->url }}" alt="Card image cap">
  </div>
  <div class="card-body">
    <h5 class="card-title">{!! $data->name !!}</h5>
    <p class="card-text">{!! $data->description !!}</p>
    <p class="card-text"><small class="text-muted">{{ $data->tracks->total }} Tracks - {{ $data->followers->total }} Followers</small></p>
    <a href="#" class="btn btn-primary">View on Spotify</a>
  </div>
</div>


<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="http://placehold.it/900x350" alt="First slide">
      <div class="carousel-caption d-none d-md-block">
        <h5>Group - FotM [June]</h5>
        <p>A discovery playlist. No theme, no judgment, just your favorites that you're listening to at the moment.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="http://placehold.it/900x350" alt="Second slide">
      <div class="carousel-caption d-none d-md-block">
        <h5>Group - FotM [May]</h5>
        <p>A discovery playlist. No theme, no judgment, just your favorites that you're listening to at the moment.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="http://placehold.it/900x350" alt="Third slide">
      <div class="carousel-caption d-none d-md-block">
        <h5>Group - FotM [April]</h5>
        <p>A discovery playlist. No theme, no judgment, just your favorites that you're listening to at the moment.</p>
      </div>
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

@endsection
