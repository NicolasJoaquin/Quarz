$(document).ready(function() {
    var sales = new Object(),
        filters   = new Object();
        orders   = new Object();
        shipStates = new Object();
        payStates  = new Object();

    // Eventos
    // $("th input").on("keyup", function() {
    //     getFilters();
    //     getSales();
    // });   
    $("th input").on("change", function() {
        getFilters();
        getSales();
    });     
    $("th select").on("change", function() {
        getFilters();
        getSales();
    });     
    $(".orders th").on("click", function() {
        // var values = ["desc", "asc"];
        var input = $(this).find('input'),
            icon = $(this).find('i'),
            descIconClass = "bi-sort-down",
            ascIconClass = "bi-sort-up-alt"; 

        if(input.val() == "desc") {
            input.val("asc");
            icon.removeClass(descIconClass);
            icon.addClass(ascIconClass);
        }
        else if(input.val() == "asc") {
            input.val("desc");
            icon.removeClass(ascIconClass);
            icon.addClass(descIconClass);
        }
        getOrders();
        getSales();
    });    

    // Funciones 
    // Filtros y órdenes
    function getFilters() {
        filters.saleNumber = $.trim($("#saleNumberFilter").val());
        filters.user       = $.trim($("#userFilter").val());
        filters.client     = $.trim($("#clientFilter").val());
        filters.budget     = $.trim($("#budgetFilter").val());
        filters.fromDate   = $.trim($("#fromDateFilter").val());
        filters.toDate     = $.trim($("#toDateFilter").val());
        filters.shipment   = $.trim($("#shipmentFilter").val());
        filters.payment    = $.trim($("#paymentFilter").val());
        console.log(filters);

    }
    function getOrders() {
        orders.sale     = $.trim($("#saleOrder").val());
        orders.user     = $.trim($("#userOrder").val());
        orders.client   = $.trim($("#clientOrder").val());
        orders.budget   = $.trim($("#budgetOrder").val());
        orders.date     = $.trim($("#dateOrder").val());
        orders.shipment = $.trim($("#shipmentOrder").val());
        orders.payment  = $.trim($("#paymentOrder").val());
        console.log(orders);
    }

    // Datepicker
    function setDate() {
        $("#fromDateFilter").datepicker($.datepicker.regional[ "es" ]);
        $("#toDateFilter").datepicker();
        var dateFormat = "dd/mm/yy",
            from = $("#fromDateFilter").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                language: "es"
            }).on("change", function() {
                to.datepicker( "option", "minDate", getDate(this) );
            }),
            to = $("#toDateFilter").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                language: "es"
            }).on( "change", function() {
                from.datepicker( "option", "maxDate", getDate(this) );
            });
    
        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch(error) {
                date = null;
            }
    
            return date;
        }
    }

    // Ventas
    function getSales() { 
        jsonFilters = JSON.stringify(filters);
        jsonOrders  = JSON.stringify(orders);
        $.get("./viewSales", {getSalesToDashboard: true, filters: jsonFilters, orders: jsonOrders}, function(response) {
            response = JSON.parse(response);
            sales = response.sales;
            if(response.state == 1) {
                renderTable();
                console.log(response.successMsg); 
            }
            else if(response.state == 0) {
                alert(response.errorMsg); 
                console.log(response.errorMsg); 
            }
        });
    }
    function renderTable() { 
        clearTable();
        sales.forEach(function(sale) {
            $("#salesTableBody").append('<tr id="saleRow_' + sale.sale_id + '"></tr>');
            $("#saleRow_"+ sale.sale_id).append('<th class="col-md-1" scope="row">' + sale.sale_id + '</th>');
            $("#saleRow_"+ sale.sale_id).append('<td class="col-md-2">' + sale.user_name + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td class="col-md-2">' + sale.client_name + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td class="col-md-1">' + sale.budget_id + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td class="col-md-2">' + sale.start_date + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td class="col-md-2">' + sale.ship_name + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td class="col-md-2">' + sale.pay_name + '</td>');

            $("#saleRow_"+ sale.sale_id).click(function() { // Revisar
                viewSale(sale.sale_id);
            });
        });
    }
    function clearTable() {     
        $("#salesTableBody").empty();
    }
    function viewSale(saleId) {
        window.location = "./viewSale-" + saleId;
        // alert("redirección a vista ampliada # " + prodId);
    }  

    // Estados de pago y envío
    function getShipmentStates() { 
        $.get("./controllers/viewShipmentStates.php", {getShipmentStatesToSelect: true}, function(response) {
            response = JSON.parse(response);
            if(response.state == 1) {
                shipStates = response.shipStates;
                renderShipSelect();
                console.log(response.successMsg); 
            }
            else if(response.state == 0) {
                alert(response.errorMsg); 
                console.log(response.errorMsg); 
            }
        });
    }
    function getPaymentStates() { 
        $.get("./controllers/viewPaymentStates.php", {getPaymentStatesToSelect: true}, function(response) {
            response = JSON.parse(response);
            if(response.state == 1) {
                payStates = response.payStates;
                renderPaySelect();
                console.log(response.successMsg); 
            }
            else if(response.state == 0) {
                alert(response.errorMsg); 
                console.log(response.errorMsg); 
            }
        });
    }
    function renderShipSelect() { // Falta fix
        shipStates.forEach(function(state) {
            $("#shipmentFilter").append('<option value="'+ state.shipment_state_id +'">'+ state.title +'</option>');
        });
    }    
    function renderPaySelect() { // Falta fix
        payStates.forEach(function(state) {
            $("#paymentFilter").append('<option value="'+ state.payment_state_id +'">'+ state.title +'</option>');
        });
    }    

    // Init
    setDate();
    getFilters();
    getOrders();
    getSales();
    getShipmentStates();
    getPaymentStates();
});
