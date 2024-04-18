<?php

$chemin_fichier_info = '/opt/vod.lgdl.org/video_convert/convert/output/info.json';
$contenu_json = file_get_contents($chemin_fichier_info);
$data = json_decode($contenu_json, true);

$time_total_film = $data['format']['duration'];
$duration_microseconds = intval($time_total_film * 1e6);

// Chemin du dossier à lister
$dossier = '/opt/vod.lgdl.org/video_convert/progress/';

// Liste les fichiers avec une extension .php dans le dossier
$fichiers = glob($dossier . '/*.txt');

// Affiche les fichiers
foreach ($fichiers as $fichier) {
    $fichier_ = basename($fichier);
    

    $file = "/opt/vod.lgdl.org/video_convert/progress/{$fichier_}";
    $lines = file($file);
    // On prend les 12 dernières lignes
    $last_lines = array_slice($lines, -12);

    if (strpos($fichier_,'title') == false ) {
        $all_i = substr(explode("=",$last_lines[11])[1], 0, -1);
    }
    else {
        $all_i = substr(explode("=",$last_lines[8])[1], 0, -1);
    }


    // echo $testt;
    // $i = 'continue';
    // echo "<script>console.log('{$i}');</script>";

    if ($all_i === strval('end')) {
        echo $fichier_ . ": ";
        echo "<i class='bi bi-check-circle-fill'></i>&nbsp;";
    }

    else if ($all_i === strval('continue') ) {

        echo "<hr>" . $fichier_ . "<br>";

        $time_us= intval(explode("=",$last_lines[5])[1]);
        $speed= floatval(substr(explode("=",$last_lines[10])[1], 0, -1));

        $us_restant = ($duration_microseconds- $time_us)/ $speed / 1000000;
        $minute_restant = floor($us_restant / 60);
        $seconde_restant = $us_restant % 60;

        echo "Temps restant: " . $minute_restant . " minutes et " . $seconde_restant . " secondes";
        echo "<br>";

        $pcent=  number_format($time_us*100/$duration_microseconds, 2);
        

        echo '<div class="progress">';
        echo "<div class='progress-bar progress-bar-striped progress-bar-animated' ole='progressbar' aria-label='Example with label' style='width:{$pcent}%;' aria-valuenow='{$pcent}' aria-valuemin='0' aria-valuemax='100'>{$pcent}%</div>";
        echo '</div>';

        // foreach ($last_lines as $line) {
        //     echo $line . "<br>";
        // }
    }
    
    

    else {
        echo $fichier_ . ": ";
        echo "<i class='bi bi-x-circle-fill'></i>&nbsp;";
    }



}
?>

