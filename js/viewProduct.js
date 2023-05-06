$(document).ready(function() {
    var product = new Object();

    // Eventos
    $("#goBack").on("click", function() {
        window.location = "./viewProducts";
    }); 
    $("#viewProductChanges").on("click", function() { 
        window.location = "./viewProduct-" + product.product_id + "-product-changes";
    }); 
    $("#viewPriceChanges").on("click", function() { 
        window.location = "./viewProduct-" + product.product_id + "-price-changes";
    }); 
    $("#viewStockChanges").on("click", function() { 
        window.location = "./viewProduct-" + product.product_id + "-stock-changes";
    }); 

    $("#modifyProduct").on("click", function() { 
        setProduct();
        modifyProduct();
    }); 
    // $("#editCost").on("keyup", function() {
    //     setProduct();
    // });
    // $("#editPrice").on("keyup", function() {
    //     setProduct();
    // });
    // $("#editStock").on("keyup", function() {
    //     setProduct();
    // });

    // Producto
    function setProduct() {
        product.product_id       = $("#prodId").val();
        product.cost_price       = $("#editCost").val();
        product.product_price    = $("#editPrice").val();
        product.product_quantity = $("#editStock").val();
        // console.log(product);
    }
    function modifyProduct() {
        productEncode = JSON.stringify(product);
        $.post("./viewProduct", {modifyProduct: true, product: productEncode}, function(response) {
            response = JSON.parse(response);
            if(response.state == 1) {
                alert(response.successMsg); 
                location.reload();
            }
            else if(response.state == 0) {
                alert(response.errorMsg); 
            }
        });
    }

    // Init
    setProduct();
});
