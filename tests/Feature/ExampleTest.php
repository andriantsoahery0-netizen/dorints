<?php

// La page d'accueil renvoie vers la connexion
it('redirige l\'accueil vers la page de connexion', function () {
    $this->get('/')->assertRedirect(route('login'));
});
