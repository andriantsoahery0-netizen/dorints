<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Notifications\TauxChangeModifie;

class NotificationController extends Controller
{
    // Toutes les notifications de l'utilisateur connecté
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    // Clic sur une notification : elle passe en « lue » et on ouvre la page concernée
    public function ouvrir(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $commandeId = $notification->data['commande_id'] ?? null;
        if ($commandeId && Commande::whereKey($commandeId)->exists()) {
            return redirect()->route('commandes.show', $commandeId);
        }

        if ($notification->type === TauxChangeModifie::class) {
            return redirect()->route('taux-changes.index');
        }

        return redirect()->route('notifications.index')
            ->with('error', "L'élément lié à cette notification n'existe plus.");
    }

    public function toutMarquerLu()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}
