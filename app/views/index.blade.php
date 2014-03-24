@extends('layout')

@section('content')
<div class="section-container auto" data-section>
	<section>
		<p class="title" data-section-title><a href="#panel1">Live Queue</a></p>
		<div class="content" data-section-content>
			<p><div id="content-data"></div></p>
		</div>
	</section>
	<section>
		<p class="title" data-section-title><a href="#panel2">Interval</a></p>
		<div class="content" data-section-content>
			<p><div id="content-interval"></div></p>
		</div>
	</section>

</div>
	
@stop

@section('scripts')
	<script type="text/javascript">
		var BASE = "<?php echo URL::to('/');?>"
		
		refresh_page();
		


		/*var interval = $.ajax({
		        type: "GET",
		        url: BASE+'/interval',
		        async: false,
		        success: function(){
		        	setTimeout(function(){refresh_page();}, 5000);
		    	}
		    }).responseText;


		$('#content-interval').html(interval);*/

		
		function refresh_page() {
			var feedback = $.ajax({
		        type: "GET",
		        url: BASE+'/content',
		        async: false
		    }).responseText;

		    var interval = $.ajax({
		        type: "GET",
		        url: BASE+'/interval',
		        async: false,
		        success: function(){
		        	setTimeout(function(){refresh_page();}, 5000);
		    	}
		    }).responseText;

		    $('#content-data').html(feedback);
		    $('#content-interval').html(interval);

		    var data = $.ajax({
		        type: "GET",
		        url: BASE+'/ameyo',
		        async: false
		    }).responseText;

			var data = jQuery.parseJSON(data);

			$('#total_agents').val(data['campaign']['total_agents']);
			$('#agents_available').val(data['campaign']['agents_available']);
			$('#agents_acd').val(data['queue']['agents_allocated']);
			$('#agents_break').val(data['campaign']['agents_on_break']);
			$('#agents_manual').val(data['campaign']['agents_on_manual']);


			$('#calls_waiting').val(data['call']['in_ivr']);
			$('#calls_acd').val(data['history']['total_acd']);
			$('#calls_abandon').val(data['history']['total_abandon']);
			$('#calls_total').val(data['history']['total_calls']);
			$('#longest_call_wait').val(data['queue']['longest_call_waiting']);
			console.log('refresh!');
		}
		
	</script>
@stop