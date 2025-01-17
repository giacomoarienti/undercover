<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(10);

        return view('user.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function edit(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'read' => 'required|boolean'
        ]);

        $notification = $request->user()->notifications()->find($request->get('id'));
        if(!$notification) {
            return to_route('notifications')->withErrors(['id' => 'Notification not found']);
        }

        $notification->read = $request->get('read');
        $notification->save();

        return to_route('notifications')->with('message', 'Notification updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $notification = $request->user()->notifications()->find($request->get('id'));
        if(!$notification) {
            return to_route('notifications')->withErrors(['id' => 'Notification not found']);
        }

        $notification->delete();

        return to_route('notifications')->with('message', 'Notification deleted');
    }
}
