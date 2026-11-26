{{-- Ajoute un bouton « œil » dans chaque champ mot de passe pour afficher ou masquer la saisie --}}
<script>
    (function () {
        const OEIL = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>';
        const OEIL_BARRE = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';

        // Affiche (true) ou masque (false) le contenu d'un champ et met l'icône à jour
        window.oeilAfficher = function (champ, visible) {
            champ.type = visible ? 'text' : 'password';
            const bouton = champ.parentNode.querySelector('.oeil-bouton');
            if (bouton) {
                bouton.innerHTML = visible ? OEIL_BARRE : OEIL;
                bouton.setAttribute('aria-label', visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
                bouton.title = visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe';
            }
        };

        function equiper(champ) {
            if (champ.dataset.oeil) return;
            champ.dataset.oeil = '1';

            const conteneur = document.createElement('span');
            conteneur.style.cssText = 'position:relative;display:block;flex:1 1 auto;width:100%;';
            champ.parentNode.insertBefore(conteneur, champ);
            conteneur.appendChild(champ);
            // La marge du haut passe du champ au conteneur
            if (champ.classList.contains('mt-1')) {
                champ.classList.remove('mt-1');
                conteneur.style.marginTop = '0.25rem';
            }
            champ.style.paddingRight = '2.75rem';

            const bouton = document.createElement('button');
            bouton.type = 'button';
            bouton.className = 'oeil-bouton';
            bouton.style.cssText = 'position:absolute;right:0.6rem;top:50%;transform:translateY(-50%);background:none;border:0;padding:0.2rem;color:#64748b;cursor:pointer;line-height:0;';
            bouton.addEventListener('click', function () {
                window.oeilAfficher(champ, champ.type === 'password');
                champ.focus();
            });
            conteneur.appendChild(bouton);
            window.oeilAfficher(champ, false);
        }

        function equiperTout() {
            document.querySelectorAll('input[type="password"]').forEach(equiper);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', equiperTout);
        } else {
            equiperTout();
        }
    })();
</script>
