<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Current extends Model {

  // public $timestamps = false;
	protected $table = 'current';
	protected $fillable = ['title', 'artist','album', 'user', 'likes'];
}
