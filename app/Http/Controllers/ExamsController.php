<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizApiService;
use App\Services\QuestionService;
use App\Models\questions_session as QuestionsSession;
use App\Models\Answers;
use Illuminate\Support\Facades\DB;

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

    public function submit_answer(Request $request)
    {
        if (!$request->user()->id) {
            return redirect()->route('quiz_page')->withError('You are not logged in.');
        }
        $userId = $request->user()->id;

        $submittedAnswers = $request->input('answer');
        $answers = [];
        foreach ($submittedAnswers as $questionId => $answer) {
            $answers[] = [
                'user_id' => $userId,
                'question_id' => $questionId,
                'attempted_answer' => $answer,
            ];
        }

        DB::transaction(function () use ($answers, $userId) {
            Answers::insert($answers);
            QuestionsSession::where('user_id', $userId)
                ->where('status', 'pending')
                ->update(['status' => 'completed', 'completed_at' => now()]);
        });

        return redirect()->route('api_config')->withSuccess('Answer submitted successfully.');
    }
}
