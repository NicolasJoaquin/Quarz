$(document).ready(function() {
    var budget = new Object();
    budget.subtotalPrice = 0.00;
    budget.discount      = 0.00;
    budget.tax           = 0.00;
    budget.ship          = 0.00;
    budget.totalPrice    = 0.00;
    budget.shipMethod    = new Object();
    budget.payMethod     = new Object();
    budget.items         = new Array();
    budget.client        = new Object();
    budget.notes         = "";

    var sale = new Object();

    var productToAdd = new Object();
    var products     = new Object();
    var clients      = new Object();
    var shipMethods  = new Object();
    var payMethods   = new Object();

    // Getters
    // Clientes
    function getClients() { // OK
        $("#clientToPush").empty();
        var filterValue = ""; // VER ESTO
        $.get("./viewClients", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) {
            clients = JSON.parse(response);
            clients.forEach(function(c) {
                $("#clientToPush").append('<option value=' + c['client_id'] + '>' + c['name'] + '</option>');
            });
            budget.client = clients[0];
            renderClientInDetail(); 
            $("#clientToPush").change(function() {
                var id = this.value;
                clients.forEach(function(c){
                    if(id == c.client_id)
                        budget.client = c;
                });
                renderClientInDetail(); 
            });
        });
    }
    // Métodos de envío y de pago
    function getShipmentMethods() { 
        $("#shipMethod").empty();
        $.get("./viewShipmentMethods", {getShipmentMethodsToSelect: true}, function(response) {
            response    = JSON.parse(response);
            shipMethods = response.shipMethods;
            shipMethods.forEach(function(m) {
                $("#shipMethod").append('<option value=' + m['shipment_method_id'] + '>' + m['title'] + '</option>');
            });
            budget.shipMethod = shipMethods[0]; // Ver que método de envío se pone por default
            renderShipMethodInDetail(); 
            $("#shipMethod").change(function() {
                var id = this.value;
                shipMethods.forEach(function(m){
                    if(id == m.shipment_method_id)
                        budget.shipMethod = m;
                });
                renderShipMethodInDetail(); 
            });
        });
    }    
    function getPaymentMethods() { 
        $("#payMethod").empty();
        $.get("./viewPaymentMethods", {getPaymentMethodsToSelect: true}, function(response) {
            response   = JSON.parse(response);
            payMethods = response.payMethods;
            payMethods.forEach(function(m) {
                $("#payMethod").append('<option value=' + m['payment_method_id'] + '>' + m['title'] + '</option>');
            });
            budget.payMethod = payMethods[0]; // Ver que método de envío se pone por default
            renderPayMethodInDetail(); 
            $("#payMethod").change(function() {
                var id = this.value;
                payMethods.forEach(function(m){
                    if(id == m.payment_method_id)
                        budget.payMethod = m;
                });
                renderPayMethodInDetail(); 
            });
        });
    }    
    // Productos
    function getProducts() { // OK
        var filterValue = "";
        $.get("./viewProducts", {getProductsToSelect: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) {
            response = JSON.parse(response);
            products = response.products; // Falta handle de errores
            products.forEach(function(product) {
                $("#productToPush").append('<option value=' + product.product_id + '>' + product.description + '</option>');
            });
            productToAdd = products[0];
            productToAdd.quantity = 1;
            productToAdd.total_price = productToAdd.sale_price;
            renderItemToAdd();
            $("#productToPush").change(function(){
                var id = this.value;
                products.forEach(function(product){
                    if(product.product_id === id){
                        productToAdd = product;
                        productToAdd.quantity = 1;
                        productToAdd.total_price = productToAdd.sale_price;
                    }
                });
                renderItemToAdd(); 
            });
        });
    }

    // Validaciones
    // Items
    function validateItemToAdd() { // OK
        if(productToAdd.cost_price.length === 0 || productToAdd.cost_price == "NaN") { // Revisar si se quieren evitar costos = 0
            alert("Falta el costo del producto a agregar");
            return false;
        }
        if(productToAdd.cost_price < 0) { 
            alert("El costo del producto no puede ser menor a 0");
            return false;
        }
        if(productToAdd.sale_price.length === 0 || productToAdd.sale_price == "NaN"){
            alert("Falta el precio del producto a agregar");
            return false;
        }
        if(productToAdd.sale_price < 0){
            alert("El precio del producto no puede ser menor a 0");
            return false;
        }
        if(productToAdd.quantity.length === 0  || productToAdd.quantity == "NaN"){
            alert("Falta la cantidad del producto a agregar");
            return false;
        }
        if(productToAdd.quantity <= 0){
            alert("La cantidad del producto no puede ser 0 o menor");
            return false;
        }
        if(productToAdd.product_id.length === 0 || productToAdd.product_id == "NaN"){
            alert("Falta el código de producto a agregar");
            return false;
        }
        if(productToAdd.description.length === 0){
            alert("Falta la descripción del producto a agregar");
            return false;
        }
        if(productToAdd.total_price.length === 0 || productToAdd.total_price == "NaN"){
            alert("Falta el total del producto a agregar");
            return false;
        }
        if(productToAdd.total_price < 0){
            alert("El precio total del producto no puede ser menor a 0");
            return false;
        }
        return true;
    }
    function validateBudget() { // OK
        if(budget.items.length == 0) {
            alert("Agregá items");
            return false;
        }
        return true;
    }
    function validateSale() { // Revisar
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
    function validateStock(){ // A MODIFICAR EN CASO DE QUE HAYA MÁS DE 1 DEPÓSITO / Pendiente de desarrollo
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

    // Updates
    // Items y productos
    function updateTotalItemToAdd() { 
        // console.log(productToAdd);
        productToAdd.total_price = (productToAdd.sale_price * productToAdd.quantity).toFixed(2);
        $("#productTotalPrice").empty();
        $("#productTotalPrice").val(productToAdd.total_price);
    }
    // Venta y cotización
    function updateSubtotalPrice() {
        budget.subtotalPrice = 0.00;
        budget.items.forEach(function(item) {
            budget.subtotalPrice += parseFloat(item.total_price);
        });
    }
    function updateDiscount() {
        var discountVal = 0.00;
        if($.trim($('#discount').val()).length == 0 || $.trim($('#discount').val()) < 0) {
            $('#discount').val(0);
            $('#discount').change();
        }
        if($('#discountType').val() == 1) {
            discountVal = parseFloat($.trim($('#discount').val())).toFixed(2);
        }
        else if($('#discountType').val() == 2) {
            discountVal = parseFloat($.trim($('#discount').val())).toFixed(2);
            discountVal = (discountVal/100)*budget.subtotalPrice;
        }
        // if(discountVal < 0) {
        //     $('#discount').val(0);
        //     $('#discount').change();
        // }
        budget.discount = discountVal;
    }
    function updateTax() {
        var taxVal = 0.00;
        if($.trim($('#tax').val()).length == 0 || $.trim($('#tax').val()) < 0) {
            $('#tax').val(0);
            $('#tax').change();
        }
        if($('#taxType').val() == 1) {
            taxVal = parseFloat($.trim($('#tax').val())).toFixed(2);
        }
        else if($('#taxType').val() == 2) {
            taxVal = parseFloat($.trim($('#tax').val())).toFixed(2);
            taxVal = (taxVal/100)*budget.subtotalPrice;
        }
        budget.tax = taxVal;
    }
    function updateShip() {
        budget.ship = 0.00;
        if($.trim($('#ship').val()).length == 0 || $.trim($('#ship').val()) < 0) {
            $('#ship').val(0);
            $('#ship').change();
        }
        budget.ship = parseFloat($.trim($('#ship').val())).toFixed(2);
    }
    function updateTotalPrice() {
        budget.totalPrice = parseFloat(budget.subtotalPrice) - parseFloat(budget.discount) + parseFloat(budget.tax) + parseFloat(budget.ship);
    }
    function updateBudget() { 
        updateSubtotalPrice();
        updateDiscount();
        updateTax();
        updateShip();
        updateTotalPrice();
    }

    // Renders y clears
    // Clientes
    function renderClientInDetail() { // OK
        $("#client").empty();
        $("#client").val(budget.client.name);
    }
    // Métodos de envío y de pago
    function renderShipMethodInDetail() { 
        $("#shipMethodDetail").empty();
        $("#shipMethodDetail").html(budget.shipMethod.title);
    }
    function renderPayMethodInDetail() { 
        $("#payMethodDetail").empty();
        $("#payMethodDetail").html(budget.payMethod.title);
    }
    // Items y productos
    function clearItemToAdd() { // OK 
        $("#productCostToPush").empty();
        $("#productPriceToPush").empty();
        $("#productQuantityToPush").empty();
        $("#productCurrentQuantity").empty();
        $("#productId").empty();
        $("#productDesc").empty();
        $("#productTotalPrice").empty();
    }
    function renderItemToAdd() { // OK
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
    // Cotización y venta
    function clearDetail() { 
        $("#quantityOfItems").empty();        
 
        $("#detailTableBody").empty();

        $("#subtotalPriceDetail").empty();

        $("#discountPercDetail").empty();
        $("#discountValDetail").empty();

        $("#taxPercDetail").empty();
        $("#taxValDetail").empty();

        $("#shipDetail").empty();

        $("#totalPriceDetail").empty();
    }
    function renderSubtotalInDetail() {
        $("#subtotalPriceDetail").empty();
        var subtotal     = "$" + parseFloat(budget.subtotalPrice).toFixed(2);
        $("#subtotalPriceDetail").html(subtotal);
    }
    function renderDiscountInDetail() {
        $("#discountValDetail").empty();
        $("#discountPercDetail").empty();
        var discountVal  = "-$" + parseFloat(budget.discount).toFixed(2);
        var discountPerc = "-%" + parseFloat(((budget.discount*100)/budget.subtotalPrice)).toFixed(2);
        if(budget.subtotalPrice == 0)
            discountPerc = "-%0.00";
        $("#discountValDetail").html(discountVal);
        $("#discountPercDetail").html(discountPerc);
    }
    function renderTaxInDetail() {
        $("#taxValDetail").empty();
        $("#taxPercDetail").empty();
        var taxVal  = "$" + parseFloat(budget.tax).toFixed(2);
        var taxPerc = "%" + parseFloat(((budget.tax*100)/budget.subtotalPrice)).toFixed(2);
        if(budget.subtotalPrice == 0)
            taxPerc = "%0.00";
        $("#taxValDetail").html(taxVal);
        $("#taxPercDetail").html(taxPerc);
    }
    function renderShipInDetail() {
        $("#shipDetail").empty();
        var shipVal    = "$" + parseFloat(budget.ship).toFixed(2);
        $("#shipDetail").html(shipVal);
    }
    function renderTotalInDetail() {
        $("#totalPriceDetail").empty();
        var total    = "$" + parseFloat(budget.totalPrice).toFixed(2);
        $("#totalPriceDetail").html(total);
    }
    function renderFooterDetail() { 
        renderSubtotalInDetail();
        renderDiscountInDetail();
        renderTaxInDetail();
        renderShipInDetail();
        renderTotalInDetail();
    }
    function renderDetail() {
        clearDetail();
        $("#quantityOfItems").append(budget.items.length);        
        budget.items.forEach(function(item, i) {
            $("#detailTableBody").append('<tr id=filaItem_' + i + '></tr>');
            $("#filaItem_"+ i).append('<th scope="row" class="col-md-1">' + i + '</th>');
            $("#filaItem_"+ i).append('<td class="col-md-2">' + item.description + '</td>');
            $("#filaItem_"+ i).append('<td class="col-md-2">' + item.cost_price + '</td>');
            $("#filaItem_"+ i).append('<td class="col-md-2">' + item.sale_price + '</td>');
            $("#filaItem_"+ i).append('<td class="col-md-1">' + item.quantity + '</td>');
            $("#filaItem_"+ i).append('<td class="col-md-2">' + item.total_price + '</td>');

            $("#filaItem_"+ i).append('<td class="col-md-2"><button type="button" class="btn btn-outline-info mr-1" id="edit' + i + '"><i class="bi bi-pencil-fill"></i></button><button type="button" class="btn btn-outline-danger" id="delete' + i + '"><i class="bi bi-trash3-fill"></i></button></td>');
            $("#edit"+ i).click(function() { // Pendiente de desarrollo
                renderDetail();
            });
            // $("#filaItem_"+ i).append('<td class="col-md-1"><button type="button" class="btn btn-outline-danger" id="delete' + i + '"><i class="bi bi-trash3-fill"></i></button></td>');
            $("#delete"+ i).click(function() {
                budget.items.splice(i, 1);
                updateBudget();
                renderDetail();
            });
        });
        updateBudget();
        renderFooterDetail();
    }

    // Changes y clicks
    $("#productCostToPush").change(function() { 
        var val = parseFloat($.trim($("#productCostToPush").val())).toFixed(2);
        $("#productCostToPush").val(val);
        productToAdd.cost_price = val;
        if(!validateItemToAdd()) {
            $("#productCostToPush").val(0);
            $("#productCostToPush").change();
        }    
    }); 
    $("#productPriceToPush").change(function() { 
        var val = parseFloat($.trim($("#productPriceToPush").val())).toFixed(2);
        $("#productPriceToPush").val(val);
        productToAdd.sale_price = val;
        if(!validateItemToAdd()) {
            $("#productPriceToPush").val(0);
            $("#productPriceToPush").change();
        }   
        updateTotalItemToAdd(); 
    }); 
    $("#productQuantityToPush").change(function() { 
        var val = parseFloat($.trim($("#productQuantityToPush").val())).toFixed(2);
        $("#productQuantityToPush").val(val);
        productToAdd.quantity = val;
        if(!validateItemToAdd()) {
            $("#productQuantityToPush").val(1);
            $("#productQuantityToPush").change();
        }    
        updateTotalItemToAdd();
    }); 
    $("#notes").change(function() { 
        budget.notes = $.trim($("#notes").val());
        console.log(budget);
    });
    $("#addItem").click(function() { 
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
    $("#addSale").click(function() { // Revisar estructura
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
    $("#discount").change(function() {
        updateDiscount();
        renderDetail();
    });
    $("#discountType").change(function() {
        updateDiscount();
        renderDetail();
    });
    $("#tax").change(function() {
        updateTax();
        renderDetail();
    });
    $("#taxType").change(function() {
        updateTax();
        renderDetail();
    });
    $("#ship").change(function() {
        updateShip();
        renderDetail();
    });

    // Init
    $('#clientToPush').select2();
    $('#productToPush').select2();
    getClients();
    getShipmentMethods();
    getPaymentMethods();
    getProducts();
    $("#discount").change();
    $("#tax").change();
    $("#ship").change();
});
