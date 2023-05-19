<?php 
// html/FormNewSale.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Nueva Venta");
$header->render();

?>
<div id="make">
    <h3>Armado de pedido</h3>
    <label for="clients">Seleccione un cliente: </label>
    <select name="clients" id="clients">

    </select>

    <label for="itemToAdd">Seleccione un producto: </label>
    <select name="itemToAdd" id="itemToAdd">

    </select>

    <input type="button" name="addItem" id="addItem" value="Agregar Item">

    <table id="itemToAddTable">    
        <thead> <tr><th>Cod.</th> <th>Descripción</th> <th>Costo</th> <th>Precio de Venta</th> <th>Cantidad</th> <th>Total</th></tr> </thead>
        <tbody id="itemToAddTableBody">
            <tr>
                <td id="prodId"></td> 
                <td id="prodDesc"></td> 
                <td><input type="number" id="prodCost"></td> 
                <td><input type="number" id="prodPrice"></td> 
                <td><input type="number" id="prodQuantity"></td> 
                <td id="prodTotalPrice"></td>
            </tr>
        </tbody>
    </table>
</div>

<div id="detail">
    <div id="saleDetailHeader">
        <h3>Detalle de pedido</h3> <input type="number" readonly id="saleDetailClientId"> <input type="text" readonly id="saleDetailClientName">
        <button id="submitSale">Alta de pedido</button>
        <button id="clearSaleDetail">Limpiar detalle de pedido</button>
    </div>

    <div id="saleDetail">
        <table id="saleDetailTable">    
            <thead> <tr><th>Pos.</th> <th>Cod.</th> <th>Descripción</th> <th>Costo</th> <th>Precio de Venta</th> <th>Cantidad</th> <th>Total</th> <th>Borrar Item</th></tr> </thead>
            <tbody id="saleDetailTableBody">
                
            </tbody>
            <tfoot> <tr> <td colspan=6>TOTAL: </td> <td colspan=2 id="saleTotalPrice"></td> </tr> </tfoot>
        </table>
    </div>
    

    
    <div id="saleDetailFooter">
        <div id="saleDescription">
            <textarea id="saleDesc" placeholder="Agregue una nota para este pedido si lo desea" maxlength=255></textarea>
        </div>
    </div>
</div>

<script>
$(document).ready(function (){
    function locate(url){
        $(location).attr('href',url);
    }

    // VER IMPLEMENTACIÓN DE LISTA DE PRECIOS
    var list = new Object();
    list.saleFactor = 1.45;

    var saleItems = new Array();

    function validateStock(){ // A MODIFICAR EN CASO DE QUE HAYA MÁS DE 1 DEPÓSITO
        var ret = true;
        saleItems.forEach(function(saleItem, i){
            var saleItemTotalQuantity = 0;
            saleItems.forEach(function(saleItemTest, j){
                if(saleItem.prodId == saleItemTest.prodId) saleItemTotalQuantity += parseInt(saleItemTest.prodQuantity);
            });

            $.ajax({ //AJAX SÍNCRONO PARA EL CONTROL DE STOCK
                async: false,
                type: 'get',
                url: "./viewStock",
                data: {get: true},
                success: function(response){
                    stockItems = JSON.parse(response);
                    stockItems.forEach(function(stockItem, k){
                        if(stockItem.product_id == saleItem.prodId){
                            if(stockItem.quantity < saleItemTotalQuantity){
                                alert("El producto " + saleItem.prodDesc + " sólo cuenta con " + stockItem.quantity + 
                                " unidades en stock, usted quiere vender " + saleItemTotalQuantity + " unidades, no se puede dar de alta el pedido");
                                ret = false;
                            } 
                        }
                    });
                }// ACA IRIA EL error
            });

            // $.get("./viewStock", {get: true}, function(response) { // AJAX ASINCRONICO, NO FUNCIONA PARA ESTE CONTROL
            //     stockItems = JSON.parse(response);
            //     stockItems.forEach(function(stockItem, k){
            //         if(stockItem.product_id == saleItem.prodId){
            //             if(stockItem.quantity < saleItemTotalQuantity){
            //                 alert("El producto " + saleItem.prodDesc + " sólo cuenta con " + stockItem.quantity + 
            //                 " unidades en stock, usted quiere vender " + saleItemTotalQuantity + " unidades, no se puede dar de alta el pedido");
            //                 ret = false;
            //             } 
            //         }
            //     });
            // });
        });
        return ret; 
    }


    // ACA

    function validateForm(){
        if($("#saleDetailClientId").val().length === 0){
            alert("Asigne un cliente al pedido");
            return false;
        }
        if(saleItems.length === 0){
            alert("Agregue ítems al pedido");
            return false;
        }
        if(validateStock() === false) return false;
        return true;
    }

    function validateItemToAdd(){ //cambiar por un validar los campos de un objeto que sólo se renderize
        if($("#prodId").text().length === 0){
            alert("Falta el código de producto a agregar");
            return false;
        }
        if($("#prodDesc").text().length === 0){
            alert("Falta la descripción del producto a agregar");
            return false;
        }
        if($("#prodCost").val().length === 0){
            alert("Falta el costo del producto a agregar");
            return false;
        }
        if($("#prodPrice").val().length === 0){
            alert("Falta el precio del producto a agregar");
            return false;
        }
        if($("#prodQuantity").val().length === 0){
            alert("Falta la cantidad del producto a agregar");
            return false;
        }
        if($("#prodTotalPrice").text().length === 0){
            alert("Falta el total del producto a agregar");
            return false;
        }
        return true;
    }

    function getLastItem(){
        var lastItem = salesItems[salesItems.length - 1];
        return lastItem;
    }

    function getSaleTotalPrice(){
        var total = 0;
        saleItems.forEach(function(item, i){
            total += parseFloat(item.prodTotalPrice);
        });
        return total.toFixed(2);
    }

    function clearSaleTotalPrice() {
        $("#saleTotalPrice").empty();
    }

    function clearSaleDetail(){
        $("#saleDetailTableBody").empty();
    }

    function clearSaleDesc(){
        $("#saleDesc").val("");
    }

    function updateSaleTotalPrice(){
        $("#saleTotalPrice").empty();
        $("#saleTotalPrice").append(getSaleTotalPrice());
    }

    function renderSaleDetail(){
        clearSaleDetail();
        saleItems.forEach(function(item, i){
            $("#saleDetailTableBody").append('<tr id=' + i + '></tr>');
            $("#"+ i).append('<td>' + i + '</td>');
            $("#"+ i).append('<td>' + item.prodId + '</td>');
            $("#"+ i).append('<td>' + item.prodDesc + '</td>');
            $("#"+ i).append('<td>' + item.prodCost + '</td>');
            $("#"+ i).append('<td>' + item.prodPrice + '</td>');
            //$("#"+ i).append('<td>' + item.prodFactor + '</td>');
            $("#"+ i).append('<td>' + item.prodQuantity + '</td>');
            $("#"+ i).append('<td>' + item.prodTotalPrice + '</td>');
            $("#"+ i).append('<td><button id="delete' + i + '">Borrar</button></td>');
            $("#delete"+ i).click(function(){
                saleItems.splice(i, 1);
                renderSaleDetail();
            });
        });
        updateSaleTotalPrice();
    }

    function putClientInDetail(client){
        $("#saleDetailClientId").empty();
        $("#saleDetailClientName").empty();
        $("#saleDetailClientId").val(client.client_id);
        $("#saleDetailClientName").val(client.name);
    }

    function updateTotalItemToAdd(){
        $("#prodTotalPrice").empty();
        $("#prodTotalPrice").append(($("#prodPrice").val() * $("#prodQuantity").val()).toFixed(2));
    }

    function clearItemToAdd(){
        $("#prodId").empty();
        $("#prodDesc").empty();
        $("#prodCost").empty();
        $("#prodPrice").empty();
        $("#prodQuantity").empty();
        $("#prodTotalPrice").empty();
    }

    function putItemToAdd(product){
        clearItemToAdd();
        $("#prodId").append(product.product_id);
        $("#prodDesc").append(product.description);
        $("#prodCost").val(product.cost_price);
        var prodPrice = (product.cost_price * list.saleFactor).toFixed(2);
        $("#prodPrice").val(prodPrice); 
        $("#prodQuantity").val(1);
        var prodTotalPrice = ($("#prodPrice").val() * $("#prodQuantity").val()).toFixed(2);
        $("#prodTotalPrice").append(prodTotalPrice);
    }

    function getItemToAdd(){
        var item = {prodId : $("#prodId").text().trim(), prodDesc : $("#prodDesc").text().trim(),
                    prodCost : parseFloat($("#prodCost").val()).toFixed(2), prodPrice : parseFloat($("#prodPrice").val()).toFixed(2),
                    //prodFactor : ($("#prodPrice").val()/$("#prodCost").val()).toFixed(2), 
                    prodQuantity : $("#prodQuantity").val(), prodTotalPrice : parseFloat($("#prodTotalPrice").text()).toFixed(2)};
        return item;
    }

    function getClients(){
        $("#clients").empty();
        var filterValue = ""; // VER ESTO
        $.get("./viewClients", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) {
            response = JSON.parse(response);
            response.forEach(function(client) {
                $("#clients").append('<option value=' + client['client_id'] + '>' + client['name'] + '</option>');
            });
            $("#clients").change(function(){
                var clientId = this.value;
                response.forEach(function(client){
                    if(client.client_id === clientId){
                        putClientInDetail(client); 
                    }
                });
            });
            putClientInDetail(response[0]); 
        });
    }

    function getProducts(){
        var filterValue = "";
        $.get("./viewProducts", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) {
            response = JSON.parse(response);
            response.forEach(function(product) {
                $("#itemToAdd").append('<option value=' + product.product_id + '>' + product.description + '</option>');
            });
            $("#itemToAdd").change(function(){
                var prodId = this.value;
                response.forEach(function(product){
                    if(product.product_id === prodId){
                        putItemToAdd(product);
                    }
                });
            });
            putItemToAdd(response[0]);
        });
    }

    function getSale(){
        var sale = {
            client_id : $("#saleDetailClientId").val().trim(),
            total : getSaleTotalPrice(),
            items : saleItems,
            description : $("#saleDesc").val().trim()
        };
        return sale;
    }

    $("#prodCost").change(function(){
        $("#prodCost").val(parseFloat($("#prodCost").val()).toFixed(2));
    });

    $("#prodPrice").change(function(){
        $("#prodPrice").val(parseFloat($("#prodPrice").val()).toFixed(2));
        updateTotalItemToAdd();
    });

    $("#prodQuantity").change(function(){
        $("#prodQuantity").val(parseInt($("#prodQuantity").val()));
        updateTotalItemToAdd();
    });

    $("#addItem").click(function(){
        if(validateItemToAdd()){
            saleItems.push(getItemToAdd());
            renderSaleDetail();
        }
    });

    $("#submitSale").click(function(){
        if(validateForm()){
            var sale = getSale();
            sale = JSON.stringify(sale);
            $.post("./newSale", {new: true, sale: sale}, function(response){
                alert(response);
                console.log(response);
                locate("");
            });
        }
    });

    $("#clearSaleDetail").click(function(){
        clearSaleDetail();
        clearSaleTotalPrice();
        clearSaleDesc();
        saleItems = [];
    });

    getClients();
    getProducts();
});
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
