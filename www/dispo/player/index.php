<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video.js DASH Player</title>
    <link href="https://vjs.zencdn.net/7.16.0/video-js.css" rel="stylesheet">
    <script src="https://vjs.zencdn.net/7.16.0/video.js"></script>
    <script src="https://cdn.dashjs.org/latest/dash.all.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/videojs-seek-buttons/dist/videojs-seek-buttons.min.js"></script>
    <link href="./style.css" rel="stylesheet">


</head>
<style>
body{
    margin:0;
    background-color: #000000;
}

.videoPlayer-dimensions {
  background-color: #000000;
  position: relative;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

</style>


<?php 

$film = $_GET["f"];
$url = "../films/{$film}/video/output.mpd"

?>

<body>
  <div class="video-container">
    <video class="video-js vjs-default-skin"  id="videoPlayer"  controls>
      <!-- <track kind="subtitles" src="./result/fre.vtt" srclang="Fre" label="Français"> -->
      <?php

        $fichiers = scandir("../films/{$film}/sub/");

        // Parcourir chaque fichier
        foreach ($fichiers as $fichier) {
          if ($fichier != "." && $fichier != "..") {
            echo "<track kind='subtitles' src='../films/{$film}/sub/{$fichier}' srclang='{$fichier}' label='{$fichier}'>";
          }
          
        }

      ?>
    </video>
  </div>
  <script>
  const url = <?php  echo "'".$url."'";  ?>;
    const player = videojs('videoPlayer');
    player.ready(() => {
      player.src({
        src: url,
        type: 'application/dash+xml'
      });
      player.fill(false);
    });

    player.seekButtons({
        forward: 10,
        back: 10
      });

  </script>
</body>
</html>