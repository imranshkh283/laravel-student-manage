<?php

namespace App\Repositories;

use App\Models\Answers;

class AnswersRepository
{

    public function saveAnswers(array $answers): void
    {
        Answers::insert($answers);
    }

    public function getAnswersByUserId(int $userId)
    {
        return Answers::with('question')->where('user_id', $userId)->get()->map(function ($answer) {
            $questionData = $answer->question->data;
            return [
                'id' => $answer->question_id,
                'user_id' => $answer->user_id,
                'question_id' => $answer->question_id,
                'attempted_answer' => $answer->attempted_answer,
                'question_id' => $answer->question->id,
                'correct_answers' => [
                    'id' => $questionData['id'] ?? null,
                    'correct_answers' => $questionData['correct_answers'] ?? [],
                ],
            ];
        });
    }

    public function updateAnswersByUserId(array $conditions, string $correctAnswer): void
    {
        Answers::where([
            'question_id' => $conditions['question_id'],
            'user_id' => $conditions['user_id'],
        ])->update([
            'correct_answer' => $correctAnswer,
            'updated_at' => now(),
        ]);
    }
}
