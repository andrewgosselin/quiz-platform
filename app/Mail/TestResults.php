<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestResults extends Mailable
{
    use Queueable, SerializesModels;

    public $session;
    public $quiz;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($session, $quiz)
    {
        $this->session = $session;
        $this->quiz = $quiz;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->view('pages.quizzes.results')
            ->with("session", $this->session)
            ->with("quiz", $this->quiz)
            ->attach(storage_path('app/public/results/' . $this->session->session_id . '/results.pdf'), [
                'as' => 'results.pdf',
                'mime' => 'application/pdf',
           ]);
    }
}
