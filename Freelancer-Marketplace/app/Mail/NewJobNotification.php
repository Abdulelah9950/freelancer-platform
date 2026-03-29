<?php

namespace App\Mail;

use App\Models\Job;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewJobNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $freelancer;
    public $matchingSkills;

    /**
     * Create a new message instance.
     */
    public function __construct(Job $job, User $freelancer, $matchingSkills)
    {
        $this->job = $job;
        $this->freelancer = $freelancer;
        $this->matchingSkills = $matchingSkills;
        
        Log::info('NewJobNotification constructed', [
            'job_exists' => !is_null($job),
            'freelancer_exists' => !is_null($freelancer),
            'matchingSkills_count' => $matchingSkills ? $matchingSkills->count() : 0
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 New Job Matching Your Skills: ' . ($this->job->title ?? 'New Job'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-job-notification',
        );
    }
}