<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	protected $appends = ["open"];

	public function getOpenAttribute(){
		return true;
	}
}