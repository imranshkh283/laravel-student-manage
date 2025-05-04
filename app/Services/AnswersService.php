<?php

namespace App\Services;

use App\Repositories\AnswersRepository;
use Illuminate\Support\Facades\DB;
use App\Models\questions_session as QuestionsSession;

class AnswersService
{
    protected $answersRepository;
    protected $userId;
    public function __construct(AnswersRepository $answersRepository)
    {
        $this->answersRepository = $answersRepository;
    }

    public function saveAnswers(array $submittedAnswers): void
    {
        $this->userId = auth()->user()->id;
        $answers = [];
        foreach ($submittedAnswers as $questionId => $answer) {
            $answers[] = [
                'user_id' => $this->userId,
                'question_id' => $questionId,
                'attempted_answer' => $answer,
            ];
        }

        DB::transaction(function () use ($answers) {
            $this->answersRepository->saveAnswers($answers);
            QuestionsSession::where('user_id', $this->userId)
                ->where('status', 'pending')
                ->update(['status' => 'completed', 'completed_at' => now()]);
        });
    }
}
