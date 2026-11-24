<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'adresse',
        'email',
    ];

    // Total des factures du client
    private const SQL_FACTURE = "(select coalesce(sum(f.montant), 0) from factures f
        inner join commandes c on c.id = f.commande_id
        where c.client_id = clients.id)";

    // Total réglé sur ces factures (transferts en ariary)
    private const SQL_REGLE = "(select coalesce(sum(t.montant_envoye), 0) from transferts t
        inner join commandes c on c.id = t.commande_id
        inner join factures f on f.commande_id = c.id
        where c.client_id = clients.id and t.devise_source = 'MGA')";

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function transferts(): HasMany
    {
        return $this->hasMany(Transfert::class);
    }

    public function factures(): HasManyThrough
    {
        return $this->hasManyThrough(Facture::class, Commande::class);
    }

    // Ajoute total_facture et total_regle à chaque client de la requête
    public function scopeAvecSituation(Builder $query): Builder
    {
        return $query->select('clients.*')
            ->selectRaw(self::SQL_FACTURE . ' as total_facture')
            ->selectRaw(self::SQL_REGLE . ' as total_regle');
    }

    // Clients qui ont tout réglé
    public function scopeAyantRegle(Builder $query): Builder
    {
        return $query->whereRaw(self::SQL_FACTURE . ' > 0')
            ->whereRaw(self::SQL_REGLE . ' >= ' . self::SQL_FACTURE);
    }

    // Clients qui n'ont pas encore tout réglé
    public function scopeNonRegle(Builder $query): Builder
    {
        return $query->whereRaw(self::SQL_REGLE . ' < ' . self::SQL_FACTURE);
    }

    public function totalFacture(): float
    {
        return (float) ($this->total_facture ?? $this->factures()->sum('factures.montant'));
    }

    public function totalRegle(): float
    {
        if (isset($this->total_regle)) {
            return (float) $this->total_regle;
        }

        return (float) Transfert::where('devise_source', 'MGA')
            ->whereHas('commande', fn ($q) => $q->where('client_id', $this->id)->has('facture'))
            ->sum('montant_envoye');
    }

    public function resteARegler(): float
    {
        return max(0, $this->totalFacture() - $this->totalRegle());
    }

    // aucune_facture, regle ou non_regle
    public function situation(): string
    {
        if ($this->totalFacture() <= 0) {
            return 'aucune_facture';
        }

        return $this->resteARegler() > 0 ? 'non_regle' : 'regle';
    }

    // Pratique pour afficher le nom complet facilement dans les vues
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}
