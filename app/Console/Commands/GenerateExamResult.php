<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\QuestionSessionService;

use App\Services\AnswersService;

class GenerateExamResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $userId;
    protected $service;
    protected $answersService;
    protected $signature = 'app:generate-exam-result';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function __construct(QuestionSessionService $service, AnswersService $answersService)
    {
        $this->service = $service;
        $this->answersService = $answersService;
        parent::__construct();
    }
    public function handle()
    {
        $this->info('Command executed ...');
        $submittedQuestionSession = $this->service->getSubmittedQuestionSession();

        if ($submittedQuestionSession->count() < 0) {
            $this->info('No pending exam sessions found');
        } else {
            $this->info('Get User Answers');
            foreach ($submittedQuestionSession as $questionSession) {
                $this->userId = $questionSession->user_id;
                $this->answersService->getAnswersByUserId($this->userId);
                $this->info('DB transaction completed');
            }
        }
    }
}
