<?php

namespace App\Repositories;

use App\Models\Answers;

class AnswersRepository
{

    public function saveAnswers(array $answers): void
    {
        Answers::insert($answers);
    }
}
