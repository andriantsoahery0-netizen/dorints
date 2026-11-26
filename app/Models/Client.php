<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    // Le numéro est toujours enregistré au format 034 12 345 67
    protected function telephone(): Attribute
    {
        return Attribute::make(set: fn ($valeur) => self::formaterTelephone($valeur));
    }

    // Le nom est toujours enregistré (et donc affiché) en majuscules
    protected function nom(): Attribute
    {
        return Attribute::make(
            set: fn ($valeur) => $valeur === null ? null : mb_strtoupper(trim($valeur), 'UTF-8')
        );
    }

    // Renvoie les 9 chiffres du numéro malgache (341234567), ou null
    public static function chiffresNationaux(?string $numero): ?string
    {
        $chiffres = preg_replace('/\D/', '', (string) $numero);

        if (str_starts_with($chiffres, '00261')) {
            $chiffres = substr($chiffres, 5);
        } elseif (str_starts_with($chiffres, '261') && strlen($chiffres) === 12) {
            $chiffres = substr($chiffres, 3);
        } elseif (str_starts_with($chiffres, '0') && strlen($chiffres) === 10) {
            $chiffres = substr($chiffres, 1);
        }

        return strlen($chiffres) === 9 ? $chiffres : null;
    }

    // 0341234567, +261341234567 ou 34 12 345 67 deviennent 034 12 345 67
    public static function formaterTelephone(?string $numero): ?string
    {
        $n = self::chiffresNationaux($numero);
        if ($n === null) {
            return $numero === null ? null : trim($numero);
        }

        return sprintf('0%s %s %s %s',
            substr($n, 0, 2), substr($n, 2, 2), substr($n, 4, 3), substr($n, 7, 2));
    }

    // Numéro malgache reconnu, ou numéro étranger commençant par + ou 00
    public static function telephoneValide(?string $numero): bool
    {
        if (self::chiffresNationaux($numero) !== null) {
            return true;
        }
        $chiffres = preg_replace('/\D/', '', (string) $numero);

        return preg_match('/^\s*(\+|00)/', (string) $numero) === 1
            && strlen($chiffres) >= 8 && strlen($chiffres) <= 15;
    }

    // Recherche par nom et/ou prénom, dans n'importe quel ordre :
    // « Rasoa », « Marie », « Marie Rasoa » ou « ras mar » trouvent Rasoa Marie
    public function scopeNomContient(Builder $query, string $recherche): Builder
    {
        $mots = preg_split('/\s+/', trim($recherche), -1, PREG_SPLIT_NO_EMPTY);
        if (! $mots) {
            return $query->whereRaw('1 = 0');
        }
        foreach ($mots as $mot) {
            $query->where(fn ($q) => $q->where('nom', 'like', "%{$mot}%")
                ->orWhere('prenom', 'like', "%{$mot}%"));
        }

        return $query;
    }

    // Recherche d'un numéro quel que soit le format saisi (034…, +261…, 34 12…)
    public function scopeTelephoneContient(Builder $query, string $recherche): Builder
    {
        $chiffres = preg_replace('/\D/', '', $recherche);
        foreach (['00261', '261', '0'] as $prefixe) {
            if (str_starts_with($chiffres, $prefixe) && strlen($chiffres) > strlen($prefixe) + 2) {
                $chiffres = substr($chiffres, strlen($prefixe));
                break;
            }
        }
        if (strlen($chiffres) < 3) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereRaw("replace(telephone, ' ', '') like ?", ['%' . $chiffres . '%']);
    }

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

    // Le prénom commence toujours par une majuscule (« tiana » -> « Tiana »)
    protected function prenom(): Attribute
    {
        return Attribute::make(set: fn ($valeur) => \App\Support\Prenom::formater($valeur));
    }
}
