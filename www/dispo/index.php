<?php

// Lire le contenu du fichier JSON
$json_data = file_get_contents('/opt/vod.lgdl.org/films/films.json');

// Décoder le contenu JSON en un tableau associatif
$films = json_decode($json_data, true);

// Parcourir chaque élément du tableau associatif
foreach ($films as $key => $value) {
    // Extraire le nom du film en supprimant l'extension et les parenthèses
    
    // Afficher au format souhaité
    echo '<a href="./player?f=' . $key . '">' . $value . '</a><br>';
}

