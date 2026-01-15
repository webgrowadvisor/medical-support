<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use App\Models\DoctorWallet;
use App\Models\WalletTransaction;
use App\Models\UserWallet;
use App\Models\UserNotification;
use App\Models\DoctorNotification;
use App\Models\AdminNotification;

class NotificationController extends Controller
{

    public function user(Request $request)
    {

        $userId = auth()->id();

        $notifications = UserNotification::where('user_id', $userId)
        ->where('is_read', 0)->latest()
        ->paginate(10);

        $unreadCount = UserNotification::where('user_id', $userId)
        ->where('is_read', 0)
        ->count();

        $html = view('front.user-notifications', compact('notifications'))->render();

        return response()->json([
            'html' => $html,
            'unread' => $unreadCount
        ]);

    }

    public function doctor(Request $request)
    {

        $doctorId = Auth::guard('seller')->id();

        $notifications = DoctorNotification::where('doctor_id', $doctorId)
        ->where('is_read', 0)->latest()
        ->paginate(10);

        $unreadCount = DoctorNotification::where('doctor_id', $doctorId)
        ->where('is_read', 0)
        ->count();

        $html = view('seller.user-notifications', compact('notifications'))->render();

        return response()->json([
            'html' => $html,
            'unread' => $unreadCount
        ]);

    }

    public function admin(Request $request)
    {
        $notifications = AdminNotification::where('is_read', 0)
        ->latest()
        ->paginate(10);

        $unreadCount = AdminNotification::where('is_read', 0)
        ->count();

        $html = view('admin.user-notifications', compact('notifications'))->render();

        return response()->json([
            'html' => $html,
            'unread' => $unreadCount
        ]);

    }


    public function userRead(Request $request, $id)
    {
        $userId = auth()->id();
        UserNotification::where('id', $id)
        ->where('user_id', $userId)
        ->update(['is_read' => 1]);

        return back();
    }

    public function doctorRead(Request $request, $id)
    {      
        $doctorId = Auth::guard('seller')->id();
        DoctorNotification::where('id', $id)
        ->where('doctor_id', $doctorId)
        ->update(['is_read' => 1]);
        
        return back();
    }

    public function adminRead($id)
    {
        AdminNotification::where('id', $id)->update(['is_read' => 1]);
        return back();
    }

    public function allRead()
    {
        // USER
        if (Auth::guard('web')->check()) {

            UserNotification::where('user_id', Auth::guard('web')->id())
                ->update(['is_read' => 1]);

        }
        // DOCTOR
        elseif (Auth::guard('seller')->check()) {            
            DoctorNotification::where('doctor_id', Auth::guard('seller')->id())
                ->update(['is_read' => 1]);

        }
        // ADMIN
        elseif (Auth::guard('admin')->check()) {            
            AdminNotification::where('is_read', '0')->update(['is_read' => 1]);
        }

        return back()->with('success_msg', 'All notifications marked as read');
    }

}
