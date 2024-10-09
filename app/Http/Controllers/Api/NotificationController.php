<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnreadNotifications(Request $request, $id)
    {
        $admin = User::find($id);
        $notifications = $admin->unreadNotifications;
        $admin->unreadNotifications->markAsRead();

        return response()->success($notifications, 200, '');
    }
}
