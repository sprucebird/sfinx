<?php

namespace App;
use \App\groups;
use Illuminate\Database\Eloquent\Model;

class dancer extends Model
{
	protected $fillable = [
		'firstName',
		'lastName',
		'primaryPhone',
		'birthDate',
		'city',
		'group',
	];
	public function currentGroup()
	{
		return $this->belongsTo(groups::class, 'group', 'id');
	}
	public function rfid()
	{
		return $this->hasOne(RFID::class, 'Owner', 'id');
	}
	public function entries()
	{
		return $this->hasMany(Entrie::class, 'Owner');
	}
	public function trainings() {
		return $this->belongsToMany(Trainings::class, 'dancer_training');
	}
    //
}
