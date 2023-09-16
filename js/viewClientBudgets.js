$(document).ready(function() {
    var client = new Object();
    // Eventos
    $("#goBack").on("click", function() {
        window.location = "./viewClient-" + client.client_id;
    }); 
    $(".view-budget").on("click", function() { 
        var href = "./" + $(this).attr('aria-href');
        window.location = href;
    }); 
    // Funciones
    function setClient() {
        client.client_id = $("#number").val();
    }
    // Init
    setClient();
});
