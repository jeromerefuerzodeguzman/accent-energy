<?php

class Record extends Eloquent {

	public $conn;
	public $start_time;
	public $end_time;

	public function __construct() {
		//connect on database
		$this->conn = odbc_connect('northstar-apps','','');
		$this->start_time = "08:00:00";
		$this->end_time = "20:00:00";
	}

	/**
	 * get list of campaigns
	 * @return array
	 */
 	public function campaigns() {
 		$campaigns = array(
 			'AEDL' => 'Accent Energy Disconnect Letter',
 			'AEP' => 'Accent Energy Payment',
 			'AES' => 'Accent Energy Spanish',
 			'AEST' => 'Accent Energy Spanish Transfer',
 			'DR' => 'Dynowatt Renewal',
 			'AECC' => 'Accent Energy Customer Care',
 			'AEM' => 'Accent Energy Main',
 			'AEMS' => 'Accent Energy Main, AE Sales Transfers',
 			'AEWE' => 'Accent Energy Web Enrollment',
 			'CCMS' => 'CCM Sales',
 			'DCC1' => 'Dynowatt Customer Care',
 			'DCC2' => 'Dynowatt Customer Care 2',
 			'DDL' => 'Dynowatt Disconnect Letter',
 			'DM' => 'Dynowatt Main',
 			'DP' => 'Dynowatt Payment',
 			'DWE' => 'Dynowatt Web Enrollment'
 		);

 		return $campaigns;
 	}

 	public function statuses() {
 		//define statuses
		$statuses = array(
			'1'	=> array('color' => '#CA9696', 'label' => 'Available'),
			'4'	=> array('color' => '#c1bf97', 'label' => 'Waiting'),
			'5' => array('color' => '#9dc997', 'label' => 'Connected'),
			'6' => array('color' => '#c6b597', 'label' => 'Paused'),
			'8' => array('color' => '#97bfc4', 'label' => 'After Call Work')
		);

		return $statuses;
 	}

 	/**
	 * get list of campaigns
	 * @return array
	 */
 	public function current_agents() {
 		
		//get list of agents currently logged in
		$data_agents = array();
		$query_agents = "
			SELECT tskstsrs.tsr, tskstsrs.statnum, tsrmaster.sname, tskstsrs.last_stattime
			FROM tskstsrs,tsrmaster
			WHERE tsrmaster.tsr = tskstsrs.tsr AND tskstsrs.tsr != ''
			ORDER BY tskstsrs.statnum, tskstsrs.last_stattime DESC";
		$result_agents = odbc_exec($this->conn, $query_agents);

		# fetch the data from the database
		while(odbc_fetch_row($result_agents)){
		 	$tsr = trim(odbc_result($result_agents, 1));
			$status = odbc_result($result_agents, 2);
			$name = odbc_result($result_agents, 3);
			$time = odbc_result($result_agents, 4);
			$data_agents[$tsr] = array('name' => $name, 'status' => $status, 'time' => $time);
		}

		return $data_agents;
 	}

 	/**
 	 * get total calls specified campaign. No parameters means all campaigns
 	 * @param   $campaign [description]
 	 * @return [type]           [description]
 	 */
 	public function total_calls($campaign = NULL, $group = TRUE) {
 		if($group == FALSE) {
 			$query = "
				SELECT COUNT(*)
				FROM inboundlog
				WHERE appl != 'CGEN' AND appl IS NOT NULL AND call_time > '".$this->start_time."'
			";

			$result = odbc_exec($this->conn, $query);

			# fetch the data from the database
			while(odbc_fetch_row($result)){
				$count = odbc_result($result, 1);
			}

			# close the connection
			odbc_close($this->conn);

			return $count;
 		} elseif($campaign == NULL) {
 			//generate stats for each campaign
 			$query = "
				SELECT appl, COUNT(*)
				FROM inboundlog
				WHERE appl != 'CGEN' AND appl IS NOT NULL AND call_time > '".$this->start_time."'
				GROUP BY appl
			";

			$result = odbc_exec($this->conn, $query);

			$data = array();

			# fetch the data from the database
			while(odbc_fetch_row($result)){
			 	$campaign = trim(odbc_result($result, 1));
				$count = odbc_result($result, 2);
				$data[$campaign] = $count;
			}

			# close the connection
			odbc_close($this->conn);

			return $data;

 		} else {
 			//generate stats for specific campaign
			
			//check if campaign exist	 			
 			if($this->campaign_exist($campaign) === TRUE) {
 				//query total calls
 				$query = "
 					SELECT appl, COUNT(*)
					FROM inboundlog
					WHERE appl = '$campaign' AND call_time > '".$this->start_time."'
					GROUP BY appl
 				";

 				# perform the query
				odbc_fetch_into(odbc_exec($this->conn, $query), $result);

				# close the connection
				odbc_close($this->conn);

				return $result[1];
 			}
 		}

 	}


 	/**
 	 * get total live calls specified campaign. No parameters means all campaigns
 	 * @param   $campaign [description]
 	 * @return [type]           [description]
 	 */
 	public function total_connected_calls($campaign = NULL, $group = TRUE) {
 		
 		//generate stats for each campaign
 		if($group == FALSE) {
 			$query = "
 				SELECT COUNT(*)
				FROM inboundlog
				WHERE appl != 'CGEN' AND tsr <> '' AND appl IS NOT NULL AND call_time > '".$this->start_time."'
			";

			$result = odbc_exec($this->conn, $query);

			# fetch the data from the database
			while(odbc_fetch_row($result)){
				$count = odbc_result($result, 1);
			}

			# close the connection
			odbc_close($this->conn);

			return $count;
 		} elseif($campaign == NULL) {
 			$query = "
				SELECT appl, COUNT(*)
				FROM inboundlog
				WHERE appl != 'CGEN' AND tsr <> '' AND appl IS NOT NULL
				GROUP BY appl
			";

			$result = odbc_exec($this->conn, $query);

			$data = array();

			# fetch the data from the database
			while(odbc_fetch_row($result)){
			 	$campaign = trim(odbc_result($result, 1));
				$count = odbc_result($result, 2);
				$data[$campaign] = $count;
			}

			# close the connection
			odbc_close($this->conn);

			return $data;

 		} else {
 			//generate stats for specific campaign
			
			//check if campaign exist	 			
 			if($this->campaign_exist($campaign) === TRUE) {
 				//query total calls
 				$query = "
 					SELECT appl, COUNT(*)
					FROM inboundlog
					WHERE appl != 'CGEN' AND tsr <> '' AND appl = '$campaign' 
					GROUP BY appl
 				";

 				# perform the query
				odbc_fetch_into(odbc_exec($this->conn, $query), $result);

				# close the connection
				odbc_close($this->conn);

				return $result[1];
 			}
 		}

 	}


 	/**
 	 * get total abandoned calls.
 	 * @param   $campaign [description]
 	 * @return [type]           [description]
 	 */
 	public function calls_abandoned() {
 		//query lines with status = 2(WATING)
		$query = "
			SELECT COUNT(*)
			FROM inboundlog
			WHERE appl != 'CGEN' AND tsr = '' AND call_time > '".$this->start_time."' AND appl IS NOT NULL
		";

		$result = odbc_exec($this->conn, $query);

		$data = array();

		# fetch the data from the database
		while(odbc_fetch_row($result)){
		 	$total = trim(odbc_result($result, 1));
		}

		# close the connection
		odbc_close($this->conn);

		return $total;
 	}

 	/**
 	 * get total abandoned calls specified campaign. No parameters means all campaigns
 	 * @param   $campaign [description]
 	 * @return [type]           [description]
 	 */
 	public function total_abandoned_calls($campaign = NULL) {
 		
 		//generate stats for each campaign
 		if($campaign == NULL) {
 			$query = "
				SELECT appl, COUNT(*)
				FROM inboundlog
				WHERE appl != 'CGEN' AND tsr = '' AND appl IS NOT NULL
				GROUP BY appl
			";

			$result = odbc_exec($this->conn, $query);

			$data = array();

			# fetch the data from the database
			while(odbc_fetch_row($result)){
			 	$campaign = trim(odbc_result($result, 1));
				$count = odbc_result($result, 2);
				$data[$campaign] = $count;
			}

			# close the connection
			odbc_close($this->conn);

			return $data;

 		} else {
 			//generate stats for specific campaign
			
			//check if campaign exist	 			
 			if($this->campaign_exist($campaign) === TRUE) {
 				//query total calls
 				$query = "
 					SELECT appl, COUNT(*)
					FROM inboundlog
					WHERE appl = '$campaign' AND tsr = ''
					GROUP BY appl
 				";

 				# perform the query
				odbc_fetch_into(odbc_exec($this->conn, $query), $result);

				# close the connection
				odbc_close($this->conn);

				return $result[1];
 			}
 		}

 	}

 	/**
 	 * get average call time specified campaign. No parameters means all campaigns
 	 * @param   $campaign [description]
 	 * @return [type]           [description]
 	 */
 	public function average_calltime($campaign = NULL) {
 		
 		//generate stats for each campaign
 		if($campaign == NULL) {
 			$query = "
				SELECT appl, sum(time_holding)/count(*)
				FROM inboundlog
				WHERE appl != 'CGEN' AND tsr <> '' AND appl IS NOT NULL
				GROUP BY appl";

			$result = odbc_exec($this->conn, $query);

			$data = array();

			# fetch the data from the database
			while(odbc_fetch_row($result)){
			 	$campaign = trim(odbc_result($result, 1));
				$count = odbc_result($result, 2);
				$data[$campaign] = $count;
			}

			# close the connection
			odbc_close($this->conn);

			return $data;

 		} else {
 			//generate stats for specific campaign
			
			//check if campaign exist	 			
 			if($this->campaign_exist($campaign) === TRUE) {
 				//query total calls
 				$query = "
					SELECT appl, sum(time_holding)/count(*)
					FROM inboundlog
					WHERE appl = '$campaign' AND tsr <> '' AND appl IS NOT NULL
					GROUP BY appl
 				";

 				# perform the query
				odbc_fetch_into(odbc_exec($this->conn, $query), $result);

				# close the connection
				odbc_close($this->conn);

				return $result[1];
 			}
 		}
 	}


	/**
 	 * Get total calls waiting
 	 * @return boolean
 	 */
 	public function tot_call_waiting() {
 		//query lines with status = 2(WATING)
		$query = "
			SELECT count(*)
			FROM tsksphone
			WHERE status = 2
		";

		$result = odbc_exec($this->conn, $query);

		$data = array();

		# fetch the data from the database
		while(odbc_fetch_row($result)){
		 	$total = trim(odbc_result($result, 1));
		}

		# close the connection
		odbc_close($this->conn);

		return $total;
 	}


 	/**
 	 * Get total calls waiting
 	 * @return boolean
 	 */
 	public function calls_waiting() {
 		//query lines with status = 2(WATING)
		$query = "
			SELECT count(*), min(stat_time)
			FROM tsksphone
			WHERE status = 2 AND station = 0 AND tsr_code = '' AND grp = 5 AND tot_conn = 0
   				AND phone_no NOT IN (2804474, 5134740, 5134739, 9234447, 9950992, 7965014, 3136862, 2072222, 3734858, 8667456, 2186362, 2431900, 6424272, 2804474, 7965013, 4050233, 3416575, 5174150, 4050238, 2011908, 5071250, 5078150, 7965012, 4767866, 6174352, 5071850, 4250906, 2011909, 2011907)
   				AND phone_no NOT IN (SELECT phone_no FROM tsksphone WHERE status = 3)
		";

		$result = odbc_exec($this->conn, $query);

		$data = array();

		# fetch the data from the database
		while(odbc_fetch_row($result)){
			$data = array(
				'total' => trim(odbc_result($result, 1)),
				'oldest' => trim(odbc_result($result, 2))
			);
		 	/*$total = trim(odbc_result($result, 1));
		 	$oldest = trim(odbc_result($result, 2));*/
		}

		# close the connection
		odbc_close($this->conn);

		return $data;
 	}


 	/**
 	 * Checker if campaign exist
 	 * @param  string $campaign
 	 * @return boolean
 	 */
 	public function campaign_exist($campaign) {
 		$campaign_list = $this->campaigns();

 		if(array_key_exists($campaign, $campaign_list)) {
			return TRUE;
		}
		
		return FALSE;
 	}


 	public static function get_time_difference($time) {

 		//format current time
 		$time_local = date("H:i:s", strtotime("-5 hours -23 minutes 15 seconds"));
		$time_local_seconds = substr($time_local, -2, 2);
		$time_local_minutes = substr($time_local, -4, 2);
		if(strlen($time_local) == 5) {
			$time_local_hours = substr($time_local, -5, 1);
		} else {
			$time_local_hours = substr($time_local, -6, 2);
		}

		//format time
		$time_seconds = substr($time, -2, 2);
		$time_minutes = substr($time, -4, 2);
		if(strlen($time) == 5) {
			$time_hours = substr($time, -5, 1);
		} else {
			$time_hours = substr($time, -6, 2);
		}
		/*var_dump($time_local); echo "---";
		var_dump($time_hours . ":" . $time_minutes . ":" . $time_seconds);*/
		$a = new DateTime($time_local);
		$b = new DateTime($time_hours . ":" . $time_minutes . ":" . $time_seconds);
		$interval = $a->diff($b);

		echo $interval->format("%H:%I:%S");
 	}

 	/**
 	 * Get inbound call log
 	 * @return boolean
 	 */
 	public function inbound_logs() {
 		$this->conn = odbc_connect('northstar-apps','','');

 		//query calls offered
		$query_offered = "select call_time from inboundlog WHERE appl != 'CGEN' AND appl IS NOT NULL order by call_time";
		$result_offered = odbc_exec($this->conn, $query_offered);

		//query calls answered
		$query_answered = "select call_time from inboundlog WHERE appl != 'CGEN' AND appl IS NOT NULL AND tsr <> '' order by call_time";
		$result_answered = odbc_exec($this->conn, $query_answered);

		//query calls abandoned
		$query_abandoned = "select call_time from inboundlog WHERE appl != 'CGEN' AND appl IS NOT NULL AND tsr = '' order by call_time";
		$result_abandoned = odbc_exec($this->conn, $query_abandoned);


		$data = array();

		# fetch the data from the database
		while(odbc_fetch_row($result_offered)){
			$data['offered'][] = array(
				'minutes' => substr(odbc_result($result_offered, 1), -5, 2),
				'hour' => substr(odbc_result($result_offered, 1), -8, 2),
			);
		}

		while(odbc_fetch_row($result_answered)){
			$data['answered'][] = array(
				'minutes' => substr(odbc_result($result_answered, 1), -5, 2),
				'hour' => substr(odbc_result($result_answered, 1), -8, 2),
			);
		}

		while(odbc_fetch_row($result_abandoned)){
			$data['abandoned'][] = array(
				'minutes' => substr(odbc_result($result_abandoned, 1), -5, 2),
				'hour' => substr(odbc_result($result_abandoned, 1), -8, 2),
			);
		}

		# close the connection
		odbc_close($this->conn);

		return $data;
 	}
}

?>