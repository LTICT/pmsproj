<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
	protected $table = "links";
	public $primaryKey = "id";
	public $timestamps = false;
}

