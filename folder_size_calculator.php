<?php

/**
 * Calcule la taille totale des fichiers dans un dossier jusqu'à une profondeur donnée
 * 
 * @param string $path Chemin absolu du dossier à analyser
 * @param int $profondeur Profondeur maximale de parcours (0 = aucun parcours)
 * @return int|false Taille totale en octets, ou false en cas d'erreur
 */
function folderSizeCalculator(string $path, string $profondeur): int |false
{
   if ($profondeur <= 0) {
      return 0;
   }
   $filesList = scandir($path);
   if ($filesList === false) {
      return false;
   }

   $result = 0;

   foreach ($filesList as $filename) {
      if ($filename !== "." && $filename !== "..") {
         $fullPath = $path . DIRECTORY_SEPARATOR . $filename;

         if (is_dir($fullPath)) {
            if ($profondeur > 1) {
               $subResult = folderSizeCalculator($fullPath, $profondeur - 1);
               if ($subResult !== false) {
                  $result += $subResult;
               }
            }
         } else {
            $fileSize = filesize($fullPath);
            if ($fileSize !== false) {
               $result += $fileSize;
            }
         }
      }
   }
   return $result;
}

// Demander à l'utilisateur
$path = readline("Donnez un chemin absolu : ");
$depthInput = readline("Donnez la profondeur maximale de parcours : ");

/** @var int $depth Profondeur convertie en entier */
$depth = intval($depthInput);

// Vérification
if (file_exists($path) && is_dir($path)) {
   /** @var int|false $totalSize Taille totale calculée */
   $totalSize = folderSizeCalculator($path, $depth);

   if ($totalSize !== false) {
      /** @var float $sizeInMB Taille convertie en mégaoctets */
      $sizeInMB = $totalSize / (1024 * 1024);
      echo "Taille du dossier (profondeur $depth) : " . round($sizeInMB, 2) . " Mo\n";
   } else {
      echo "Erreur : Impossible de calculer la taille du dossier.\n";
   }
} else {
   echo "Erreur : le chemin spécifié est invalide.\n";
}
