$(document).ready(function() {
    var filter = new Object();

    // Eventos
    // $("#filter").on("keyup", function() {
    //     getFilter();
    //     getProducts();
    // });    
    $("#goBack").on("click", function() {
        var id = $("#id").val();
        window.location = "./viewProduct-" + id;
    }); 

    // Filtros
    function getFilter() {
        filter.desc = $.trim($("#filter").val());
    }
});
