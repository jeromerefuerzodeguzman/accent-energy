<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>IGS Dashboard</title>
		{{ HTML::style('css/normalize.css') }}
		{{ HTML::style('css/foundation.css') }}
		{{ HTML::style('css/responsive-tables.css') }}
		{{ HTML::script('js/vendor/custom.modernizr.js') }}
	</head>
	<body>
		<div id="content">
			<nav class="top-bar">
				<ul class="title-area">
				    <li class="name">
				      	<h1><a href="#">Northstar Solutions Inc.</a></h1>
				    </li>
    				<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
			  	</ul>
			  	<section class="top-bar-section"></section>
			</nav>
			<div class="row">
				<div class="large-12 columns">
					<h1><a href="#">IGS Dashboard</a></h1>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<h3>@yield('title')</h3>
					<hr />
					<div class="row">
						<div class="large-12 columns">
							@yield('content')
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<hr>
					<h6><small>Copyright 2013 Northstar Solutions Inc.</small></h6>
				</div>
			</div>
		</div>
		<!-- Check for Zepto support, load jQuery if necessary -->

		<script src="js/vendor/jquery.js"></script>
		<script src="js/foundation/foundation.js"></script>
		<script src="js/foundation/responsive-tables.js"></script>
		<script src="js/foundation/foundation.alerts.js"></script>
		<script src="js/foundation/foundation.clearing.js"></script>
		<script src="js/foundation/foundation.cookie.js"></script>
		<script src="js/foundation/foundation.dropdown.js"></script>
		<script src="js/foundation/foundation.forms.js"></script>
		<script src="js/foundation/foundation.joyride.js"></script>
		<script src="js/foundation/foundation.magellan.js"></script>
		<script src="js/foundation/foundation.orbit.js"></script>
		<script src="js/foundation/foundation.placeholder.js"></script>
		<script src="js/foundation/foundation.reveal.js"></script>
		<script src="js/foundation/foundation.section.js"></script>
		<script src="js/foundation/foundation.tooltips.js"></script>
		<script src="js/foundation/foundation.topbar.js"></script>
		<script src="js/foundation/foundation.interchange.js"></script>
		<script>
			$(document).foundation();
		</script>

		<div id="alerts">
			@if(Session::has('message'))
				<div class="alert-box success">
					{{ Session::get('message') }}
					<a href="" class="close">&times;</a>
				</div>
			@elseif(Session::has('error'))
				<div class="alert-box alert">
					{{ Session::get('error') }}
					<a href="" class="close">&times;</a>
				</div>
			@endif

			@if($errors->has())
				<script type="text/javascript">
				@foreach($errors->messages as $key => $value)
					if($("input[name='{{ $key }}']").length){
						var form = $("input[name='{{ $key }}']").addClass("error").after('<small class="error">{{ $value[0] }}</small>').parents('form:first');
					} else if($("select[name='{{ $key }}']").length) {
						var form = $("select[name='{{ $key }}']").addClass("error").after('<small class="error">{{ $value[0] }}</small>').parents('form:first');
					} else if($("textarea[name='{{ $key }}']").length) {
						var form = $("textarea[name='{{ $key }}']").addClass("error").after('<small class="error">{{ $value[0] }}</small>').parents('form:first');
					}
				@endforeach

					@if(Session::has('form'))
						$("#{{ Session::get('form') }}").reveal();
					@else
						var parent = form.parent();
						if(parent.attr('id').indexOf("modal") !== -1) {
							parent.foundation('reveal', 'open');
						}
					@endif
				</script>
			@endif
		</div>
		@yield('scripts')
	</body>
</html>