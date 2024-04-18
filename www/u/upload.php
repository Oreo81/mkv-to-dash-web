<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = '/opt/vod.lgdl.org/video_convert/on_hold/';

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $file = $_FILES['file'];

    if ($file['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $file['tmp_name'];
        $file_name = basename($file['name']);

        $destination = $uploadDir . $file_name ;

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);


        if(strtolower($extension) === 'mkv') {
            if (move_uploaded_file($tmp_name, $destination)) {
                
                echo json_encode(['msg' => "Envoi de $file_name FINI "]);
            } else {
                echo json_encode(['msg' => "Erreur lors du déplacement du fichier $file_name vers $destination"]);
            }
        } else {
            echo json_encode(['msg' => "Le fichier n'est pas un fichier MKV."]);
        }
    } else {
        echo json_encode(['msg' => "Erreur lors du téléchargement du fichier : Code d'erreur " . $file_name['error']]);
    }
} else {
    echo json_encode(['msg' => 'Requête invalide']);
}

?>
