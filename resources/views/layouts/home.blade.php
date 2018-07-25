@extends('default')

@section('pageTitle')
Home
@endsection

@include('partials.nav')

@section('content')

<div id="home-carousel" class="row">
  <div id="playlistCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
      <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
      <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <a href="/playlist"><img class="d-block w-100" src="{{ $data->items[0]->images[0]->url }}" alt="First slide"></a>
        <div class="carousel-caption d-none d-md-block">
          <h5>{{ $data->items[0]->name }}</h5>
        </div>
      </div>
      <div class="carousel-item">
        <a target="_blank" href="{{ $data->items[1]->external_urls->spotify }}"><img class="d-block w-100" src="{{ $data->items[1]->images[0]->url }}" alt="Second slide"></a>
        <div class="carousel-caption d-none d-md-block">
          <h5>{{ $data->items[1]->name }}</h5>
        </div>
      </div>
      <div class="carousel-item">
        <a target="_blank" href="{{ $data->items[2]->external_urls->spotify }}"><img class="d-block w-100" src="{{ $data->items[2]->images[0]->url }}" alt="Third slide"></a>
        <div class="carousel-caption d-none d-md-block">
          <h5>{{ $data->items[2]->name }}</h5>
        </div>
      </div>
    </div>
    <a class="carousel-control-prev" href="#playlistCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#playlistCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

@endsection
