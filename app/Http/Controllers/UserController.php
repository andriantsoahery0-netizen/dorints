<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UserController;
use App\Models\ActivityLog;

class UserController extends Controller
{
    // Liste des utilisateurs (admin + agents)
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // Formulaire de création
    public function create()
    {
        return view('users.create');
    }

    // Enregistrement d'un nouvel utilisateur
    public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'role' => 'required|in:admin,agent',
    ]);

    $user = User::create([
        'nom' => $validated['nom'],
        'prenom' => $validated['prenom'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    ActivityLog::enregistrer(
        'creation_utilisateur',
        "Utilisateur {$user->name} ({$user->role}) créé"
    );

return redirect()->route('users.index')
    ->with('success', 'Utilisateur créé avec succès.');
}

    //Activation et désactivation compte
    public function toggleActif(User $user)
{
    if ($user->id === auth()->id()) {
        return redirect()->route('users.index')
            ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
    }

    $user->update(['actif' => !$user->actif]);

    $action = $user->actif ? 'activé' : 'désactivé';

    ActivityLog::enregistrer(
        'modification_statut_utilisateur',
        "Compte de {$user->name} {$action}"
    );

    return redirect()->route('users.index')
        ->with('success', "Compte {$action} avec succès.");
}

    // Suppression
    public function destroy(User $user)
{
    // Empêche un admin de se supprimer lui-même
    if ($user->id === auth()->id()) {
        return redirect()->route('users.index')
            ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
    }

    // Empêche la suppression si l'utilisateur a laissé une trace (commandes, règlements,
    // factures, taux, journal) : on le désactive plutôt, pour garder l'historique
    $aDesDonnees = $user->commandes()->exists()
        || $user->transferts()->exists()
        || \App\Models\Facture::where('user_id', $user->id)->exists()
        || \App\Models\TauxChange::where('user_id', $user->id)->exists()
        || \App\Models\ActivityLog::where('user_id', $user->id)->exists();

    if ($aDesDonnees) {
        return redirect()->route('users.index')
            ->with('error', "Impossible de supprimer {$user->name} : son nom figure dans l'historique (commandes, règlements, factures ou journal). Désactivez plutôt son compte.");
    }

    $nomUser = $user->name;
    $user->delete();

    ActivityLog::enregistrer(
        'suppression_utilisateur',
        "Utilisateur {$nomUser} supprimé"
    );

    return redirect()->route('users.index')
        ->with('success', 'Utilisateur supprimé avec succès.');
}

    public function show(User $user)
{
    $user->load(['commandes.client', 'transferts.client']);

    return view('users.show', compact('user'));
}

}