$(document).ready(function() {
    var budget      = new Object();
    var products    = new Object();
    var itemToAdd   = new Object();
    var itemToEdit  = new Object();
    var lastPos     = 0;
    var editModal = new bootstrap.Modal(document.getElementById('itemToEditModal'), {
        keyboard: true
    });

    /* Pasar getters a un archivo aparte para que se consuma desde los customizables de cada vista */
    // Getters
    function getShipmentMethods() { 
        $.get("./viewShipmentMethods", {getShipmentMethodsToSelect: true}, function(response) {
            response    = JSON.parse(response);
            var shipMethods = response.shipMethods;
            renderShipMethods(shipMethods); 
        });
    }   
    function getPaymentMethods() { 
        $.get("./viewPaymentMethods", {getPaymentMethodsToSelect: true}, function(response) {
            response   = JSON.parse(response);
            var payMethods = response.payMethods;
            renderPayMethods(payMethods); 
        });
    }    
    function getProducts() { 
        $.get("./viewProducts", {getProductsToSelect: true}, function(response) {
            response = JSON.parse(response);
            products = response.products; // Falta handle de errores
            renderProductsInSelect();
            $("#itemToAdd").change();
        });
    }
    function getBudget() { 
        var budgetNumber = $("#budgetNumber").val();
        $.get("./viewBudget", {getBudgetToNewVersion: true, number: budgetNumber}, function(response) {
            response = JSON.parse(response);
            console.log(response);
            budget = response.budget; // Falta handle de errores
            renderBudget();
            updateLastItemPosition();
        });
    }
    // Renders y clears
    function clearItemToAdd() { 
        $("#itemToAddCost").empty();
        $("#itemToAddPrice").empty();
        $("#itemToAddQuantity").empty();
        $("#itemToAddCurrentQuantity").empty();
        $("#itemToAddId").empty();
        $("#itemToAddDesc").empty();
        $("#itemToAddTotalPrice").empty();
    }
    function clearItemToEdit() { 
        $("#itemToEditCost").empty();
        $("#itemToEditPrice").empty();
        $("#itemToEditQuantity").empty();
        $("#itemToEditCurrentQuantity").empty();
        $("#itemToEditId").empty();
        $("#itemToEditDesc").empty();
        $("#itemToEditTotalPrice").empty();
    }
    function renderShipMethods(methods) { 
        $("#shipMethod").empty();
        methods.forEach(function(m) {
            $("#shipMethod").append('<option value=' + m['shipment_method_id'] + '>' + m['title'] + '</option>');
        });
    }
    function renderPayMethods(methods) { 
        $("#payMethod").empty();
        methods.forEach(function(m) {
            $("#payMethod").append('<option value=' + m['payment_method_id'] + '>' + m['title'] + '</option>');
        });
    }
    function renderBudgetInfo() { // Falta fix
        $("#userDetail").html(budget.info.user_name);
        $("#clientDetail").html(budget.info.client_name);
        $("#dateDetail").html(budget.info.start_date);
        $("#shipMethod option[value="+ budget.shipMethod.shipment_method_id +"]").attr("selected", true);
        $("#payMethod option[value="+ budget.payMethod.payment_method_id +"]").attr("selected", true);
        $("#desc").val(budget.notes);
        $("#subtotalDetail").html("$" + budget.subtotalPrice);
        $("#totalDetail").html("$" + budget.totalPrice);
        /* Falta fix acá, se establece el monto en $ de recargos y descuentos pero se fuerza el tipo, 
            faltaría tener labels que idiquen los montos en $ y % y por otro lado los inputs para cargar los valores */
        $("#discountType").val(1);
        $("#taxType").val(1);
        $("#discount").val(budget.discount);
        $("#tax").val(budget.tax);
        /* --- */
        $("#ship").val(budget.ship);
    }
    function renderBudgetItems() { 
        $("#itemsTableBody").empty();
        budget.items.forEach(function(item, i) {
            $("#itemsTableBody").append('<tr id=filaItem_' + i + '></tr>');
            $("#filaItem_"+ i).append('<th scope="row" class="col-md-1">' + item.position + '</th>');
            $("#filaItem_"+ i).append('<td class="col-md-1">' + item.product_id + '</td>');
            $("#filaItem_"+ i).append('<td class="col-md-3">' + item.description + '</td>');
            $("#filaItem_"+ i).append('<td class="col-md-2">$' + item.sale_price + '</td>');
            $("#filaItem_"+ i).append('<td class="col-md-1">x' + item.quantity + '</td>');
            $("#filaItem_"+ i).append('<td class="col-md-2">$' + item.total_price + '</td>');

            $("#filaItem_"+ i).append('<td class="col-md-2"><button type="button" class="btn btn-outline-info mr-1" id="edit' + i + '"><i class="bi bi-pencil-fill"></i></button><button type="button" class="btn btn-outline-danger" id="delete' + i + '"><i class="bi bi-trash3-fill"></i></button></td>');
            $("#edit"+ i).click(function() { 
                editModal.show();
                itemToEdit          = {...budget.items[i]};
                itemToEdit.index    = i;
                renderItemToEdit(); 
            });
            // $("#filaItem_"+ i).append('<td class="col-md-1"><button type="button" class="btn btn-outline-danger" id="delete' + i + '"><i class="bi bi-trash3-fill"></i></button></td>');
            $("#delete"+ i).click(function() {
                budget.items.splice(i, 1);
                updateLastItemPosition();
                updateBudget();
                renderBudget();
                console.log(budget);
            });
        });
    }
    function renderBudget() { 
        renderBudgetInfo();
        renderBudgetItems(); // REVISAR
    }
    function renderProductsInSelect() { 
        products.forEach(function(product) {
            $("#itemToAdd").append('<option value=' + product.product_id + '>' + product.description + '</option>');
        });
    }
    function renderItemToAdd() { 
        clearItemToAdd();
        $("#itemToAddCost").val(itemToAdd.cost_price);
        $("#itemToAddPrice").val(itemToAdd.sale_price); 
        $("#itemToAddQuantity").val(itemToAdd.quantity);
        $("#itemToAddCurrentQuantity").val(itemToAdd.stock_quantity); 
        $("#itemToAddId").val(itemToAdd.product_id);
        $("#itemToAddDesc").val(itemToAdd.description);
        $("#itemToAddTotalPrice").val(itemToAdd.total_price);
    }
    function renderItemToEdit() { 
        clearItemToEdit();
        $("#itemToEditIndex").val(itemToEdit.index); // Con este input me refiero al ítem a la hora de hacer click en editar
        $("#itemToEditCost").val(itemToEdit.cost_price);
        $("#itemToEditPrice").val(itemToEdit.sale_price); 
        $("#itemToEditQuantity").val(itemToEdit.quantity);
        $("#itemToEditCurrentQuantity").val(itemToEdit.stock_quantity); 
        $("#itemToEditId").val(itemToEdit.product_id);
        $("#itemToEditDesc").val(itemToEdit.description);
        $("#itemToEditTotalPrice").val(itemToEdit.total_price);
    }
    // Validaciones
    function validateItemToAdd() { // REVISAR TOTALES Y POSITION
        // if(itemToAdd.position.length === 0 || itemToAdd.position == "NaN") { // Revisar si se quieren evitar costos = 0
        //     alert("Falta la posición del producto a agregar");
        //     return false;
        // }
        // if(itemToAdd.position < 0) { 
        //     alert("La posición del producto no puede ser menor a 0");
        //     return false;
        // }
        if(itemToAdd.cost_price.length === 0 || itemToAdd.cost_price == "NaN") { // Revisar si se quieren evitar costos = 0
            alert("Falta el costo del producto a agregar");
            return false;
        }
        if(itemToAdd.cost_price < 0) { 
            alert("El costo del producto no puede ser menor a 0");
            return false;
        }
        if(itemToAdd.sale_price.length === 0 || itemToAdd.sale_price == "NaN"){
            alert("Falta el precio del producto a agregar");
            return false;
        }
        if(itemToAdd.sale_price < 0){
            alert("El precio del producto no puede ser menor a 0");
            return false;
        }
        if(itemToAdd.quantity.length === 0  || itemToAdd.quantity == "NaN"){
            alert("Falta la cantidad del producto a agregar");
            return false;
        }
        if(itemToAdd.quantity <= 0){
            alert("La cantidad del producto no puede ser 0 o menor");
            return false;
        }
        if(itemToAdd.product_id.length === 0 || itemToAdd.product_id == "NaN"){
            alert("Falta el código de producto a agregar");
            return false;
        }
        if(itemToAdd.description.length === 0){
            alert("Falta la descripción del producto a agregar");
            return false;
        }
        // if(itemToAdd.total_price.length === 0 || itemToAdd.total_price == "NaN"){
        //     alert("Falta el total del producto a agregar");
        //     return false;
        // }
        // if(itemToAdd.total_price < 0){
        //     alert("El precio total del producto no puede ser menor a 0");
        //     return false;
        // }
        return true;
    }
    function validateItemToEdit() { 
        if(itemToEdit.position.length === 0 || itemToEdit.position == "NaN") { // Revisar si se quieren evitar costos = 0
            alert("Falta la posición del producto a editar");
            return false;
        }
        if(itemToEdit.position < 0) { 
            alert("La posición del producto no puede ser menor a 0");
            return false;
        }
        if(itemToEdit.cost_price.length === 0 || itemToEdit.cost_price == "NaN") { // Revisar si se quieren evitar costos = 0
            alert("Falta el costo del producto a editar");
            return false;
        }
        if(itemToEdit.cost_price < 0) { 
            alert("El costo del producto no puede ser menor a 0");
            return false;
        }
        if(itemToEdit.sale_price.length === 0 || itemToEdit.sale_price == "NaN") {
            alert("Falta el precio del producto a editar");
            return false;
        }
        if(itemToEdit.sale_price < 0) {
            alert("El precio del producto no puede ser menor a 0");
            return false;
        }
        if(itemToEdit.quantity.length === 0  || itemToEdit.quantity == "NaN") {
            alert("Falta la cantidad del producto a editar");
            return false;
        }
        if(itemToEdit.quantity <= 0){
            alert("La cantidad del producto no puede ser 0 o menor");
            return false;
        }
        if(itemToEdit.product_id.length === 0 || itemToEdit.product_id == "NaN"){
            alert("Falta el código de producto a editar");
            return false;
        }
        if(itemToEdit.description.length === 0){
            alert("Falta la descripción del producto a editar");
            return false;
        }
        return true;
    }
    function validateBudget() { 
        if(budget.items.length == 0) { // OK
            alert("Agregá items");
            return false;
        }
        return true;
    }
    // Updates
    function updateTotalItemToAdd() { 
        itemToAdd.total_price = (itemToAdd.sale_price * itemToAdd.quantity).toFixed(2);
        $("#itemToAddTotalPrice").empty();
        $("#itemToAddTotalPrice").val(itemToAdd.total_price);
    }
    function updateTotalItemToEdit() { 
        itemToEdit.total_price = (itemToEdit.sale_price * itemToEdit.quantity).toFixed(2);
        $("#itemToEditTotalPrice").empty();
        $("#itemToEditTotalPrice").val(itemToEdit.total_price);
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
        budget.discount = discountVal;
    }
    function updateShip() { 
        budget.ship = 0.00;
        if($.trim($('#ship').val()).length == 0 || $.trim($('#ship').val()) < 0) {
            $('#ship').val(0);
            $('#ship').change();
        }
        budget.ship = parseFloat($.trim($('#ship').val())).toFixed(2);
    }
    function updateSubtotalPrice() { 
        budget.subtotalPrice = 0.00;
        budget.items.forEach(function(item) {
            budget.subtotalPrice += parseFloat(item.total_price);
        });

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
    function updateLastItemPosition() { 
        lastPos = 0;
        budget.items.forEach(function(item, i) {
            if(item.position > lastPos)
                lastPos = item.position;
        });
    }
    // Eventos
    $("#shipMethod").change(function() { 
        budget.shipMethod.shipment_method_id = $(this).val();
    });
    $("#payMethod").change(function() { 
        budget.payMethod.payment_method_id = $(this).val();
    });
    $("#editItem").click(function() { 
        if(validateItemToEdit()) { 
            budget.items[itemToEdit.index] = {...itemToEdit};
            updateBudget(); 
            renderBudget();
            editModal.hide();
            // updateLastItemPosition();
        }
    });
    $("#itemToEditCost").change(function() { 
        var val = parseFloat($.trim($("#itemToEditCost").val())).toFixed(2);
        $("#itemToEditCost").val(val);
        itemToEdit.cost_price = val;
        if(!validateItemToEdit()) {
            $("#itemToEditCost").val(0);
            $("#itemToEditCost").change();
        }    
    }); 
    $("#itemToEditPrice").change(function() { 
        var val = parseFloat($.trim($("#itemToEditPrice").val())).toFixed(2);
        $("#itemToEditPrice").val(val);
        itemToEdit.sale_price = val;
        if(!validateItemToEdit()) {
            $("#itemToEditPrice").val(0);
            $("#itemToEditPrice").change();
        }   
        updateTotalItemToEdit();  
    }); 
    $("#itemToEditQuantity").change(function() { K
        var val = parseFloat($.trim($("#itemToEditQuantity").val())).toFixed(2);
        $("#itemToEditQuantity").val(val);
        itemToEdit.quantity = val;
        if(!validateItemToEdit()) {
            $("#itemToEditQuantity").val(1);
            $("#itemToEditQuantity").change();
        }    
        updateTotalItemToEdit();
    }); 
    $("#itemToAdd").change(function() { 
        var id = this.value;
        products.forEach(function(product) { 
            if(product.product_id === id) {                
                itemToAdd               = product;
                itemToAdd.quantity      = 1;
                itemToAdd.total_price   = itemToAdd.sale_price;    
                console.log(itemToAdd);    
            }
        });
        renderItemToAdd(); 
    });
    $("#addItem").click(function() { 
        itemToAdd.position = parseInt(lastPos)+1;
        if(validateItemToAdd()) { 
            budget.items.push({...itemToAdd});
            updateBudget(); 
            renderBudget();
            updateLastItemPosition();
            console.log(budget);
        }
    });
    $("#itemToAddCost").change(function() { 
        var val = parseFloat($.trim($("#itemToAddCost").val())).toFixed(2);
        $("#itemToAddCost").val(val);
        itemToAdd.cost_price = val;
        if(!validateItemToAdd()) {
            $("#itemToAddCost").val(0);
            $("#itemToAddCost").change();
        }    
    }); 
    $("#itemToAddPrice").change(function() { 
        var val = parseFloat($.trim($("#itemToAddPrice").val())).toFixed(2);
        $("#itemToAddPrice").val(val);
        itemToAdd.sale_price = val;
        if(!validateItemToAdd()) {
            $("#itemToAddPrice").val(0);
            $("#itemToAddPrice").change();
        }   
        updateTotalItemToAdd(); 
    }); 
    $("#itemToAddQuantity").change(function() { 
        var val = parseFloat($.trim($("#itemToAddQuantity").val())).toFixed(2);
        $("#itemToAddQuantity").val(val);
        itemToAdd.quantity = val;
        if(!validateItemToAdd()) {
            $("#itemToAddQuantity").val(1);
            $("#itemToAddQuantity").change();
        }    
        updateTotalItemToAdd();
    }); 
    $("#desc").change(function() { 
        budget.notes = $.trim($("#desc").val());
        console.log(budget);
    });
    $("#discount").change(function() { 
        updateBudget();
        renderBudget();
    });
    $("#discountType").change(function() { 
        updateBudget();
        renderBudget();
    });
    $("#tax").change(function() { 
        updateBudget();
        renderBudget();
    });
    $("#taxType").change(function() { 
        updateBudget();
        renderBudget();
    });
    $("#ship").change(function() { 
        updateBudget();
        renderBudget();
    });
    $("#newVersion").click(function() { 
        if(validateBudget()) {
            var jsonBudget = JSON.stringify(budget);
            $.post("./newBudget", {newBudgetVersion: true, budget: jsonBudget}, function(response){
                response = JSON.parse(response);
                console.log(response);
                if(response.state === 0) {
                    alert(response.errorMsg)
                } else if (response.state === 1) {
                    alert(response.successMsg)
                    location.href ='./viewBudget-' + budget.info.budget_number;
                }
            });
        }else {
            alert("Error al versionar la cotización, intentá nuevamente");
        }
    });    
    // Init
    $("#itemToAdd").select2({
        dropdownParent: $('#itemToAddModal .modal-body'),
        theme: 'classic',
    });
    getShipmentMethods();
    getPaymentMethods();
    getBudget();
    getProducts();










});
