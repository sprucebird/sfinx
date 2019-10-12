<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
	protected $fillable = [
		'price', 'member', 'for_month'
	];

	public function Owner()
	{
		return $this->belongsTo(dancer::class, 'member');
	}
    //
}
