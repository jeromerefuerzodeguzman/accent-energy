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