<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Current extends Model {

	protected $table = 'current';
	protected $fillable = ['title', 'artist','album', 'user', 'likes', 'liked_by', 'preview_url'];
	
}
