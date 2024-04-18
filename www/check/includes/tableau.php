<table>
  <tr>
<?php
// Chemin vers le fichier JSON
$chemin_fichier_json = '/opt/vod.lgdl.org/video_convert/waiting_list.json';

// Lire le contenu du fichier JSON
$contenu_json = file_get_contents($chemin_fichier_json);

// Convertir le JSON en tableau associatif PHP
$tableau_assoc = json_decode($contenu_json, true);


foreach ($tableau_assoc["info_for_web"] as $info) {
    echo "<td>". $info[0] ."</td>";
}
echo "</tr><tr>";
foreach ($tableau_assoc["info_for_web"] as $info) {
    if ($info[1] == 0){
        echo "<td><i class='bi bi-arrow-clockwise'></i></td>";
    }
    else if ($info[1] == 1){
        echo "<td><i class='bi bi-check-circle-fill'></i></td>";
    }

    else if ($info[1] == 2){
        echo "<td><i class='bi bi-x-circle-fill'></i></td>";
    }

}
?>

  </tr>
</table>