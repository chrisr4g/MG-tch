<div class='showcaseItem '>
	<div class="itemHeader">
		<span id="{{$movie['class']}}Title">
			<a href="/movie/{{$movie['id']}}">{{$movie['headline']}} - {{$movie['year']}} - {{$movie['cert'][0]['name']}}</a>
		</span>
		<span id="{{$movie['class']}}Rating">
			{{$movie['rating']}}
		</span>
		<span id="reviewQuote">
			{{$movie['quote']}} - <i>{{$movie['reviewAuthor']}}</i>
		</span>
		Duration <span id="duration">{{(int)($movie['duration']/3600)}}h{{$movie['duration']%3600/60}}m</span>
	</div>
	<div class="row">
		@foreach($movie['keyArtImages'] as $keyArtImage)
			@if($keyArtImage['url_local'] != null && $keyArtImage['url_local'] != 'null')
				<img src={{asset($keyArtImage['url_local'])}}>
			@else
				@continue
			@endif
		@endforeach
	</div>
	<div class="showcase_synopsis">
		<span>{{$movie['synopsis']}}</span>
	</div>
	<div class="showcase_genres">
		@foreach($movie['genres'] as $genre)
			<span class="{{$genre['name']}}_genre">
				{{$genre['name']}}
			</span>
		@endforeach
	</div>
</div>