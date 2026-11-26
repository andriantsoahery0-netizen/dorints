<?php

namespace App\Support;

// Met un prénom en forme : « hery nirina » -> « Hery Nirina », « jean-marc » -> « Jean-Marc »
class Prenom
{
    public static function formater(?string $prenom): ?string
    {
        if ($prenom === null) {
            return null;
        }
        $prenom = preg_replace('/\s+/', ' ', trim($prenom));

        return mb_convert_case(mb_strtolower($prenom, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }
}
