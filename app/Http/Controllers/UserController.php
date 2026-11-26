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
        'password' => 'required|string|min:8|confirmed',
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

    // Le ticket d'identifiants n'est affiché qu'une seule fois, juste après la création :
    // le mot de passe en clair n'est jamais enregistré en base
    session()->flash('ticket', [
        'nom_complet' => $user->name,
        'role' => $user->role === 'admin' ? 'Administrateur' : 'Agent',
        'email' => $user->email,
        'mot_de_passe' => $validated['password'],
        'cree_le' => now()->format('d/m/Y à H:i'),
    ]);

    return redirect()->route('users.ticket', $user);
}

    // Réimpression du ticket : l'ancien mot de passe n'est pas lisible (il est haché),
    // on en génère donc un nouveau, provisoire, et on réaffiche le ticket
    public function nouveauTicket(User $user)
{
    if ($user->id === auth()->id()) {
        return redirect()->route('users.index')
            ->with('error', 'Pour votre propre compte, changez le mot de passe dans le menu Profil.');
    }

    $motDePasse = self::motDePasseProvisoire();
    $user->forceFill(['password' => Hash::make($motDePasse)])->save();

    ActivityLog::enregistrer(
        'nouveau_ticket_utilisateur',
        "Nouveau mot de passe provisoire et ticket pour {$user->name}"
    );

    session()->flash('ticket', [
        'nom_complet' => $user->name,
        'role' => $user->role === 'admin' ? 'Administrateur' : 'Agent',
        'email' => $user->email,
        'mot_de_passe' => $motDePasse,
        'cree_le' => now()->format('d/m/Y à H:i'),
        'reimpression' => true,
    ]);

    return redirect()->route('users.ticket', $user);
}

    // Mot de passe provisoire de 10 caractères, sans caractères ambigus (0/O, 1/l/I)
    private static function motDePasseProvisoire(): string
{
    $caracteres = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
    $motDePasse = '';
    for ($i = 0; $i < 10; $i++) {
        $motDePasse .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }

    return $motDePasse;
}

    // Ticket d'identifiants à imprimer pour le nouvel utilisateur
    public function ticket(User $user)
{
    $ticket = session('ticket');

    if (! $ticket || $ticket['email'] !== $user->email) {
        return redirect()->route('users.index')
            ->with('error', "Le ticket n'est disponible qu'une seule fois, juste après la création du compte.");
    }

    return view('users.ticket', compact('user', 'ticket'));
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