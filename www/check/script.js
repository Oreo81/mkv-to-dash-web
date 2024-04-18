$(document).ready(function(){
    function readLastLines() {
        $.ajax({
            url: 'read_last_lines.php',
            type: 'GET',
            success: function(response) {
                $("#output").html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    
    // Lire les 5 dernières lignes initialement
    readLastLines();

    // Exécuter la fonction toutes les 2 secondes
    setInterval(readLastLines, 2000);
});
