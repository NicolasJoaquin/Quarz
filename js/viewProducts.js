$(document).ready(function() {
    var products = new Object(),
        filter   = new Object();
    
    // Eventos
    $("#filter").on("keyup", function() {
        getFilter();
        getProducts();
    });    
    // Filtros
    function getFilter() {
        filter.desc = $.trim($("#filter").val());
    }
    // Productos
    function getProducts() { 
        $.get("./viewProducts", {getToDashboard: true, filterDesc: filter.desc, order: false}, function(response) {
            response = JSON.parse(response);
            products = response.products;
            console.log(response.msg);
            if(response.state == 1) {
                renderTable();
            }
            else if(response.state == 0) {
                alert(response.msg); 
            }
        });
    }
    function renderTable() { 
        clearTable();
        products.forEach(function(product) {
            $("#productsTableBody").append('<tr id=prodRow_' + product.product_id + '></tr>');
            $("#prodRow_"+ product.product_id).append('<th class="td-cod" scope="row">' + product.product_id + '</th>');
            $("#prodRow_"+ product.product_id).append('<td class="td-desc">' + product.description + '</td>');
            // $("#prodRow_"+ product.product_id).append('<td>' + product.provider_name + '</td>');
            $("#prodRow_"+ product.product_id).append('<td class="td-pack">' + product.packing_unit + '</td>');
            $("#prodRow_"+ product.product_id).append('<td class="td-acc"> $' + product.cost_price + '</td>');
            $("#prodRow_"+ product.product_id).append('<td class="td-acc"> $' + product.sale_price + '</td>');
            $("#prodRow_"+ product.product_id).append('<td class="td-stock">' + product.stock_quantity + '</td>');
            // $("#prodRow_"+ product.product_id).append('<td class="td-acc">' + "..." + '</td>');

            $("#prodRow_"+ product.product_id).click(function() { // Revisar
                viewProduct(product.product_id);
            });
        });
    }
    function clearTable() {     
        $("#productsTableBody").empty();
    }
    function viewProduct(prodId) {
        window.location = "./viewProduct-" + prodId;
        // alert("redirección a vista ampliada # " + prodId);
    }  
    // Init
    getFilter();
    getProducts();    
});
