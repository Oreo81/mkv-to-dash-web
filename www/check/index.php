<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Lecture des 5 dernières lignes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
    <div class="container" >
        <h1 id="title" class="text-center">
            <?php 

                $chemin_json = '/opt/vod.lgdl.org/video_convert/waiting_list.json';

                // Lire le contenu du fichier JSON
                $contenu_json = file_get_contents($chemin_json);

                // Convertir le JSON en tableau associatif PHP
                $json_decode = json_decode($contenu_json, true);

                // Vérifier si la conversion a réussi
                if ($json_decode !== null) {
                    // Lire la valeur "current" du JSON
                    $currentFilm = $json_decode['current'];

                    // Afficher la valeur extraite du JSON
                    echo '<script>document.getElementById("title").innerText = "' . $currentFilm . '";</script>';
                } else {
                    // Afficher un message d'erreur si la conversion a échoué
                    echo 'Erreur lors de la lecture du fichier JSON.';
                }

            ?>
        </h1>
        <div id="output">

        </div>
    </div>
</body>
</html>

<script>
    var currentFilm = json_data.current;
</script>
