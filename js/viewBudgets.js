$(document).ready(function() {
    var budgets    = new Object(),
        subtotal   = 0, 
        total      = 0, 
        registers  = 0, 
        filters    = new Object(),
        orders     = new Object(),
        shipMethods = new Object(),
        payMethods  = new Object();

    // Eventos
    $("th input").on("keyup", function() {
        getFilters();
        getBudgets();
    });   
    $("th input").on("change", function() {
        getFilters();
        getBudgets();
    });     
    $("th select").on("change", function() {
        getFilters();
        getBudgets();
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
        getBudgets();
    });    
    $("#limitLength").on("change", function() {
        $('#limitOffset').val(0); // Cuando cambia la cantidad de registros a ver, se setea el offset en 0
        getBudgets();
    });
    // Funciones 
    // Filtros y órdenes
    function getFilters() {
        filters.number = $.trim($("#numberFilter").val());
        filters.user       = $.trim($("#userFilter").val());
        filters.client     = $.trim($("#clientFilter").val());
        filters.fromDate   = $.trim($("#fromDateFilter").val());
        filters.toDate     = $.trim($("#toDateFilter").val());
        filters.shipment   = $.trim($("#shipmentFilter").val());
        filters.payment    = $.trim($("#paymentFilter").val());
        /* Faltan subtotal y total */
        console.log(filters);
    }
    function getOrders() {
        orders.number   = $.trim($("#numberOrder").val());
        orders.user     = $.trim($("#userOrder").val());
        orders.client   = $.trim($("#clientOrder").val());
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
    // Cotizaciones
    function getBudgets() { 
        jsonFilters         = JSON.stringify(filters);
        jsonOrders          = JSON.stringify(orders);
        var limitLength     = JSON.stringify($("#limitLength").val()), 
            limitOffset     = JSON.stringify($("#limitOffset").val());
        $.get("./viewBudgets", {getToDashboard: true, filters: jsonFilters, orders: jsonOrders, limitOffset: limitOffset, limitLength: limitLength}, function(response) {
            response = JSON.parse(response);
            budgets        = response.data.budgets;
            subtotal       = response.data.subtotal;
            total          = response.data.total;
            registers      = response.data.registers;
            lastPayMethod  = response.data.lastPayMethod;
            lastShipMethod = response.data.lastShipMethod;
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
        budgets.forEach(function(budget) {
            $("#tableBody").append('<tr id="row_' + budget.budget_number + '"></tr>');
            $("#row_"+ budget.budget_number).append('<th scope="row">' + budget.budget_number + '</th>');
            $("#row_"+ budget.budget_number).append('<td>' + budget.user_name + '</td>');
            $("#row_"+ budget.budget_number).append('<td>' + budget.client_name + '</td>');
            $("#row_"+ budget.budget_number).append('<td>' + budget.start_date + '</td>');
            $("#row_"+ budget.budget_number).append('<td>' + budget.ship_name + '</td>');
            $("#row_"+ budget.budget_number).append('<td>' + budget.pay_name + '</td>');
            $("#row_"+ budget.budget_number).append('<td> $' + budget.subtotal + '</td>');
            $("#row_"+ budget.budget_number).append('<td> $' + budget.total + '</td>');
            $("#row_"+ budget.budget_number).append('<td>' + 
                '<i id="view-' + budget.budget_number + '" class="bi bi-search ms-auto text-primary"></i>' + 
            '</td>');
            $("#view-"+ budget.budget_number).click(function(event) { // Revisar
                event.preventDefault();
                viewBudget(budget.budget_number);
            });
        });
        if(subtotal == null) 
            subtotal = 0;
        if(total == null) 
            total = 0;
        $("#subtotal").html("$" + subtotal);
        $("#total").html("$" + total);
        $("#registers").html(registers);
        renderPagination();
    }
    function clearTable() {     
        $("#tableBody").empty();
    }
    function viewBudget(number) {
        window.location = "./viewBudget-" + number;
    }
    function getShipmentMethods() { //ACA
        $.get("./controllers/viewShipmentMethods.php", {getShipmentMethodsToSelect: true}, function(response) {
            response = JSON.parse(response);
            console.log(response.msg); 
            if(response.state == 1) {
                shipMethods = response.shipMethods;
                renderShipSelect();
            }
            else if(response.state == 0) {
                alert(response.msg); 
            }
        });
    }
    function getPaymentMethods() { 
        $.get("./controllers/viewPaymentMethods.php", {getPaymentMethodsToSelect: true}, function(response) {
            response = JSON.parse(response);
            console.log(response.msg); 
            if(response.state == 1) {
                payMethods = response.payMethods;
                renderPaySelect();
            }
            else if(response.state == 0) {
                alert(response.msg); 
            }
        });
    }
    function renderShipSelect() { 
        shipMethods.forEach(function(method) {
            $("#shipmentFilter").append('<option value="'+ method.shipment_method_id +'">'+ method.title +'</option>');
        });
    }    
    function renderPaySelect() { 
        payMethods.forEach(function(method) {
            $("#paymentFilter").append('<option value="'+ method.payment_method_id +'">'+ method.title +'</option>');
        });
    }    
    // Renders y Clears
    function clearPagination() {
        $("ul.pagination").empty();
    }
    function renderPagination() {
        var offset          = $('#limitOffset').val(),
            length          = $('#limitLength').val(),
            paginationItems = 1,
            actualItem      = 1;
        if(offset < 0 || offset > registers) { 
            $('#limitOffset').val(0);
            getBudgets();
        }
        if(length <= 0) {
            $('#limitLength option[value="20"]').attr('selected', true); // REVISAR SI ACÁ NO HACE getBudgets por el change del select
            getBudgets();
        }
        paginationItems = (registers/length);
        var rounded = Math.round(paginationItems);
        if(rounded < paginationItems)
            paginationItems = rounded+1;
        else
            paginationItems = rounded;

        if(offset < length) {
            actualItem = 1;
            $('#limitOffset').val(0);
        }
        else if (offset == length) {
            actualItem = 2;
            $('#limitOffset').val(length);
        }
        else if(offset > length) {
            actualItem = (offset/length);
            actualItem = Math.round(actualItem);
            $('#limitOffset').val(actualItem*length);
            actualItem++;
        }

        offset = $('#limitOffset').val();
        clearPagination();

        for (let index = 1; index <= paginationItems; index++) {
            if(index == 1 && actualItem != 1) {
                $('ul.pagination').append(
                    '<li class="page-item">' +
                        '<a class="page-link" aria-label="Previous">' +
                            '<span aria-hidden="true">&laquo;</span>' +
                        '</a>' +
                        '<input type="hidden" class="limitItem" value="'+ parseInt(offset-length)  +'">' +
                    '</li>'
                );
            }
            var activeClass = "";
            if(index == actualItem)
                activeClass = " active";
            $('ul.pagination').append(
                '<li class="page-item' + activeClass + '">' +
                    '<a class="page-link">' +
                        index +
                    '</a>' +
                    '<input type="hidden" class="limitItem" value="'+ parseInt(index*length-length) +'">' +
                '</li>'
            );
            if(index == paginationItems && actualItem != index) {
                $('ul.pagination').append(
                    '<li class="page-item" id="next">' +
                        '<a class="page-link" aria-label="Next">' +
                            '<span aria-hidden="true">&raquo;</span>' +
                        '</a>' +
                        '<input type="hidden" class="limitItem" value="'+ parseInt(offset+length)  +'">' +
                    '</li>'
                );
            }
        }
        $("li.page-item").click(function(event) { 
            var offset = $(this).find('input[type="hidden"]').val();
            $('#limitOffset').val(offset);
            getBudgets();
        });  
    }
    function renderShipSelect() { // Falta fix
        shipMethods.forEach(function(method) {
            $("#shipmentFilter").append('<option value="'+ method.shipment_method_id +'">'+ method.title +'</option>');
        });
    }    
    function renderPaySelect() { // Falta fix
        payMethods.forEach(function(method) {
            $("#paymentFilter").append('<option value="'+ method.payment_method_id +'">'+ method.title +'</option>');
        });
    }    

    // Eventos
    // Init
    setDate();
    getFilters();
    getOrders();
    getBudgets();
    getShipmentMethods();
    getPaymentMethods();
    $(document).on('click', '.btn-popover', function() {
        var sale_id = $(this).attr("aria-value"),
            action  = $(this).attr("aria-action");
        
        $.post("./newSaleBudget", {changeSaleState: true, action: action, sale_id: sale_id}, function(response) {
            console.log(response);
            response = JSON.parse(response);
            if(response.state === 0) {
                alert(response.errorMsg)
            } else if (response.state === 1) {
                alert(response.successMsg)
                // getBudgets();
                location.href = "";
            }
        });
    });
});
