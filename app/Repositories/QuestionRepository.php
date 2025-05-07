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

    public function updateQuestionStatus(int $questionId, int $status)
    {
        Questions::where('id', $questionId)->update(['status' => $status]);
    }
}
