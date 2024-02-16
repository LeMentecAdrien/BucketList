<?php

namespace App\Service;

class Censurator
{
    public function purify(?string $text): string
    {
        $filename = $this->params->get('app.censurator_file');
        if (file_exists($filename)) {
// on récupère les mots du fichier sous forme d'un tableau
            $words = file($filename);
            foreach ($words as $unwantedWord) {
// Attention : avant de traiter le mot,
// on retire le retour chariot présent en fin de chaque ligne
                $unwantedWord = str_replace(PHP_EOL, '', $unwantedWord);
// autant d'* que de lettres dans le mot :
                $replacement = str_repeat("*", mb_strlen($unwantedWord));
                $text = str_ireplace($unwantedWord, $replacement, $text);
            }
        }
        return $text;
    }
}