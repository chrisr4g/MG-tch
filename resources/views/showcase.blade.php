@extends('layouts.basic')

@section('content')

	<div class="showcase-wrapper" id="showcaseItems">
		<div class="row">
			@foreach($movies as $movie)
				@include('layouts.components.showcase_item',$movie)
			@endforeach
		</div>
	</div>
	<div  style="display:none">
		<p><img src="{{asset('loader.gif')}}">Loading more movies</p>
	</div>
	<script type="text/javascript">
		var page = 0;
		$(window).scroll(function() {
		    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
		        page++;
		        loadMoreData(page);
		    }
		});
		function loadMoreData(page){
		  $.ajax(
		        {
		            url: '/api/showcase?page=' + page,
		            type: "get",
		            beforeSend: function()
		            {
		                $('.ajax-load').show();
		            }
		        })
		        .done(function(data)
		        {
		            if(data.html == " "){
		                $('.ajax-load').html("No more movies found");
		                return;
		            }
		            $('.ajax-load').hide();
		            $("#showcaseItems").append('<div class="row">'+data.html+'</div>');
		        })
		        .fail(function(jqXHR, ajaxOptions, thrownError)
		        {
		              alert('server not responding...');
		        });
		}
	</script>
@endsection