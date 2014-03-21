@extends('layout')

@section('content')
<div class="row">
	<div class="large-4 columns">
		<h5 class="subheader">Agents Currently Logged In</h5>
		<table class="large-12" style="font-size:14px;">
			<thead>
				<th>#</th><th>Name</th><th>Status</th>
			</thead>
			<tbody>
				<?php $i = 1; ?>
				@foreach($agents as $agent => $status)
				<tr style="background-color:
					@if(isset($statuses[$status]))
						{{ $statuses[$status]['color'] }}
					@else
						{{ "##DDDDDD" }}
					@endif
				">
					<td>{{ $i++ }}</td><td>{{ $agent }}</td>
					<td>
						@if(isset($statuses[$status]))
							{{ $statuses[$status]['label'] }}
						@else
							{{ $status }}
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="large-8 columns">
		<h5 class="subheader">Campaign Stats</h5>
		<table class="large-12" style="font-size:14px;">
			<thead class="tableHead">
				<tr>
					<th>Campaign</th>
					<th>Calls</th>
					<th>Connected</th>
					<th>Abandoned</th>
					<th>Ave. Duration</th>
				</tr>
			</thead>
			<?php
				$record = new Record;
				//get campaigns
				$campaigns = $record->campaigns();
				//total calls
				$calls = $record->total_calls();
				//total live calls
				$connected_calls = $record->total_connected_calls();
				//abandoned calls
				$abandoned_calls = $record->total_abandoned_calls();
				//average calltimes
				$average_calltime = $record->average_calltime();

				//get the keys from the campaigns
				$campaign_keys = array_keys($campaigns);
				
				//variables of total numbers
				$total_calls = 0;
				$total_connected_calls = 0;
				$total_abandoned_calls = 0;

				//populates the table according to the specified data needed
				foreach($campaign_keys as $keys) {
					echo '<tr onmouseover="mouseOn(this)" onmouseout="mouseOut(this)">';
					//echo '<td class="groupName">'. $keys .'</td>';
					echo '<td class="groupName">'. $campaigns[$keys] .'</td>';
					echo '<td>';
							if(array_key_exists($keys, $calls)) {
								echo $calls[$keys];
								$total_calls += $calls[$keys];
							} else {
								echo '0';
							}
					echo '</td>';
					echo '<td>';
							if(array_key_exists($keys, $connected_calls)) {
								echo $connected_calls[$keys];
								$total_connected_calls += $connected_calls[$keys];
							} else {
								echo '0';
							}
					echo '</td>';
					echo '<td>';
							if(array_key_exists($keys, $abandoned_calls)) {
								echo $abandoned_calls[$keys];
								$total_abandoned_calls += $abandoned_calls[$keys];
							} else {
								echo '0';
							}
					echo '</td>';
					echo '<td>'. (array_key_exists($keys, $average_calltime) ? round($average_calltime[$keys], 2) : 0) .'</td>';
					echo "</tr>";

				}

				

				//row of total calls, live calls, abandoned calls 
				echo '<tr class="groupName">';
				echo '<td>Total</td>';
				echo '<td>'. $total_calls .'</td>';
				echo '<td>'. $total_connected_calls .'</td>';
				echo '<td>'. $total_abandoned_calls .'</td>';
				echo '<td></td>';
				echo '</tr>';
			?>
		</table>
	</div>
</div>
@stop