<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paletposkus extends Model {
	protected $table = "paletposkus";

	function Poskus() {
		return $this->belongsTo(Poskus::class, 'id_posku');
	}

	function Slots() {
		return $this->hasOne(Slots::class, 'id_paletposkus');
	}
}
