<?php

class Ameyo extends Eloquent {

	public static function inb_campaigns() {
 		$campaigns = DB::select('select id, name from campaign_context where type = ?', array('Interactive Voice Application'));

 		return $campaigns;
 	}

}