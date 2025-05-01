<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Questions;
use Illuminate\Container\Attributes\Auth;

class QuizApiService
{
    public function fetchQuestions($limit = 10, $category = 'Linux', $difficulty = 'easy')
    {
        $response = Http::withOptions([
            'verify' => base_path('certs/cacert.pem'), // Adjust path as needed
        ])->get('https://quizapi.io/api/v1/questions', [
            'apiKey'    => config('services.quizapi.key'),
            'limit'     => $limit,
            'category'  => $category,
            'difficulty' => $difficulty,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data;
        } else {
            Log::error('Error while fetching questions from Quiz API: ' . $response->status());
            return [
                'error' => 'Unable to fetch data',
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }
    }

    public function insertQuestion()
    {

        $questions = $this->fetchQuestions($limit = 10, $category = 'Linux', $difficulty = 'easy');

        $arr_questions = [];

        $userId = auth()->user()->id;

        foreach ($questions as $question) {
            $arr_questions[] = [
                'user_id' => $userId,
                'question_id' => $question['id'],
                'data' => json_encode($question),
                'category' => $question['category'],
                'difficulty' => $question['difficulty'],
            ];
        }

        Questions::insert($arr_questions);
    }
}
