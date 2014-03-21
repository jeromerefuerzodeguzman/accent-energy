@extends('layout')

@section('content')
<div class="section-container auto" data-section>
	<section>
		<p class="title" data-section-title><a href="#panel1">Live Queue</a></p>
		<div class="content" data-section-content>
			<p>
				<div id="content-data">
					<div class="row">
						<div class="large-6 columns">
							<h5 class="subheader">Agents Currently Logged In (<?= date('Y-m-d') ?>)</h5>
							<table class="large-12" style="font-size:14px;">
								<thead>
									<th>#</th><th>Name</th><th>Status</th><th>Time</th>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						<div class="large-4 columns">
							<form>
								<h5 class="subheader">Agents Summary</h5>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Agents Total</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="total_agents" value="">
									</div>
								</div>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Agents Avail.</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="agents_available" value="">
									</div>
								</div>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Agents in ACD</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="agents_acd" value="">
									</div>
								</div>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Agents in Manual</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="agents_manual" value="">
									</div>
								</div>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Agents in Break</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="agents_break" value="">
									</div>
								</div>
								<hr />
								<h5 class="subheader">Calls Summary</h5>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Calls Waiting</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="calls_waiting" value="">
									</div>
								</div>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Longest Call Waiting</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="longest_call_wait" value="">
									</div>
								</div>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Total ACD Calls</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="calls_acd" value="">
									</div>
								</div>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Total Abandoned Calls</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="calls_abandon" value="">
									</div>
								</div>
								<div class="row collapse">
									<div class="small-6 large-6 columns">
										<span class="prefix">Total Calls</span>
									</div>
									<div class="small-6 large-6 columns">
										<input type="text" id="calls_total" value="">
									</div>
								</div>
								<hr />
							</form>
						</div>
						<div class="large-2 columns"></div>
					</div>
				</div>
			</p>
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
		
		//refresh_page();
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

		var interval = $.ajax({
		        type: "GET",
		        url: BASE+'/interval',
		        async: false,
		        success: function(){
		        	setTimeout(function(){refresh_page();}, 5000);
		    	}
		    }).responseText;


		$('#content-interval').html(interval);

		/*
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
			//console.log('refresh!');
		}*/
		
	</script>
@stop