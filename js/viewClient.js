$(document).ready(function() {
    var client = new Object();

    // Eventos
    $("#goBack").on("click", function() {
        window.location = "./viewClients";
    }); 
    $("#viewClientSales").on("click", function() { 
        window.location = "./viewClient-" + client.client_id + "-sales";
    }); 
    $("#viewClientBudgets").on("click", function() { 
        window.location = "./viewClient-" + client.client_id + "-budgets";
    }); 
    // Funciones
    function setClient() {
        client.client_id = $("#number").val();
    }
    // Init
    setClient();
});
