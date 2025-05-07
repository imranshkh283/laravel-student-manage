<?php

namespace App\Repositories;

use App\Models\questions_session as QuestionsSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class QuestionSessionRepository
{
    public function getSubmittedQuestionSession()
    {
        return QuestionsSession::where('status', 'completed')->where('completed_at', '<', Carbon::now()->subMinutes(1))->get();
    }

    public function updateStatusAsEvaluated(int $userId)
    {
        QuestionsSession::where('user_id', $userId)->update(['status' => 'evaluated']);
    }

    public function updateStatusAsCompleted(int $userId)
    {
        QuestionsSession::where('user_id', $userId)->where('status', 'pending')->update(['status' => 'completed', 'completed_at' => now()]);
    }

    public function updateStatusAsAbandoned(int $userId)
    {
        QuestionsSession::where('user_id', $userId)->update(['status' => 'abandoned']);
    }

    public function updateStatusAsInProgress(int $userId)
    {
        QuestionsSession::where('user_id', $userId)->update(['status' => 'in_progress']);
    }

    public function updateScore(int $userId, int $score)
    {
        QuestionsSession::where('user_id', $userId)->update(['score' => $score]);
    }

    public function updateGrade(int $userId, string $grade)
    {
        try {
            DB::transaction(function () use ($userId, $grade) {
                QuestionsSession::where('user_id', $userId)->update(['grade' => $grade]);
                $this->updateStatusAsEvaluated($userId);
            });
        } catch (\Throwable $th) {
        }
    }
}
