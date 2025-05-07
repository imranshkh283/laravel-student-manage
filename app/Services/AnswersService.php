<?php

namespace App\Services;

use App\Repositories\AnswersRepository;
use App\Services\QuestionService;
use App\Services\QuestionSessionService;
use Illuminate\Support\Facades\DB;
use App\Models\questions_session as QuestionsSession;
use Symfony\Component\Console\Question\Question;
use Illuminate\Support\Collection;

class AnswersService
{
    protected $userId;
    protected $answersRepository;
    protected $questionService;
    protected $questionSessionService;
    public function __construct(
        AnswersRepository $answersRepository,
        QuestionService $questionService,
        QuestionSessionService $questionSessionService
    ) {
        $this->answersRepository = $answersRepository;
        $this->questionService = $questionService;
        $this->questionSessionService = $questionSessionService;
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
            $this->questionSessionService->updateStatusAsInProgress($this->userId);

            /* QuestionsSession::where('user_id', $this->userId)
                ->where('status', 'pending')
                ->update(['status' => 'completed', 'completed_at' => now()]); */
        });
    }

    public function getAnswersByUserId(int $userId)
    {
        $data = $this->answersRepository->getAnswersByUserId($userId);
        $totalQuestions = $data->count();

        $correctCount = 0;
        foreach ($data as $key => $value) {
            $correctAnswers = $value['correct_answers']['correct_answers'] ?? [];
            $correctAnswerKey = collect($correctAnswers)
                ->filter(fn($val) => $val === 'true')
                ->keys()
                ->first();

            $answer = str_replace('_correct', '', $correctAnswerKey);

            if (!$answer) {
                continue;
            } else {
                $condition = [
                    'question_id' => $value['question_id'],
                    'user_id' => $value['user_id'],
                ];

                // count correct answer
                $isCorrect = $value['attempted_answer'] === $answer;

                DB::transaction(function () use ($condition, $answer) {
                    $this->answersRepository->updateAnswersByUserId($condition, $answer);
                    $this->questionService->updateQuestionStatus($condition['question_id'], 1);
                });
                if ($isCorrect) {
                    $correctCount++;
                }
            }
        }

        $this->questionSessionService->updateScore($userId, $correctCount);
        $marks = intval($correctCount / $totalQuestions * 100);
        $level = 'easy';
        $grade = collect()->getGrade($level, $marks);
        // dd($marks, $grade);
        $this->questionSessionService->updateGrade($userId, $grade);
    }
}
