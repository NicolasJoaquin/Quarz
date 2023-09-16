$(document).ready(function() {
    var client = new Object();
    // Eventos
    $("#goBack").on("click", function() {
        window.location = "./viewClients";
    }); 
    $("#viewClientSales").on("click", function() { 
        window.location = "./viewClient-" + client.id + "-sales";
    }); 
    $("#viewClientBudgets").on("click", function() { 
        window.location = "./viewClient-" + client.id + "-budgets";
    }); 
    $("#editClient").on("click", function() {
        setClient();
        jsonClient = JSON.stringify(client);
        $.post("./viewClient", {editClient: true, data: jsonClient}, function(response) {
            console.log(response);
            response = JSON.parse(response);
            console.log(response.msg);
            if(response.state === 0) {
                alert(response.msg)
            } else if (response.state === 1) {
                alert(response.msg)
                location.href ='';
            }
        });
    }); 
    // Funciones
    function setClient() {
        client.id           = $.trim($("#number").val());
        client.name         = $.trim($("#name").val());
        client.dni          = $.trim($("#dni").val());
        client.cuit         = $.trim($("#cuit").val());
        client.nickname     = $.trim($("#nickname").val());
        client.direction    = $.trim($("#direction").val());
        client.email        = $.trim($("#email").val());
        client.phone        = $.trim($("#phone").val());
    }
    // Init
    setClient();
});
