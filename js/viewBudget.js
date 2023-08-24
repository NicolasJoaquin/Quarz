$(document).ready(function() {
    var product         = new Object();
    var budgetNumber    = $('#budgetNumber').val();
    // Eventos
    $("#goBack").on("click", function() {
        window.location = "./viewBudgets";
    }); 
    $("#newVersion").on("click", function() {
        window.location = "./newBudgetVersion-"+budgetNumber;
    }); 
    $("#newBudgetToSaleConfirm").on("click", function() {
        var number  = $("#budgetNumber").val();
        var version = $("#budgetVersion").val();
        $.post("./newSaleBudget", {newBudgetToSale: true, number: number, version: version}, function(response) {
            console.log(response);
            response = JSON.parse(response);
            if(response.state === 0) {
                alert(response.errorMsg)
            } else if (response.state === 1) {
                alert(response.successMsg)
                location.href ='./viewSale-' + response.sale.id; //REVISAR Q MANDA EL BACK
            }
        });
    }); 

    // Funciones
    // Init
});
