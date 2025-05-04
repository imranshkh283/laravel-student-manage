<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizApiService;
use App\Services\QuestionService;
use App\Services\AnswersService;

class ExamsController extends Controller
{
    protected $quizApi;
    protected $questionService;
    protected $answersService;

    public function __construct(
        QuizApiService $quizApi,
        QuestionService $questionService,
        AnswersService $answersService
    ) {
        $this->middleware('auth');
        $this->quizApi = $quizApi;
        $this->questionService = $questionService;
        $this->answersService = $answersService;
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

    public function submit_answer(Request $request)
    {
        if (!$request->user()->id) {
            return redirect()->route('quiz_page')->withError('You are not logged in.');
        }

        $submittedAnswers = $request->input('answer');
        $this->answersService->saveAnswers($submittedAnswers);

        return redirect()->route('api_config')->withSuccess('Answer submitted successfully.');
    }
}
