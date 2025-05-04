<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Questions;
use App\Models\questions_session as QuestionsSession;
use Illuminate\Support\Facades\DB;

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


        $userId = auth()->user()->id;

        DB::transaction(function () use ($questions, $userId) {

            QuestionsSession::create([
                'user_id' => $userId,
                'started_at' => now(),
                'status' => 'pending',
            ]);

            $arr_questions = [];
            foreach ($questions as $question) {
                $arr_questions[] = [
                    'user_id' => $userId,
                    'question_id' => $question['id'],
                    'data' => json_encode($question),
                    'category' => $question['category'],
                    'difficulty' => $question['difficulty'],
                    'status' => '0',
                    'created_at' => now(),
                ];
            }

            Questions::insert($arr_questions);
        });
    }
}
