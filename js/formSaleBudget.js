$(document).ready(function() {
    var budget = new Object();
    budget.totalPrice = 0.00;
    budget.items = new Array();
    budget.client = new Object();
    budget.notes = "";

    var sale = new Object();

    var productToAdd = new Object();
    var products = new Object();
    var clients = new Object();


    // Clientes
    function putClientInDetail() { // OK
        $("#client").empty();
        $("#client").val(budget.client.name);
    }
    function getClients() { // OK
        $("#clientToPush").empty();
        var filterValue = ""; // VER ESTO
        $.get("./viewClients", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) {
            clients = JSON.parse(response);
            clients.forEach(function(c) {
                $("#clientToPush").append('<option value=' + c['client_id'] + '>' + c['name'] + '</option>');
            });
            budget.client = clients[0];
            $("#clientToPush").change(function() {
                var id = this.value;
                clients.forEach(function(c){
                    if(id == c.client_id)
                        budget.client = c;
                });
                putClientInDetail(); 
            });
            putClientInDetail(); 
        });
    }
    getClients();

    // Items y productos
    function validateItemToAdd() { // OK
        if(productToAdd.cost_price.length === 0) { // Revisar si se quieren evitar costos = 0
            alert("Falta el costo del producto a agregar");
            return false;
        }
        if(productToAdd.cost_price < 0) { 
            alert("El costo del producto no puede ser menor a 0");
            return false;
        }
        if(productToAdd.sale_price.length === 0){
            alert("Falta el precio del producto a agregar");
            return false;
        }
        if(productToAdd.sale_price < 0){
            alert("El precio del producto no puede ser menor a 0");
            return false;
        }
        if(productToAdd.quantity.length === 0){
            alert("Falta la cantidad del producto a agregar");
            return false;
        }
        if(productToAdd.quantity <= 0){
            alert("La cantidad del producto no puede ser 0 o menor");
            return false;
        }
        if(productToAdd.product_id.length === 0){
            alert("Falta el código de producto a agregar");
            return false;
        }
        if(productToAdd.description.length === 0){
            alert("Falta la descripción del producto a agregar");
            return false;
        }
        if(productToAdd.total_price.length === 0){
            alert("Falta el total del producto a agregar");
            return false;
        }
        if(productToAdd.total_price < 0){
            alert("El precio total del producto no puede ser menor a 0");
            return false;
        }
        return true;
    }
    function updateTotalItemToAdd() { // OK
        // console.log(productToAdd);
        productToAdd.total_price = (productToAdd.sale_price * productToAdd.quantity).toFixed(2);
        $("#productTotalPrice").empty();
        $("#productTotalPrice").val(productToAdd.total_price);
    }
    function clearItemToAdd() { // OK 
        $("#productCostToPush").empty();
        $("#productPriceToPush").empty();
        $("#productQuantityToPush").empty();
        $("#productCurrentQuantity").empty();
        $("#productId").empty();
        $("#productDesc").empty();
        $("#productTotalPrice").empty();
    }
    function putItemToAdd() { // OK
        clearItemToAdd();
        $("#productCostToPush").val(productToAdd.cost_price);
        $("#productPriceToPush").val(productToAdd.sale_price); 
        $("#productQuantityToPush").val(productToAdd.quantity);
        // var prodTotalPrice = ($("#productPriceToPush").val() * $("#productQuantityToPush").val()).toFixed(2);
        $("#productCurrentQuantity").val(productToAdd.stock_quantity); 
        $("#productId").val(productToAdd.product_id);
        $("#productDesc").val(productToAdd.description);
        $("#productTotalPrice").val(productToAdd.sale_price);
    }
    function getProducts() { // OK
        var filterValue = "";
        $.get("./viewProducts", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) {
            products = JSON.parse(response);
            products.forEach(function(product) {
                $("#productToPush").append('<option value=' + product.product_id + '>' + product.description + '</option>');
            });
            productToAdd = products[0];
            productToAdd.quantity = 1;
            productToAdd.total_price = productToAdd.sale_price;
            $("#productToPush").change(function(){
                var id = this.value;
                products.forEach(function(product){
                    if(product.product_id === id){
                        productToAdd = product;
                        productToAdd.quantity = 1;
                        productToAdd.total_price = productToAdd.sale_price;
                    }
                });
                putItemToAdd(); 
            });
            putItemToAdd();
        });
    }
    getProducts();

    // General 
    function updateTotalPrice() { // OK
        var tp = 0.00;
        budget.items.forEach(function(item){ 
            tp += parseFloat(item.total_price);
        });
        budget.totalPrice = parseFloat(tp).toFixed(2);
        $("#totalPrice").empty();
        var t = "$" + budget.totalPrice;
        $("#totalPrice").html(t);
    }
    function clearDetail() { // REVISAR     
        $("#detailTableBody").empty();
        $("#quantityOfItems").empty();        
    }
    function renderDetail() { // OK
        clearDetail();
        $("#quantityOfItems").append(budget.items.length);        
        budget.items.forEach(function(item, i) {
            $("#detailTableBody").append('<tr id=filaItem_' + i + '></tr>');
            $("#filaItem_"+ i).append('<th scope="row">' + i + '</th>');
            $("#filaItem_"+ i).append('<td>' + item.description + '</td>');
            $("#filaItem_"+ i).append('<td>' + item.cost_price + '</td>');
            $("#filaItem_"+ i).append('<td>' + item.sale_price + '</td>');
            $("#filaItem_"+ i).append('<td>' + item.quantity + '</td>');
            $("#filaItem_"+ i).append('<td>' + item.total_price + '</td>');

            $("#filaItem_"+ i).append('<td><button type="button" class="btn btn-outline-info" id="edit' + i + '">Editar</button></td>');
            $("#edit"+ i).click(function() {
                renderDetail();
            });
            $("#filaItem_"+ i).append('<td><button type="button" class="btn btn-outline-danger" id="delete' + i + '">Borrar</button></td>');
            $("#delete"+ i).click(function() {
                budget.items.splice(i, 1);
                renderDetail();
            });
        });
        updateTotalPrice();
    }
    function validateBudget() { // OK
        if(budget.items.length == 0) {
            alert("Agregá items");
            return false;
        }
        return true;
    }
    function validateSale() { // OK
        if(!validateBudget())
            return false;
        var flag = 1;
        budget.items.forEach(function(item, i) {
            if(item.stock_quantity < item.quantity) {
                alert("No tenés stock suficiente del ítem #" + i + " " + item.description + " para dar de alta la venta");
                flag = 0;
            }
        });
        if(flag == 0)
            return false;
        else
            return true;
    }

    // Stock
    function validateStock(){ // A MODIFICAR EN CASO DE QUE HAYA MÁS DE 1 DEPÓSITO
    //     var ret = true;
    //     saleItems.forEach(function(saleItem, i){
    //         var saleItemTotalQuantity = 0;
    //         saleItems.forEach(function(saleItemTest, j){
    //             if(saleItem.prodId == saleItemTest.prodId) saleItemTotalQuantity += parseInt(saleItemTest.prodQuantity);
    //         });

    //         $.ajax({ //AJAX SÍNCRONO PARA EL CONTROL DE STOCK
    //             async: false,
    //             type: 'get',
    //             url: "./viewStock",
    //             data: {get: true},
    //             success: function(response){
    //                 stockItems = JSON.parse(response);
    //                 stockItems.forEach(function(stockItem, k){
    //                     if(stockItem.product_id == saleItem.prodId){
    //                         if(stockItem.quantity < saleItemTotalQuantity){
    //                             alert("El producto " + saleItem.prodDesc + " sólo cuenta con " + stockItem.quantity + 
    //                             " unidades en stock, usted quiere vender " + saleItemTotalQuantity + " unidades, no se puede dar de alta el pedido");
    //                             ret = false;
    //                         } 
    //                     }
    //                 });
    //             }// ACA IRIA EL error
    //         });
    //     });
    //     return ret; 
    }

    // Changes y clicks
    $("#productCostToPush").change(function() { // OK
        $("#productCostToPush").val(parseFloat($("#productCostToPush").val()).toFixed(2));
        var val = $.trim($("#productCostToPush").val());
        if(val.length > 0)
            productToAdd.cost_price = val;
        else    
            productToAdd.cost_price = 0;
    });
    $("#productPriceToPush").change(function() { // OK
        $("#productPriceToPush").val(parseFloat($("#productPriceToPush").val()).toFixed(2));
        var val = $.trim($("#productPriceToPush").val());
        if(val.length > 0)
            productToAdd.sale_price = val;
        else    
            productToAdd.sale_price = 0;
        updateTotalItemToAdd();
    });
    $("#productQuantityToPush").change(function() { // OK
        $("#productQuantityToPush").val(parseInt($("#productQuantityToPush").val()));
        var val = $.trim($("#productQuantityToPush").val());
        if(val.length > 0)
            productToAdd.quantity = val;
        else    
            productToAdd.quantity = 0;
        updateTotalItemToAdd();
    });
    $("#notes").change(function() { // OK
        budget.notes = $("#notes").val();
        console.log(budget);
    });
    $("#addItem").click(function() { // OK
        if(validateItemToAdd()){
            budget.items.push({...productToAdd});
            renderDetail();
        }
    });
    $("#addBudget").click(function() {
        if(validateBudget()) {
            var jsonBudget = JSON.stringify(budget);
            $.post("./newBudget", {newBudget: true, budget: jsonBudget}, function(response){
                response = JSON.parse(response);
                console.log(response);
                if(response.state === 0) {
                    alert(response.errorMsg)
                } else if (response.state === 1) {
                    alert(response.successMsg)
                    locate("./newBudget");
                }
            });
        }else {
            // alert("error BUD");
        }
    });
    $("#addSale").click(function() {
        // if(validateSale()) {
            var jsonSale = JSON.stringify(budget); // Misma estructura y datos que los presupuestos
            $.post("./newBudget", {newSale: true, sale: jsonSale}, function(response){
                response = JSON.parse(response);
                console.log(response);
                if(response.state === 0) {
                    alert(response.errorMsg)
                } else if (response.state === 1) {
                    alert(response.successMsg)
                    locate("./newBudget");
                }
            });
    });



    $('#clientToPush').select2();
    $('#productToPush').select2();
    
});
