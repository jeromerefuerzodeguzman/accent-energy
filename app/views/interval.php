<div class="row">
	<div class="large-9 columns">
		<h5 class="subheader">Calling Statistics (<?= date('Y-m-d') ?>)</h5>
		<div class="row">
			<div class="large-12 columns">
				<?php
					$date = array();
					$total = array();
					foreach ($inbound_logs as $key => $logs) {
						$hour = -1;
						$_0 = 0;
						$_15 = 0;
						$_30 = 0;
						$_45 = 0;

						/////// the last record's key ///////
						end($logs);
						$last_id = key($logs);

						foreach ($logs as $key_index => $time) {
							if($hour != $time['hour']) {
								if($hour != -1) {
									$date[$key][$hour][0] = $_0;
									$date[$key][$hour][15] = $_15;
									$date[$key][$hour][30] = $_30;
									$date[$key][$hour][45] = $_45;

									//total them
									$total[$key][$hour] = $_0 + $_15 + $_30 + $_45;
								}

								$_0 = 0;
								$_15 = 0;
								$_30 = 0;
								$_45 = 0;

								$hour = $time['hour'];
							}

							if($time['minutes'] >= 0 AND $time['minutes'] < 15) $_0++;
							elseif($time['minutes'] >= 15 AND $time['minutes'] < 30) $_15++;
							elseif($time['minutes'] >= 30 AND $time['minutes'] < 45) $_30++;
							elseif($time['minutes'] >= 45) $_45++;

							//last record.. must total
							if($key_index == $last_id) {
								$date[$key][$hour][0] = $_0;
								$date[$key][$hour][15] = $_15;
								$date[$key][$hour][30] = $_30;
								$date[$key][$hour][45] = $_45;

								//total them
								$total[$key][$hour] = $_0 + $_15 + $_30 + $_45;
							}
						}
					}


					//create table loop
					$tbody = "";
					for($a=8;$a<=20;$a++) {
						$hour = $a<10?'0'.$a:$a;
						$tbody .= "
							<tr>
								<td><strong>$a</strong></td>
								<td>";
						$tbody .= "<div class='large-12' style=\"color:#2BA6CB;\">" . (isset($date['offered'][$hour][0])?$date['offered'][$hour][0]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:GREEN;\">" . (isset($date['answered'][$hour][0])?$date['answered'][$hour][0]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:RED;\">" . (isset($date['abandoned'][$hour][0])?$date['abandoned'][$hour][0]:'0') . "</div>";
						$tbody .= "</td>
								<td>";
						$tbody .= "<div class='large-12' style=\"color:#2BA6CB;\">" . (isset($date['offered'][$hour][15])?$date['offered'][$hour][15]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:GREEN;\">" . (isset($date['answered'][$hour][15])?$date['answered'][$hour][15]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:RED;\">" . (isset($date['abandoned'][$hour][15])?$date['abandoned'][$hour][15]:'0') . "</div>";
						$tbody .= "</td>
								<td>";
						$tbody .= "<div class='large-12' style=\"color:#2BA6CB;\">" . (isset($date['offered'][$hour][30])?$date['offered'][$hour][30]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:GREEN;\">" . (isset($date['answered'][$hour][30])?$date['answered'][$hour][30]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:RED;\">" . (isset($date['abandoned'][$hour][30])?$date['abandoned'][$hour][30]:'0') . "</div>";
						$tbody .= "</td>
								<td>";
						$tbody .= "<div class='large-12' style=\"color:#2BA6CB;\">" . (isset($date['offered'][$hour][45])?$date['offered'][$hour][45]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:GREEN;\">" . (isset($date['answered'][$hour][45])?$date['answered'][$hour][45]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:RED;\">" . (isset($date['abandoned'][$hour][45])?$date['abandoned'][$hour][45]:'0') . "</div>";
						$tbody .= "</td>
								<td>";
						$tbody .= "<div class='large-12' style=\"color:#2BA6CB;\">" . (isset($total['offered'][$hour])?$total['offered'][$hour]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:GREEN;\">" . (isset($total['answered'][$hour])?$total['answered'][$hour]:'0') . "</div>";
						$tbody .= "<div class='large-12' style=\"color:RED;\">" . (isset($total['abandoned'][$hour])?$total['abandoned'][$hour]:'0') . "</div>";
						$tbody .= "</td>
							</tr>";
					}

					$table = "
						<table class='responsive large-12'>
							<thead>
								<tr>
									<th>HOUR</th><th>00</th><th>15</th><th>30</th><th>45</th><th>TOTAL</th>
								</tr>
							</thead>
							<tbody>
								$tbody
							</tbody>
						</table>
					";

					echo $table;
				?>
			</div>
		</div>
	</div>
	<div class="large-3 columns">
		<h5><small>Legends:</small></h5>
		<div class="row"><div class="large-12 columns"><span class="large-12 label">Calls Offered</span></div></div>
		<div class="row"><div class="large-12 columns"><span class="large-12 label success">Calls Answered</span></div></div>
		<div class="row"><div class="large-12 columns"><span class="large-12 label alert">Calls Abandoned</span></div></div>
	</div>
</div>