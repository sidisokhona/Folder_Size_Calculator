<?php

function folderSizeCalculator($path, $profondeur)
{
   if ($profondeur <= 0) {
      return 0;
   }
   $filesList = scandir($path);
   $result = 0;
   foreach ($filesList as $filename) {
      if ($filename !== "." && $filename !== "..") {
         $fullPath = $path . DIRECTORY_SEPARATOR . $filename;
         if (is_dir($fullPath)) {
            if ($profondeur > 1) {
               $result += folderSizeCalculator($fullPath, $profondeur - 1);
            }
         } else {
            $result += filesize($fullPath);
         }
      }
   }
   return $result;
}

// Demander à l'utilisateur
$path = readline("Donnez un chemin absolu : ");
$depthInput = readline("Donnez la profondeur maximale de parcours : ");

$depth = intval($depthInput); // conversion en entier

// Vérification
if (file_exists($path) && is_dir($path)) {
   $sizeInMB = folderSizeCalculator($path, $depth) / (1024 * 1024);
   echo "Taille du dossier (profondeur $depth) : " . round($sizeInMB, 2) . " Mo\n";
} else {
   echo "Erreur : le chemin spécifié est invalide.\n";
}
