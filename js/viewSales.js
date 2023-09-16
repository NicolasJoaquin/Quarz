$(document).ready(function() {
    var sales      = new Object(),
        total      = 0, 
        registers  = 0, 
        filters    = new Object(),
        orders     = new Object(),
        shipStates = new Object(),
        payStates  = new Object();

    // Eventos
    $("th input").on("keyup", function() {
        getFilters();
        getSales();
    });   
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
    $("#limitLength").on("change", function() {
        $('#limitOffset').val(0); // Cuando cambia la cantidad de registros a ver, se setea el offset en 0
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
        jsonFilters         = JSON.stringify(filters);
        jsonOrders          = JSON.stringify(orders);
        var limitLength     = JSON.stringify($("#limitLength").val()), 
            limitOffset     = JSON.stringify($("#limitOffset").val());
        $.get("./viewSales", {getSalesToDashboard: true, filters: jsonFilters, orders: jsonOrders, limitOffset: limitOffset, limitLength: limitLength}, function(response) {
            response = JSON.parse(response);
            sales         = response.sales.sales;
            total         = response.sales.total;
            registers     = response.sales.registers;
            lastPayState  = response.sales.lastPayState;
            lastShipState = response.sales.lastShipState;

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
            $("#saleRow_"+ sale.sale_id).append('<th scope="row">' + sale.sale_id + '</th>');
            $("#saleRow_"+ sale.sale_id).append('<td>' + sale.user_name + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td><a href="./viewClient-' + sale.client_id + '">' + sale.client_name + '</a></td>'); 
            var budNumber = "-";
            if(sale.budget_number) {
                var bn = sale.budget_number.toString(),
                    bv = sale.budget_version.toString();
                bn = bn.padStart(4, '0');
                bv = bv.padStart(2, '0');
                budNumber = "<a href='./viewBudget-" + sale.budget_number + "'>#" + bn + " > v" + bv + "</a>";
            }
            $("#saleRow_"+ sale.sale_id).append('<td>' + budNumber + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td>' + sale.start_date + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td>' + sale.ship_name + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td>' + sale.pay_name + '</td>');
            $("#saleRow_"+ sale.sale_id).append('<td> $' + sale.total + '</td>');
            // Popovers
            var nextPayIcon         = "<i class='bi bi-chevron-double-right'></i>",
                lastPayIcon         = "<i class='bi bi-check2-circle'></i>",
                nextPayStateButton  = "<a role='button' aria-action='nextPaymentState' aria-value='" + sale.sale_id + "' class='btn btn-sm btn-primary btn-popover'>" + nextPayIcon + " Pasar a <strong>" + sale.next_payment_state + "</strong></a>",
                lastPayStateButton  = "<a role='button' aria-action='lastPaymentState' aria-value='" + sale.sale_id + "' class='btn btn-sm btn-success btn-popover'>" + lastPayIcon + " Pasar a <strong>" + lastPayState + "</strong></a>",
                payStateButtons     = nextPayStateButton + lastPayStateButton,

                nextShipIcon         = "<i class='bi bi-chevron-double-right'></i>",
                lastShipIcon         = "<i class='bi bi-bag-check'></i>",
                nextShipStateButton  = "<a role='button' aria-action='nextShipmentState' aria-value='" + sale.sale_id + "' class='btn btn-sm btn-primary btn-popover'>" + nextShipIcon + " Pasar a <strong>" + sale.next_shipment_state + "</strong></a>",
                lastShipStateButton  = "<a role='button' aria-action='lastShipmentState' aria-value='" + sale.sale_id + "' class='btn btn-sm btn-success btn-popover'>" + lastShipIcon + " Pasar a <strong>" + lastShipState + "</strong></a>",
                ShipStateButtons     = nextShipStateButton + lastShipStateButton;

            if(sale.next_shipment_state == lastShipState)
                ShipStateButtons = lastShipStateButton;
            if(sale.next_payment_state == lastPayState)
                payStateButtons = lastPayStateButton;

            if(sale.ship_name == lastShipState)
                ShipStateButtons = "<em>No hay acciones disponibles</em>";

            if(sale.pay_name == lastPayState)
                payStateButtons = "<em>No hay acciones disponibles</em>";

            var payTitle            = "Gestionar pago",
                shipTitle           = "Gestionar envío",
                payPopoverContent   = payStateButtons,
                shipPopoverContent  = ShipStateButtons;

            $("#saleRow_"+ sale.sale_id).append('<td>' + 
                '<i id="view-' + sale.sale_id + '" class="bi bi-search ms-auto text-primary"></i>' + 
                // Ship management
                '<a role="button" id="shipManage-' + sale.sale_id 
                + '" data-bs-toggle="popover" title="' + shipTitle + '" data-bs-placement="left" data-bs-html="true" data-bs-content="' + shipPopoverContent 
                + '"><i class="bi bi-bus-front ms-auto text-primary"></i></a>' + 
                // Pay management
                '<a role="button" id="payManage-' + sale.sale_id 
                + '" data-bs-toggle="popover" title="' + payTitle + '" data-bs-placement="left" data-bs-html="true" data-bs-content="' + payPopoverContent 
                + '"><i class="bi bi-cash-coin ms-auto text-primary"></i></a>' + 
            '</td>');

            $("#view-"+ sale.sale_id).click(function(event) { // Revisar
                event.preventDefault();
                viewSale(sale.sale_id);
            });
        });
        /* Habilitar popovers */
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        }); 
        if(total == null)
            total = 0;
        $("#total").html("$" + total);
        $("#registers").html(registers);
        renderPagination();
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
            getSales();
        }
        if(length <= 0) {
            $('#limitLength option[value="20"]').attr('selected', true); // REVISAR SI ACÁ NO HACE getSales por el change del select
            getSales();
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
            getSales();
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

    // Eventos
    // Init
    setDate();
    getFilters();
    getOrders();
    getSales();
    getShipmentStates();
    getPaymentStates();
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
                // getSales();
                location.href = "";
            }
        });
    });
});
