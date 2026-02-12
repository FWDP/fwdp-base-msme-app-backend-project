<?php

namespace App\Modules\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Notifications\Models\Notification;
use App\Modules\Notifications\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Notification::query()
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate(10)
        );
    }

    public function markRead(Notification $notification)
    {
        $notification->update([
            'is_read' => true,
        ]);

        return response()->json([
            'message' => "Notification marked as read",
        ]);
    }

    public function broadcast(Request $request, NotificationService $notificationService)
    {
        $data = $request->validate([
           'title' => 'required|string',
           'message' => 'required|string',
           'send_email' => 'boolean',
        ]);

        $users = User::pluck('id');

        foreach ($users as $userId) {
            $notificationService->notify(
                $userId,
                'admin',
                $data['title'],
                $data['message'],
                [],
                $data['send_email']
            );
        }

        return response()->json([
            'message' => "Broadcast notifications sent to all users.",
            'recipients' => count($users),
        ]);
    }
}
