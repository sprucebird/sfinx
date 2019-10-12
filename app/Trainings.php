<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainings extends Model
{
    public function group() {
      return $this->belongsTo(groups::class, 'group', 'id');
    }

    public function dancers() {
      return $this->belongsToMany(dancer::class, 'dancer_training');
    }
}
