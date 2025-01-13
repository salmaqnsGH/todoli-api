<?php

namespace App\Mail\Activity;

use App\Constants\ActivityAction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ActivityLogMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $messageText;

    public function __construct(
        private $user,
        private $action,
        private array $changes,
        private $modelName,
        private Carbon $timestamp
    ) {
        $this->messageText = $this->formatMessage();
    }

    private function formatMessage(): string
    {
        $oldData = $newData = '';

        foreach ($this->changes as $field => $change) {
            if ($change['old'] !== null) {
                $oldData .= "{$field}: {$change['old']}, ";
            }
            if ($change['new'] !== null) {
                $newData .= "{$field}: {$change['new']}, ";
            }
        }

        return sprintf(
            '%s: %s %s %s %s%s',
            $this->timestamp->toDateTimeString(),
            $this->user->full_name,
            ActivityAction::getName($this->action),
            $this->modelName,
            'with data '.rtrim($newData, ', '),
            $oldData ? ' (old data: '.rtrim($oldData, ', ').')' : ''
        );
    }

    public function build()
    {
        return $this->subject('Todoli Notification')
            ->view('emails.activity.activity-log');
    }
}
