<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paletsoskus extends Model {
	protected $table = "paletsoskus";

	function Soskus() {
		return $this->belongsTo('Soskus', 'id_sosku');
	}

	function Slots() {
		return $this->belongsTo('Slots', 'id_slot');
	}
}
