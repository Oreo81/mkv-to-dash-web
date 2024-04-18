<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta property="og:title" content="Site Title" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://my.site.com" />
    <meta property="og:image" content="http://my.site.com/images/thumb.png" />
    <meta property="og:description" content="Site description" />
    <meta name="theme-color" content="#FF0000">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vod.lgdl.org</title>

    <link rel="icon" href="https://res.lgdl.org/includes/favicon.ico" />
    <link rel="stylesheet" href="./style/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>


</head>
<body>

        <div class=main>
        <header class="p-3 bg-dark text-white">
            <div class="container">
                <h1 class="d-flex justify-content-center">vod.lgdl.org</h1>
            </div>
        </header>
            <div class=container row>
                
                <hr>
                <div id="dropArea" class="d-flex col" ondrop="handleDrop(event)" >
                    <img src="https://res.lgdl.org/includes/upload.png" alt="Upload Indicator" width="30">
                    <span class="file-input-text">Glissez-déposez des fichiers ici ou cliquez pour sélectionner.</span>
                        <input type="file" id="fileInput" multiple ondragenter="handleDragEnter(event)" ondragleave="handleDragLeave(event)">
                </div>
                <hr>
                <div id="resultat"></div>







                <div class="espace">
                <button class="bouton-toggle bouton-toggle-collapse" type="button" aria-expanded="false">
                  <span>Upload info</span>
                  <span id="open-close-btn" class="border rounded ml-2 px-2">+</span>
                </button>
                <button class="bouton-toggle bouton-clear-collapse" type="button" aria-expanded="false">
                  <span class="border rounded ml-2 px-2">Clear</span>
                </button>
                
                <div class="collapse" id="contenu">
                  <div class="card card-body">

                  <div id="queue"></div>
                  <hr>
                  <div id="progress"></div>
                  <hr>
                  <div id="finito"></div>
                  </div>
                </div>
              </div>
            </div>
        </div>


<script>

function handleFiles(files) {
        for (const file of files) {
            addFileToArray(file);
        }
    }

function addFileToArray(file) {
    filesArray.push(file);
    updateList();
}

function updateList() {
    const fileList = $('#queue');
    fileList.empty();
    for (const fileName of filesArray) {
        fileList.append(`<li>${fileName.name}</li>`);
    }
}

// ----------------------------------------------
// ----------------------------------------------


function handleDrop(event) {
    const files = event.dataTransfer.files;
    handleFiles(files);
    resetDragStyle();
}

function handleDragEnter(event) {
    document.getElementById('dropArea').classList.add('dragover');
}

function handleDragLeave(event) {
    resetDragStyle();
}

function resetDragStyle() {
    document.getElementById('dropArea').classList.remove('dragover');
}

function openFileInput(event) {
    document.getElementById('fileInput').click();
}

// ----------------------------------------------
// ----------------------------------------------



$(document).ready(function() {
    $(".bouton-toggle-collapse").click(function(){
      $("#contenu").toggleClass("show");
        if($("#contenu").hasClass("show")){
            $("#open-close-btn").html("-")
        } else {
            $("#open-close-btn").html("+")
        }
    });

    $(".bouton-clear-collapse").click(function(){
      $("#finito").empty();
    });

    filesArray = [];
    in_upload_now = false;

    $('#fileInput').change(function() {
        handleFiles(this.files);
    });

    setInterval(function() {
        if (filesArray.length > 0 && in_upload_now === false) {
            uploadFiles(filesArray[0]);
            filesArray.shift();
            updateList();
        } else {
            // alert("Veuillez sélectionner au moins un fichier.");
        }       
    }, 1000);

    window.addEventListener('beforeunload', function (e) {
        console.log(in_upload_now);
        console.log(filesArray);
        if (in_upload_now === true && Array.isArray(filesArray) || filesArray.length > 0) {
            var confirmationMessage = "Des fichiers sont en cours de téléchargement. Êtes-vous sûr de vouloir quitter la page ?";

            (e || window.event).returnValue = confirmationMessage;
            return confirmationMessage;
        }
    });
});

// ----------------------------------------------
// ----------------------------------------------

var pattern = /^(\d+\s*-\s*)?(.*?)\s*\((\d{4})\)\s*(.*?)\s*(\d+)([a-zA-Z])?\.(.*?)$/;


function uploadFiles(files, index) {
    console.log(files.name);

    var matches = files.name.match(pattern);
    if (matches !== null) {
        console.log("Le nom correspond au pattern regex.");
        console.log("Nom complet: " + matches[0]);
        console.log("Partie 1: " + matches[1]);
        console.log("Partie 2: " + matches[2]);
        console.log("Année: " + matches[3]);
        console.log("Partie 3: " + matches[4]);
        console.log("Nombre: " + matches[5]);
        console.log("Lettre: " + matches[6]);
        console.log("Extension: " + matches[7]);
        

        in_upload_now = true;
        var formData = new FormData();
        formData.append('file', files);

        
        if($("#contenu").hasClass("show")){
            
            
        } else {
            $("#contenu").toggleClass("show");
            $("#open-close-btn").html("-")
        }

        $.ajax({
            url: 'https://vod.lgdl.org/u/upload.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $("#progress").text("Envoi de '" + files.name + "' : " + percentComplete.toFixed(2) + "%");
                    }
                }, false);

                
                return xhr;
            },
            success: function (response) {
                rep = JSON.parse(response)

                $("#finito").append(rep.msg);
                $("#finito").append("<br>");
                $("#progress").empty();
                in_upload_now = false;
            },
            error: function (error) {
                console.error(error);
                $("#progress").text("<br>Erreur lors de l'envoi de '" + files.name + "'");
                in_upload_now = false;
            }
        });
    } else {
        console.log("Le nom ne correspond pas au pattern regex.");
    }
}

// ---------------------------------------

function call_tableau(){
    $.ajax({
        url: './check/includes/tableau.php', // Chemin vers votre script PHP
        type: 'GET', // Méthode HTTP à utiliser
        dataType: 'html', // Type de données attendu du serveur
        success: function(response){
            $('#resultat').html(response); // Insérer la réponse dans la div avec l'ID 'resultat'
        },
        error: function(xhr, status, error){
            console.error(xhr.responseText); // Afficher les éventuelles erreurs
        }
    });
};

call_tableau()

</script>

</body>
</html>

