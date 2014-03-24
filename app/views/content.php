<div class="row">
	<div class="large-6 columns">
		<h5 class="subheader">Agents Currently Logged In (<?= date('Y-m-d') ?>)</h5>
		<table class="large-12" style="font-size:14px;">
			<thead>
				<th>Name</th><th>Auto Call</th><th>Status</th><th>Time</th>
			</thead>
			<tbody>
				<?php 
					foreach($lists as $list) {
						$name = explode('-', $list->session_id);
						$today = date("Y-m-d h:i:s.u");
						$interval = date_diff(date_create($list->status_date), date_create($today));
						$time = $interval->format('%H:%I:%S');
						$autocall = $list->autocall_name == 'agent_set_auto_call_on' ? '<img width="25px" src=' . URL::to('/') .'/img/on.gif' .'>' : '<img width="25px" src=' . URL::to('/') .'/img/off.gif' .'>';
						$row = "
						<tr>
							<td>$name[3]</td>
							<td>$autocall</td>
							<td>$list->status_name</td>
							<td>$time</td>
						</tr>
						";
						echo $row;
					}
				?>
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