<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('/', function()
{
	return View::make('index');
});


Route::get('/content', function()
{
	$fp = stream_socket_client("tcp://192.168.200.244:1678", $errno, $errstr, 30);
	if (!$fp) {
	    echo "$errstr ($errno)<br />\n";
	} else {
	    //var_dump($fp);
	    fwrite($fp, "GSUM\r\n\r\n");
	    fwrite($fp, "LSUM\r\n\r\n");
	    fwrite($fp, "PSUM\r\n\r\n");
	    fwrite($fp, "SSUM\r\n\r\n");
	    //echo fgets($fp, 1024);
	    fclose($fp);
	}

	$record = new Record;

	//$total_calls = $record->total_calls();

	return View::make('content')
		->with('statuses',  $record->statuses())
		->with('calls_waiting',  $record->calls_waiting())
		->with('acd_calls',  $record->total_connected_calls('', FALSE))
		->with('calls_abandoned',  $record->calls_abandoned())
		->with('total_calls',  $record->total_calls('', FALSE))
		->with('agents', $record->current_agents());
});

/*Route::get('/interval', function()
{
	$record = new Record;

	return View::make('interval')
		->with('inbound_logs',  $record->inbound_logs());
});*/

Route::get('/ameyo', function()
{
	$campaigns = Ameyo::inb_campaigns();
	$campaign_ids = '';
	foreach ($campaigns as $campaign) {
		$campaign_ids = $campaign_ids.$campaign->id.',';
	}
	$campaign_ids = substr($campaign_ids, 0, -1);

	//QUERY 1
	$query1 = "
		select sum(case when active_calls is null then 0 else active_calls end) as active_calls,
			sum(case when agent_on_call is null then 0 else agent_on_call end) as agent_on_call,
			sum(agent_on_break) as agents_on_break,
			sum(agent_ready_on_auto_call) as agents_available,
			sum(agent_ready_off_auto_call) as agents_manual,
			(sum(agent_on_break) + sum(agent_ready_off_auto_call) + sum(agent_ready_on_auto_call)) as agents_total
		from campaign_runtime_data 
		where campaign_id in($campaign_ids)
			and batch in (SELECT max(batch) from campaign_runtime_data where campaign_id in($campaign_ids))
	";
	$result1 = DB::select($query1);


	//QUERY 2
	$query2 = "
		select agent_queue_name, 
			case when agents_in_queue is null then 0 else agents_in_queue end as agents_in_queue, 
			available_agents, 
			allocated_agents, 
			pending_calls, 
			longest_wait_time, 
			longest_idle_time 
		from (select count(*) as agents_in_queue, 
			agent_queue_id from agent_queue_user as aqu, 
				(SELECT campaign_id, ush.user_id 
					from campaign_user_working_history as cwh, 
						user_session_history as ush 
					where cwh.stop_working_time is null 
						and cwh.session_id=ush.session_id 
						and ush.logout_time is null) as temp
			where aqu.campaign_context_id = temp.campaign_id 
				and aqu.user_id = temp.user_id 
				and campaign_id in($campaign_ids)
			group by agent_queue_id) av 
		right outer join (
			select agent_queue_id, 
				agent_queue_name, 
				available_agents, 
				(case when allocated_agents is null then 0 else allocated_agents end) - (case when on_hold_calls is null then 0 else on_hold_calls end ) as allocated_agents, 
				pending_calls, 
				(case when (split_part((date_added-longest_wait_time)::text,'.',1)) is null or (split_part((date_added-longest_wait_time)::text,'.',1))<='00:00:00' then '00:00:00' else (split_part((date_added-longest_wait_time)::text,'.',1)) end) as longest_wait_time, (case when (split_part((date_added-longest_idle_time)::text,'.',1)) is null or (split_part((date_added-longest_idle_time)::text,'.',1))<='00:00:00' then '00:00:00' else (split_part((date_added-longest_idle_time)::text,'.',1)) end) as longest_idle_time 
			from agent_queue_runtime_data 
			where campaign_id in($campaign_ids) and batch in (SELECT max(batch) from agent_queue_runtime_data where campaign_id in($campaign_ids)) ) qd on av.agent_queue_id = qd.agent_queue_id order by agent_queue_name
	";
	$result2 = DB::select($query2);

	//QUERY 3
	$query3 = "
		select (case when agent_on_break is null then 0 else agent_on_break end) + (case when agent_ready_off_auto_call is null then 0 else agent_ready_off_auto_call end) + (case when agent_ready_on_auto_call is null then 0 else agent_ready_on_auto_call end) as logged_in_count, 
			(case when agent_on_break is null then 0 else agent_on_break end ) as break_count, 
			(case when agent_ready_on_auto_call is null then 0 else agent_ready_on_auto_call end ) as autocall_on_count 
		from cc_runtime_data 
		order by date_added desc limit 1
	";
	$result3 = DB::select($query3);

	//QUERY 4
	$query4 = "
		SELECT sum(case when pending_calls is null then 0 else pending_calls end) as calls_in_ivr, 
			sum(case when allocated_agents is null then 0 else allocated_agents end) as calls_on_agents 
		from agent_queue_runtime_data where campaign_id in($campaign_ids) and batch in (SELECT max(batch) from agent_queue_runtime_data where campaign_id in($campaign_ids))
	";
	$result4 = DB::select($query4);

	//QUERY 5
	$query5 ="
		select (
			select count(*) from call_history where campaign_id in ($campaign_ids) and is_outbound = 'f' and date_added between '2014-03-17 00:00:00' and '2014-03-17 23:59:59'
		) as total_calls,
		(
			select count(*) from call_history where campaign_id in ($campaign_ids) and is_outbound = 'f' and call_result = 'FAILURE' and date_added between '2014-03-17 00:00:00' and '2014-03-17 23:59:59'
		) as total_abandon,
		(
			select count(*) from call_history where campaign_id in ($campaign_ids) and is_outbound = 'f' and call_result != 'FAILURE' and date_added between '2014-03-17 00:00:00' and '2014-03-17 23:59:59'
		) as total_acd
	";
	$result5 = DB::select($query5);

	//save results
	foreach ($result1 as $result) {
		$results['campaign']['active_calls'] = $result->active_calls;
		$results['campaign']['agent_on_call'] = $result->agent_on_call;
		$results['campaign']['total_agents'] = $result->agents_total;
		$results['campaign']['agents_on_break'] = $result->agents_on_break;
		$results['campaign']['agents_on_manual'] = $result->agents_manual;
		$results['campaign']['agents_available'] = $result->agents_available;

	}

	$cntr=0;
	$results['queue']['agents'] = 0;
	$results['queue']['agents_available'] = 0;
	$results['queue']['agents_allocated'] = 0;
	$results['queue']['calls_in_queue'] = 0;
	$results['queue']['longest_call_waiting'] = '00:00:00';
	foreach($result2 as $result)
	{
		$results['queue'][$cntr]["name"]=$result->agent_queue_name;
		$results['queue'][$cntr]["agents"]=$result->agents_in_queue;
		$results['queue'][$cntr]["agents_available"]=$result->available_agents;
		$results['queue'][$cntr]["agents_allocated"]=$result->allocated_agents;
		$results['queue'][$cntr]["calls_in_queue"]=$result->pending_calls;
		$results['queue'][$cntr]["longest_wait_time"]=$result->longest_wait_time;
		$results['queue'][$cntr]["longest_idle_time"]=$result->longest_idle_time;
		$cntr++;
		$results['queue']['agents'] += $result->agents_in_queue;
		$results['queue']['agents_available'] += $result->available_agents;
		$results['queue']['agents_allocated'] += $result->allocated_agents;
		$results['queue']['calls_in_queue'] += $result->pending_calls;
		$results['queue']['longest_call_waiting'] = $results['queue']['longest_call_waiting']>=$result->longest_wait_time?$results['queue']['longest_call_waiting']:$result->longest_wait_time;
	}

	foreach ($result3 as $result) {
		$results['agent']['logged_in'] = $result->logged_in_count;
		$results['agent']['on_break'] = $result->break_count;
		$results['agent']['autocall_on'] = $result->autocall_on_count;
	}

	foreach ($result4 as $result) {
		$results['call']['in_ivr'] = $result->calls_in_ivr;
		$results['call']['in_acd'] = $result->calls_on_agents;
	}

	foreach ($result5 as $result) {
		$results['history']['total_calls'] = $result->total_calls;
		$results['history']['total_abandon'] = $result->total_abandon;
		$results['history']['total_acd'] = $result->total_acd;
	}


	return json_encode($results);
	//return var_dump(DB::connection('pgsql')->select('select * from campaign_runtime_data'));
});

Route::get('/interval', function()
{
	$today = date("Y-m-d");
	//"2014-03-17";

	$query_offered = "select date_added from call_history where campaign_id in (8,9,10) and is_outbound = 'f' and date_added between '$today 00:00:00' and '$today 23:59:59' order by date_added ";
	$query_answered = "select date_added from call_history where campaign_id in (8,9,10) and is_outbound = 'f' and call_result ilike '%SUCCESS%' and date_added between '$today 00:00:00' and '$today 23:59:59'  order by date_added ";
	$query_abandoned = "select date_added from call_history where campaign_id in (8,9,10) and is_outbound = 'f' and call_result ilike '%FAIL%' and date_added between '$today 00:00:00' and '$today 23:59:59'  order by date_added ";

	$result_offered = DB::select($query_offered);
	$result_answered = DB::select($query_answered);
	$result_abandoned = DB::select($query_abandoned);


	$data = array();

	foreach ($result_offered as $result) {
		/*var_dump(substr($result->date_added, 11, 2));
		var_dump(substr($result->date_added, 17, 2));*/
		$data['offered'][] = array(
				'minutes' => substr($result->date_added, 17, 2),
				'hour' => substr($result->date_added, 11, 2),
			);
		/*var_dump($result);*/
	}

	foreach ($result_answered as $result) {
		$data['answered'][] = array(
				'minutes' => substr($result->date_added, 17, 2),
				'hour' => substr($result->date_added, 11, 2),
			);
	}

	foreach ($result_abandoned as $result) {
		$data['abandoned'][] = array(
				'minutes' => substr($result->date_added, 17, 2),
				'hour' => substr($result->date_added, 11, 2),
			);
	}

	return View::make('interval')
		->with('inbound_logs',  $data);
});