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
    // Funciones
    // Init
});
