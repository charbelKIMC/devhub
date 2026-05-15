<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Page complète des notifications
    public function index()
    {
        $notifications = Notification::where('destinataire_id', Auth::id())
                                     ->latest('date_creation')
                                     ->paginate(20);

        // Marquer toutes comme lues
        Notification::where('destinataire_id', Auth::id())
                    ->where('est_lue', false)
                    ->update(['est_lue' => true]);

        return view('notifications.index', compact('notifications'));
    }

    public function poll(Request $request)
    {
        $count = Notification::where('destinataire_id', Auth::id())
                            ->where('est_lue', false)
                            ->count();

        $notifications = Notification::where('destinataire_id', Auth::id())
            ->latest('date_creation')
            ->take(8)
            ->get()
            ->map(function($n) {
                return [
                    'id'      => $n->id,
                    'type'    => $n->type,
                    'contenu' => $n->contenu,
                    'lien'    => $n->lien,
                    'est_lue' => (bool) $n->est_lue,
                    'date'    => $n->date_creation
                                ? $n->date_creation->diffForHumans()
                                : 'récemment',
                ];
            });

        return response()->json([
            'count'         => $count,
            'notifications' => $notifications,
        ]);
    }

    // Marquer une seule comme lue
    public function marquerLue($id)
    {
        Notification::where('id', $id)
                    ->where('destinataire_id', Auth::id())
                    ->update(['est_lue' => true]);

        return response()->json(['success' => true]);
    }

    // Marquer toutes comme lues
    public function marquerToutesLues(Request $request)
    {
        Notification::where('destinataire_id', Auth::id())
                    ->where('est_lue', false)
                    ->update(['est_lue' => true]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('notifications.index');
    }
}