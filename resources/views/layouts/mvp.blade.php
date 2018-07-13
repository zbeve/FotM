@extends('default')

@section('pageTitle')
MVP
@endsection

@include('partials.nav')

@section('content')

<div id="mvp-container" class="row">
  <div class="col"></div>
  <div class="col-8">
    <div class="card">
      <div class="row">
        <div class="col-1"></div>
        <div class="col-5"></div>
        <div class="col-5">
          <h5 class="text-center header">Recently Played Tracks</h5>
        </div>
        <div class="col-1"></div>
      </div>
      <div class="row">
        <div class="col-1"></div>
        <div class="col-5">
          <img class="img-fluid rounded mx-auto d-block" src="{{ $data->images[0]->url }}" alt="Responsive image">
        </div>
        <div class="col-5">
          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img class="d-block w-100" src="{{ $song[0]->image_url }}" alt="First slide">
                <h5 class="text-center">{{ $song[0]->title }}</h5>
                <p class="text-center">{{ $song[0]->artist }} | {{ $song[0]->album }}</p>
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="{{ $song[1]->image_url }}" alt="Second slide">
                <h5 class="text-center">{{ $song[1]->title }}</h5>
                <p class="text-center">{{ $song[1]->artist }} | {{ $song[1]->album }}</p>
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="{{ $song[2]->image_url }}" alt="Third slide">
                <h5 class="text-center">{{ $song[2]->title }}</h5>
                <p class="text-center">{{ $song[2]->artist }} | {{ $song[2]->album }}</p>
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="{{ $song[3]->image_url }}" alt="Fourth slide">
                <h5 class="text-center">{{ $song[3]->title }}</h5>
                <p class="text-center">{{ $song[3]->artist }} | {{ $song[3]->album }}</p>
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="{{ $song[4]->image_url }}" alt="Fifth slide">
                <h5 class="text-center">{{ $song[4]->title }}</h5>
                <p class="text-center">{{ $song[4]->artist }} | {{ $song[4]->album }}</p>
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
          <div class="col-1"></div>
        </div>
      </div>
      <div class="card-body">
        <h5 class="card-title">@if($data->display_name != null){{ $data->display_name }}@else{{$data->id}}@endif | Current MVP</h5>
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Contributed 420 Songs</li>
        <li class="list-group-item">Recieved 69 Likes</li>
        <li class="list-group-item">{{ $data->followers->total }} Followers</li>
      </ul>
      <div class="card-body">
        <a href="{{ $data->external_urls->spotify }}" class="btn btn-outline-success">Visit Profile</a>
      </div>
    </div>
  </div>
  <div class="col"></div>
</div>



@endsection
