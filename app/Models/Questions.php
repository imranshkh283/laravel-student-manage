<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'question_id',
        'user_id',
        'data',
        'category',
        'difficulty',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // public function getCorrectAnswersAttribute()
    // {
    //     $data = json_decode($this->data, true);

    //     return $data['correct_answers'] ?? null;
    // }
}
