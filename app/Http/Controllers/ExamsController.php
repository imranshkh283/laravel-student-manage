<?php

namespace App\Http\Controllers;

use App\Services\QuizApiService;
use App\Services\QuestionService;

class ExamsController extends Controller
{
    protected $quizApi;
    protected $questionService;
    public function __construct(QuizApiService $quizApi, QuestionService $questionService)
    {
        $this->middleware('auth');
        $this->quizApi = $quizApi;
        $this->questionService = $questionService;
    }

    public function index()
    {
        return view('exams.index');
    }

    public function loadQuiz()
    {
        $this->quizApi->insertQuestion();

        return response()->json([
            'redirect' => route('quiz_page'),
        ]);
    }

    public function quizPage()
    {
        $questions = $this->questionService->getAll();

        return view('exams.quiz', compact('questions'));
    }
}
