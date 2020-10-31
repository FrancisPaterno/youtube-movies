<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class MovieVotes extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function sharedMovie()
    {
        return $this->belongsTo(SharedMovie::class);
    }
}
