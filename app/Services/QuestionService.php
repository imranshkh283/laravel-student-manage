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
        $questions = [];

        $question = $this->questionRepository->getAll();

        foreach ($question as $q) {
            $data = json_decode($q->data, true);
            $questions[] = [
                'id' => $q->id,
                'question_id' => $q->question_id,
                'category' => $q->category,
                'difficulty' => $q->difficulty,
                'question' => $data['question'],
                'answers' => $data['answers'],
                'multiple_correct_answers' => $data['multiple_correct_answers'],
            ];
        }

        return $questions;
    }
}
