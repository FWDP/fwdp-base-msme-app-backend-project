<?php

namespace App\Modules\Notifications\Services;

use App\Mail\NotificationMail;
use App\Models\User;
use App\Modules\Notifications\Models\Notification;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function notify(
        int $user_id,
        string $type,
        string $title,
        string $message,
        array $data = [],
        bool $sendEmail = false
    )
    {
        $notification = Notification::query()->create([
            'user_id' => $user_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);

        if ($sendEmail) {
            if (User::query()->find($user_id) && User::query()->find($user_id)->email) {
                Mail::to(User::query()->find($user_id)->email)->send(
                    new NotificationMail($title, $message, $data)
                );
            }
        }

        return $notification;
    }

}
