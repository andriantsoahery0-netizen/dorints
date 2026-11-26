<?php

// Messages de validation en français (les règles utilisées par l'application)
return [
    'accepted' => 'Le champ :attribute doit être accepté.',
    'array' => 'Le champ :attribute doit être une liste.',
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    'current_password' => 'Le mot de passe est incorrect.',
    'date' => 'Le champ :attribute doit être une date valide.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'exists' => 'La valeur du champ :attribute est invalide.',
    'in' => 'La valeur du champ :attribute est invalide.',
    'integer' => 'Le champ :attribute doit être un nombre entier.',
    'lowercase' => 'Le champ :attribute doit être en minuscules.',
    'max' => [
        'array' => 'Le champ :attribute ne peut pas contenir plus de :max éléments.',
        'numeric' => 'Le champ :attribute ne peut pas dépasser :max.',
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    ],
    'min' => [
        'array' => 'Le champ :attribute doit contenir au moins :min élément(s).',
        'numeric' => 'Le champ :attribute doit être au moins :min.',
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'numeric' => 'Le champ :attribute doit être un nombre.',
    'password' => [
        'letters' => 'Le champ :attribute doit contenir au moins une lettre.',
        'mixed' => 'Le champ :attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers' => 'Le champ :attribute doit contenir au moins un chiffre.',
        'symbols' => 'Le champ :attribute doit contenir au moins un symbole.',
        'uncompromised' => 'Ce :attribute est apparu dans une fuite de données. Choisissez-en un autre.',
    ],
    'required' => 'Le champ :attribute est obligatoire.',
    'string' => 'Le champ :attribute doit être un texte.',
    'unique' => 'Cette valeur du champ :attribute est déjà utilisée.',

    // Noms des champs affichés dans les messages
    'attributes' => [
        'email' => 'adresse e-mail',
        'password' => 'mot de passe',
        'current_password' => 'mot de passe actuel',
        'name' => 'nom',
        'nom' => 'nom',
        'prenom' => 'prénom',
        'telephone' => 'téléphone',
        'adresse' => 'adresse',
        'client_id' => 'client',
        'statut' => 'statut',
        'description' => 'remarque',
        'montant_envoye' => 'montant envoyé',
        'mode_paiement' => 'mode de paiement',
        'sens' => 'sens de la conversion',
        'role' => 'rôle',
        'taux_eur_vers_mga' => 'taux',
    ],
];
