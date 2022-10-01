<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paletsoskus extends Model {
    protected $table = "paletsoskus";

    function Soskus() {
        return $this->belongsTo(Soskus::class, 'id_sosku');
    }

    function Slots() {
        return $this->belongsTo(Slots::class, 'id_slot');
    }
}
