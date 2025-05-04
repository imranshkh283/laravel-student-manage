<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class questions_session extends Model
{
    protected $table = 'questions_sessions';

    public $timestamps = false;

    protected $fillable = ['user_id', 'started_at', 'completed_at', 'status', 'score'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
