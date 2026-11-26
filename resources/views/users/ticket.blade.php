<x-app-layout>
    <x-slot name="header">
        <div class="max-w-2xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                {{ !empty($ticket['reimpression']) ? 'Nouveau ticket' : 'Compte créé' }}
            </h2>
        </div>
    </x-slot>

    {{-- Styles du ticket écrits ici pour ne pas dépendre de la recompilation du CSS --}}
    <style>
        .ticket { -webkit-print-color-adjust: exact; print-color-adjust: exact; width: 90mm; margin: 0 auto; background: #fff; border: 1px dashed #94a3b8; border-radius: 12px; padding: 18px 20px; font-family: Arial, Helvetica, sans-serif; color: #0f172a; }
        .ticket-entete { display: flex; align-items: center; gap: 10px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; }
        .ticket-logo { width: 36px; height: 36px; border-radius: 9px; background: #d97706; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; }
        .ticket-societe { font-weight: bold; font-size: 16px; line-height: 1.1; }
        .ticket-societe small { display: block; font-size: 9px; letter-spacing: 1px; color: #1e40af; }
        .ticket-titre { text-align: center; font-size: 11px; font-weight: bold; letter-spacing: 1px; color: #1e40af; margin: 14px 0 4px; }
        .ticket-nom { text-align: center; font-size: 18px; font-weight: bold; margin: 0; }
        .ticket-role { text-align: center; font-size: 12px; color: #64748b; margin: 2px 0 14px; }
        .ticket-ligne { margin-bottom: 10px; }
        .ticket-ligne span { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; }
        .ticket-ligne strong { display: block; font-family: "Courier New", monospace; font-size: 15px; word-break: break-all; background: #eff6ff; border-radius: 6px; padding: 6px 8px; margin-top: 2px; }
        .ticket-pied { border-top: 1px dashed #94a3b8; margin-top: 14px; padding-top: 10px; font-size: 10px; color: #475569; line-height: 1.4; }
        @media print {
            @page { margin: 8mm; }
            body * { visibility: hidden !important; }
            #ticket, #ticket * { visibility: visible !important; }
            #ticket { position: absolute; left: 0; top: 0; margin: 0; }
        }
    </style>

    <div class="py-6">
        <div class="max-w-2xl px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-xl p-4 text-sm" style="border:1px solid #fde68a;background:#fffbeb;color:#78350f;">
                @if (!empty($ticket['reimpression']))
                    Un nouveau mot de passe provisoire a été généré : l'ancien ne fonctionne plus.
                @endif
                Imprimez ce ticket maintenant et remettez-le à {{ $ticket['nom_complet'] }}.
                Pour des raisons de sécurité, ce mot de passe ne sera plus affiché ; en cas d'oubli, utilisez « Ticket » dans la liste des utilisateurs.
            </div>

            <div id="ticket" class="ticket">
                <div class="ticket-entete">
                    <div class="ticket-logo">&#8645;</div>
                    <div class="ticket-societe">D'ORINTS<small>GESTION &amp; TRANSFERT</small></div>
                </div>

                <p class="ticket-titre">IDENTIFIANTS DE CONNEXION</p>
                <p class="ticket-nom">{{ $ticket['nom_complet'] }}</p>
                <p class="ticket-role">{{ $ticket['role'] }}</p>

                <div class="ticket-ligne">
                    <span>Adresse e-mail</span>
                    <strong>{{ $ticket['email'] }}</strong>
                </div>
                <div class="ticket-ligne">
                    <span>Mot de passe</span>
                    <strong>{{ $ticket['mot_de_passe'] }}</strong>
                </div>
                <div class="ticket-ligne">
                    <span>Adresse de connexion</span>
                    <strong>{{ route('login') }}</strong>
                </div>

                <div class="ticket-pied">
                    {{ !empty($ticket['reimpression']) ? 'Ticket réimprimé le' : 'Compte créé le' }} {{ $ticket['cree_le'] }} par {{ auth()->user()->name }}.<br>
                    Changez ce mot de passe dès la première connexion, dans le menu Profil.
                    Ne communiquez jamais vos identifiants.
                </div>
            </div>

            <div class="flex justify-center gap-3">
                <button type="button" onclick="window.print()"
                        class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                    Imprimer le ticket
                </button>
                <a href="{{ route('users.index') }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                    Terminer
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
