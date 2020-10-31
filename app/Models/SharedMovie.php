<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedMovie extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $appends = array('up', 'down');
    protected $vote;

    public function getupAttribute()
    {
        return count($this->movieVotes()->where('vote', 0)->get());
    }

    public function getdownAttribute()
    {
        return count($this->movieVotes()->where('vote', 1)->get());
    }

    public function movieVotes()
    {
        return $this->hasMany(MovieVotes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
