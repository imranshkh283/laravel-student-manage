<?php

namespace App\Services;

use App\Repositories\QuestionRepository;

class QuestionService
{
    protected $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function getAll()
    {
        return $this->questionRepository->getAll()->map(function ($question) {
            $data = json_decode($question->data, true);

            return [
                'id' => $question->id,
                'question_id' => $question->question_id,
                'category' => $question->category,
                'difficulty' => $question->difficulty,
                'question' => $data['question'],
                'answers' => $data['answers'],
                'multiple_correct_answers' => $data['multiple_correct_answers'],
            ];
        })->toArray();
    }
}
