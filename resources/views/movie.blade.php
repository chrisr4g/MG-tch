@extends('layouts.basic')

@section('content')

	<div class="movieWrapper">
		<div class="movieHeader">
			<span id="{{$movie['class']}}Title">
				{{$movie['headline']}} - {{$movie['year']}} - {{$movie['cert'][0]['name']}}
			</span>
			<span id="{{$movie['class']}}Rating">
				{{$movie['rating']}}
			</span><br>
			<span id="reviewQuote">
				{{$movie['quote']}} - <i>{{$movie['reviewAuthor']}}</i>
			</span>
		</div>
		<div class="movie synopsis">
			Synopsis : 
			<span>{{$movie['synopsis']}}</span>
		</div>
		<div class="movie duration">
			Duration : {{(int)($movie['duration']/3600)}}h{{$movie['duration']%3600/60}}m
		</div>
		<div class="movie genres">
			Genres : 
			@foreach($movie['genres'] as $genre)
				<span class="{{$genre['name']}}_genre">
					{{$genre['name']}}
				</span>
			@endforeach
		</div>
		<div class="movie directors">
			Directors :
			@foreach($movie['directors'] as $director)
				<span class="directors names">
					{{$director['name']}}
				</span>
			@endforeach
		</div>
		<div class="movie cast">
			Cast :
			@foreach($movie['cast'] as $cast)
				<span class="cast names">
					{{$cast['name']}}
				</span>
			@endforeach
		</div>
		<div class="row">
			Key Art Images : 
			@foreach($movie['keyArtImages'] as $keyArtImage)
				@if($keyArtImage['url_local'] != null && $keyArtImage['url_local'] != 'null')
					<img src={{asset($keyArtImage['url_local'])}}>
				@else
					@continue
				@endif
			@endforeach
		</div>
		<div class="row">
			Card Images :
			@foreach($movie['cardImages'] as $cardImage)
				<img src="{{asset($cardImage['url_local'])}}">
			@endforeach
		</div>
		@if($movie['videos'] != null)
			<div class="row">
				Videos : 
				@foreach($movie['videos'] as $video)
					<div class="video">
						<a href="{{$video['url']}}" title="{{$video['title']}}"><img src="{{asset($video['thumbnailUrl'])}}" alt="{{$video['title']}}"></a>
						Alternatives : 
						@foreach($movie['videoAlternatives'] as $vidAlt)
							@if($vidAlt['video_id'] == $video['id'])
								<a href="{{$vidAlt['url']}}"> {{$vidAlt['quality']}} </a>
							@endif
						@endforeach
					</div><br>
				@endforeach
			</div>
		@endif
	</div>

@endsection