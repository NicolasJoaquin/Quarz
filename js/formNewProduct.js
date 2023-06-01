$(document).ready(function() {
    var product = new Object();

    // Eventos
    $("#goBack").on("click", function() {
        window.location = "./viewProducts";
    }); 

    $("#save").on("click", function() { 
        setProduct();
        save();
    }); 

    // Producto
    function setProduct() {
        product.desc        = $.trim($("#desc").val());
        product.packingUnit = $.trim($("#packingUnit").val());
        product.provider    = $.trim($("#provider").val());
        product.costPrice   = $.trim($("#costPrice").val());
        product.salePrice   = $.trim($("#salePrice").val());
        product.quantity    = $.trim($("#quantity").val());
        // console.log(product);
    }
    function save() {
        productEncode = JSON.stringify(product);
        $.post("./newProduct", {new: true, product: productEncode}, function(response) {
            console.log(response);
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

    // Proveedores
    function getProviders() {
        $.get("./viewProviders", {getProvidersToSelect: true}, function(response) {
            response = JSON.parse(response);
            if(response.state == 1) {
                console.log(response.successMsg); 
            }
            else if(response.state == 0) {
                console.log(response.errorMsg); 
                return;
            }
            response.providers.forEach(function(p) {
                var sel = "";
                if(p['provider_id'] == 1)
                    $("#provider").append('<option value=' + p['provider_id'] + ' selected>' + p['name'] + '</option>'); // Diamont
                else
                    $("#provider").append('<option value=' + p['provider_id'] + '>' + p['name'] + '</option>');
            });
        });
    }

    // Init
    setProduct();
    getProviders();
});
