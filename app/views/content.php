
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
					<input type="text" value="<?= count($agents) ?>">
				</div>
			</div>
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">Agents Avail.</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $available ?>">
				</div>
			</div>
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">Agents in ACD</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $connected ?>">
				</div>
			</div>
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">Agents in Pause</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $paused ?>">
				</div>
			</div>
			<!-- <div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">% Avail.</span>
				</div>
				<div class="small-6 large-6 columns">
					<?php 
						$value = 0;
						if(count($agents)>0) $value = round(($available/count($agents))*100, 2);
					?>
					<input type="text" value="<?= $value ?>">
				</div>
			</div> -->
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">Agents in ACW</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $acw ?>">
				</div>
			</div>
			<hr />
			<h5 class="subheader">Calls Summary</h5>
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">Calls Waiting</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $calls_waiting['total'] ?>">
				</div>
			</div>
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">Oldest Call Waiting</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $calls_waiting['total']=='0'?'00:00':Record::get_time_difference($calls_waiting['oldest']) ?>">
				</div>
			</div>
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">ACD Calls</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $acd_calls ?>">
				</div>
			</div>
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">Abandoned Calls</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $calls_abandoned ?>">
				</div>
			</div>
			<div class="row collapse">
				<div class="small-6 large-6 columns">
					<span class="prefix">Total Calls</span>
				</div>
				<div class="small-6 large-6 columns">
					<input type="text" value="<?= $total_calls ?>">
				</div>
			</div>
			<hr />
		</form>
	</div>
	<div class="large-2 columns"></div>
</div>
