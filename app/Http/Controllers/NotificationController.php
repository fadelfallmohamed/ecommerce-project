<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);
        $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            Log::info('Tentative de marquage de la notification comme lue', [
                'notification_id' => $id,
                'user_id' => Auth::id(),
                'method' => $request->method()
            ]);
            
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            
            Log::info('Notification marquée comme lue avec succès', [
                'notification_id' => $id,
                'order_id' => $notification->order_id
            ]);
            
            // Rediriger vers la commande associée
            return redirect()->route('orders.show', $notification->order_id);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage de la notification comme lue', [
                'error' => $e->getMessage(),
                'notification_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors du marquage de la notification comme lue.');
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            $user = Auth::user();
            $count = $user->unreadNotifications->count();
            
            Log::info('Tentative de marquage de toutes les notifications comme lues', [
                'user_id' => $user->id,
                'unread_count' => $count,
                'method' => $request->method()
            ]);
            
            $user->unreadNotifications->markAsRead();
            
            Log::info('Toutes les notifications ont été marquées comme lues', [
                'user_id' => $user->id,
                'marked_read' => $count
            ]);
            
            return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage de toutes les notifications comme lues', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors du marquage des notifications comme lues.');
        }
    }
}
