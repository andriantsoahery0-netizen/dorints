// Remplace les textes anglais de Breeze par du français écrit directement dans les vues.
// Usage (à la racine du projet) :  node francais-direct.js
const fs = require("fs");
const path = require("path");

const T = {
  "Email": "Adresse email",
  "Password": "Mot de passe",
  "Name": "Nom",
  "Remember me": "Se souvenir de moi",
  "Forgot your password?": "Mot de passe oublié ?",
  "Log in": "Se connecter",
  "Register": "S'inscrire",
  "Confirm Password": "Confirmer le mot de passe",
  "Already registered?": "Déjà inscrit ?",
  "Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.":
    "Mot de passe oublié ? Pas de souci. Indiquez-nous votre adresse email et nous vous enverrons un lien pour en choisir un nouveau.",
  "Email Password Reset Link": "Envoyer le lien de réinitialisation",
  "Reset Password": "Réinitialiser le mot de passe",
  "This is a secure area of the application. Please confirm your password before continuing.":
    "Ceci est une zone sécurisée de l'application. Veuillez confirmer votre mot de passe avant de continuer.",
  "Confirm": "Confirmer",
  "Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.":
    "Merci pour votre inscription ! Avant de commencer, pouvez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez rien reçu, nous vous en enverrons un autre avec plaisir.",
  "A new verification link has been sent to the email address you provided during registration.":
    "Un nouveau lien de vérification a été envoyé à l'adresse email indiquée lors de l'inscription.",
  "Resend Verification Email": "Renvoyer l'email de vérification",
  "Log Out": "Déconnexion",
  "Profile": "Profil",
  "Dashboard": "Tableau de bord",
  "Profile Information": "Informations du profil",
  "Update your account's profile information and email address.":
    "Mettez à jour les informations de votre profil et votre adresse email.",
  "Your email address is unverified.": "Votre adresse email n'est pas vérifiée.",
  "Click here to re-send the verification email.": "Cliquez ici pour renvoyer l'email de vérification.",
  "A new verification link has been sent to your email address.":
    "Un nouveau lien de vérification a été envoyé à votre adresse email.",
  "Save": "Enregistrer",
  "Saved.": "Enregistré.",
  "Update Password": "Modifier le mot de passe",
  "Ensure your account is using a long, random password to stay secure.":
    "Utilisez un mot de passe long et aléatoire pour garder votre compte sécurisé.",
  "Current Password": "Mot de passe actuel",
  "New Password": "Nouveau mot de passe",
  "Delete Account": "Supprimer le compte",
  "Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.":
    "Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de le supprimer, téléchargez les données ou informations que vous souhaitez conserver.",
  "Are you sure you want to delete your account?": "Voulez-vous vraiment supprimer votre compte ?",
  "Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.":
    "Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Saisissez votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.",
  "Cancel": "Annuler",
  "You're logged in!": "Vous êtes connecté !",
};

// Regex qui accepte ' ou " autour du texte, et \' pour une apostrophe échappée
function src(eng) {
  return eng.replace(/[.*+?^${}()|[\]\\]/g, "\\$&").replace(/'/g, "\\\\?'");
}

function walk(dir, out = []) {
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    const p = path.join(dir, e.name);
    if (e.isDirectory()) walk(p, out);
    else if (e.name.endsWith(".blade.php")) out.push(p);
  }
  return out;
}

const root = path.join(process.cwd(), "resources", "views");
if (!fs.existsSync(root)) {
  console.error("Dossier resources/views introuvable : lance ce script à la racine du projet.");
  process.exit(1); 
}

let total = 0;
for (const file of walk(root)) {
  const original = fs.readFileSync(file, "utf8");
  let txt = original;
  let n = 0;
  for (const [eng, fr] of Object.entries(T)) {
    const e = src(eng);
    // :value="__('Email')"  ->  value="Adresse email"
    const attr = new RegExp(`:value="__\\(\\s*(['"])${e}\\1\\s*\\)"`, "g");
    txt = txt.replace(attr, () => { n++; return `value="${fr}"`; });
    // {{ __('Log in') }}  ->  Se connecter
    const text = new RegExp(`\\{\\{\\s*__\\(\\s*(['"])${e}\\1\\s*\\)\\s*\\}\\}`, "g");
    txt = txt.replace(text, () => { n++; return fr; });
  }
  if (txt !== original) {
    fs.writeFileSync(file, txt, "utf8");
    console.log(`✔ ${path.relative(process.cwd(), file)} (${n} remplacement${n > 1 ? "s" : ""})`);
    total += n;
  }
}
console.log(`\nTotal : ${total} remplacement(s).`);

// Ce qui reste d'appels __('...') (non traités)
let reste = 0;
for (const file of walk(root)) {
  fs.readFileSync(file, "utf8").split(/\r?\n/).forEach((line, i) => {
    const m = line.match(/__\(\s*(['"])(.*?)\1\s*\)/);
    if (m) {
      if (!reste) console.log("\nTextes encore en appel __() à vérifier :");
      reste++;
      console.log(`  ${path.relative(process.cwd(), file)}:${i + 1}  ->  ${m[2]}`);
    }
  });
}
if (!reste) console.log("Aucun appel __() restant.");
