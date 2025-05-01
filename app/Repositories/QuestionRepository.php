<?php

namespace App\Repositories;

use App\Models\Questions;

class QuestionRepository
{
    public function getAll()
    {
        return Questions::where([
            'status' => 0,
            'user_id' => auth()->user()->id,
        ])->get();
    }
}
