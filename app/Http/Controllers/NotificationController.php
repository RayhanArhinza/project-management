<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->with('project')  // Eager load project untuk mengurangi query
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->description,
                    'project_name' => optional($notification->project)->name ?? 'Unknown Project',
                    'due_date' => $notification->due_date ? $notification->due_date->format('d M Y H:i') : null
                ];
            });

        return view('notifications.index', compact('notifications'));
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        // Redirect back untuk refresh halaman jika diperlukan
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }


}
