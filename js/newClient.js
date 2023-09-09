$(document).ready(function() {
    /* Variables */
    var client = new Object();
    /* Eventos */
    $("#goBack").on("click", function() {
        window.location = "./viewClients";
    }); 
    $("#save").on("click", function() { 
        setClient();
        save();
    }); 
    /* Funciones */    
    function setClient() {
        client.name      = $.trim($("#name").val());
        client.cuit      = $.trim($("#cuit").val());
        client.nickname  = $.trim($("#nickname").val());
        client.dni       = $.trim($("#dni").val());
        client.email     = $.trim($("#email").val());
        client.phone     = $.trim($("#phone").val());
        client.direction = $.trim($("#direction").val());

        console.log(client);
    }
    function save() {
        encodeData = JSON.stringify(client);
        $.post("./newClient", {newClient: true, data: encodeData}, function(response) {
            console.log(response);
            response = JSON.parse(response);
            console.log(response.msg);
            if(response.state == 1) {
                alert(response.msg); 
                location.reload();
            }
            else {
                alert(response.msg); 
            }
        });
    }
    // Init
    setClient();
});
