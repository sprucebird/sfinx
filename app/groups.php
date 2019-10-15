<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class groups extends Model
{
	protected $fillable = ['groupName', 'description', 'leader', 'level'];

	public function members()
	{
	     return $this->hasMany(dancer::class, 'group', 'id');
	}

	public function trainings() {
		return $this->hasMany(Trainings::class, 'groupID', 'id');
	}
    //
}
