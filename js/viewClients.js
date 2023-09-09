$(document).ready(function() {
    var clients    = new Object(),
        registers  = 0, 
        filters    = new Object(),
        orders     = new Object();
    // Eventos
    $("th input").on("keyup", function() {
        getFilters();
        getClients();
    });   
    $("th input").on("change", function() {
        getFilters();
        getClients();
    });     
    $("th select").on("change", function() {
        getFilters();
        getClients();
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
        getClients();
    });    
    $("#limitLength").on("change", function() {
        $('#limitOffset').val(0); // Cuando cambia la cantidad de registros a ver, se setea el offset en 0
        getClients();
    });
    // Funciones 
    function getFilters() {
        filters.number  = $.trim($("#numberFilter").val());
        filters.name    = $.trim($("#nameFilter").val());
        filters.dni     = $.trim($("#dniFilter").val());
        filters.email   = $.trim($("#emailFilter").val());
    }
    function getOrders() {
        orders.number  = $.trim($("#numberOrderr").val());
        orders.name    = $.trim($("#nameOrder").val());
        orders.dni     = $.trim($("#dniOrder").val());
        orders.email   = $.trim($("#emailOrder").val());
    }
    function getClients() { 
        jsonFilters         = JSON.stringify(filters);
        jsonOrders          = JSON.stringify(orders);
        var limitLength     = JSON.stringify($("#limitLength").val()), 
            limitOffset     = JSON.stringify($("#limitOffset").val());
        $.get("./viewClients", {getToDashboard: true, filters: jsonFilters, orders: jsonOrders, limitOffset: limitOffset, limitLength: limitLength}, function(response) {
            response  = JSON.parse(response);
            clients   = response.data.clients;
            registers = response.data.registers;
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
        clients.forEach(function(client) {
            $("#tableBody").append('<tr id="row_' + client.client_id + '"></tr>');
            $("#row_"+ client.client_id).append('<th scope="row">' + client.client_id + '</th>');
            $("#row_"+ client.client_id).append('<td>' + client.name + '</td>');
            dniStr   = "-";
            emailStr = "-";
            if(client.dni)
                dniStr = client.dni;
            if(client.email)
                emailStr = client.email;
            $("#row_"+ client.client_id).append('<td>' + dniStr + '</td>');
            $("#row_"+ client.client_id).append('<td>' + emailStr + '</td>');
            $("#row_"+ client.client_id).append('<td>' + 
                '<i id="view-' + client.client_id + '" class="bi bi-search ms-auto text-primary"></i>' + 
            '</td>');
            $("#view-"+ client.client_id).click(function(event) {
                event.preventDefault();
                viewDetail(client.client_id);
            });
        });
        $("#registers").html(registers);
        renderPagination();
    }
    function clearTable() {     
        $("#tableBody").empty();
    }
    function viewDetail(number) {
        window.location = "./viewClient-" + number;
    }
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
            getClients();
        }
        if(length <= 0) {
            $('#limitLength option[value="20"]').attr('selected', true); // REVISAR SI ACÁ NO HACE getClients por el change del select
            getClients();
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
            getClients();
        });  
    }
    // Init
    getFilters();
    getOrders();
    getClients();
});
