<?php

namespace App\Services;

use App\Repositories\QuestionSessionRepository;

class QuestionSessionService
{
    protected $questionSessionRepository;

    public function __construct(QuestionSessionRepository $questionSessionRepository)
    {
        $this->questionSessionRepository = $questionSessionRepository;
    }

    public function getSubmittedQuestionSession()
    {
        return $this->questionSessionRepository->getSubmittedQuestionSession();
    }

    public function updateScore(int $userId, int $score)
    {
        $this->questionSessionRepository->updateScore($userId, $score);
    }

    public function updateGrade(int $userId, string $grade)
    {
        $this->questionSessionRepository->updateGrade($userId, $grade);
    }

    public function updateStatusAsEvaluated(int $userId)
    {
        $this->questionSessionRepository->updateStatusAsEvaluated($userId);
    }
    public function updateStatusAsCompleted(int $userId)
    {
        $this->questionSessionRepository->updateStatusAsCompleted($userId);
    }
    public function updateStatusAsAbandoned(int $userId)
    {
        $this->questionSessionRepository->updateStatusAsAbandoned($userId);
    }
}
