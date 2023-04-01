<?php 
// html/FormNewBuy.php
?>
<div id="make">
    <h3>Armado de Compra</h3>
    <label for="providers">Seleccione un proveedor: </label>
    <select name="providers" id="providers">

    </select>

    <label for="itemToAdd">Seleccione un producto: </label>
    <select name="itemToAdd" id="itemToAdd">

    </select>

    <input type="button" name="addItem" id="addItem" value="Agregar Item">

    <table id="itemToAddTable">    
        <thead> <tr><th>Cod.</th> <th>Descripción</th> <th>Costo</th> <th>Cantidad</th> <th>Total</th></tr> </thead>
        <tbody id="itemToAddTableBody">
            <tr>
                <td id="product_id"></td> 
                <td id="product_description"></td> 
                <td><input type="number" id="cost_price"></td> 
                <td><input type="number" id="quantity"></td> 
                <td id="total_cost"></td>
            </tr>
        </tbody>
    </table>
</div>

<div id="detail">
    <div id="buyDetailHeader">
        <h3>Detalle de Compra</h3> <input type="number" readonly id="buyDetailProviderId"> <input type="text" readonly id="buyDetailProviderName">
        <button id="submitBuy">Alta de Compra</button>
        <button id="clearBuyDetail">Limpiar detalle de Compra</button>
    </div>

    <div id="buyDetail">
        <table id="buyDetailTable">    
            <thead> <tr><th>Pos.</th> <th>Cod.</th> <th>Descripción</th> <th>Costo</th> <th>Cantidad</th> <th>Total</th> <th>Borrar Item</th></tr> </thead>
            <tbody id="buyDetailTableBody">
                
            </tbody>
            <tfoot> <tr> <td colspan=5>TOTAL: </td> <td colspan=2 id="buyTotalPrice"></td> </tr> </tfoot>
        </table>
    </div>
    
    <div id="buyDetailFooter">
        <div id="buyDescription">
            <textarea id="buyDesc" placeholder="Agregue una nota para esta compra si lo desea" maxlength=255></textarea>
        </div>
    </div>
</div>

<script>
$(document).ready(function (){
    function locate(url){
        $(location).attr('href',url);
    }

    var buyItems = new Array();
    var buyProvider = new Array();

    function validateForm(){
        if(buyProvider.length === 0){
            alert("Asigne un proveedor a la compra");
            return false;
        }

        // if($("#saleDetailClientId").val().length === 0){
        //     alert("Asigne un cliente al pedido");
        //     return false;
        // }
        if(buyItems.length === 0){
            alert("Agregue ítems al pedido");
            return false;
        }
        return true;
    }

    function validateItemToAdd(){ //cambiar por un validar los campos de un objeto que sólo se renderize
        if($("#product_id").text().length === 0){
            alert("Falta el código de producto a agregar");
            return false;
        }
        if($("#product_description").text().length === 0){
            alert("Falta la descripción del producto a agregar");
            return false;
        }
        if($("#cost_price").val().length === 0){
            alert("Falta el costo del producto a agregar");
            return false;
        }
        if($("#quantity").val().length === 0){
            alert("Falta la cantidad del producto a agregar");
            return false;
        }
        if($("#total_cost").text().length === 0){
            alert("Falta el total del producto a agregar");
            return false;
        }
        return true;
    }

    function getLastItem(){
        var lastItem = buyItems[buyItems.length - 1];
        return lastItem;
    }

    function getBuyTotalPrice(){
        var total = 0;
        buyItems.forEach(function(item, i){
            total += parseFloat(item.total_cost);
        });
        return total.toFixed(2);
    }

    function clearBuyTotalPrice() {
        $("#buyTotalPrice").empty();
    }

    function clearBuyItems(){
        $("#buyDetailTableBody").empty();
        buyItems = [];
    }

    function clearBuyDesc(){
        $("#buyDesc").val("");
    }

    function updateBuyTotalPrice(){
        $("#buyTotalPrice").empty();
        $("#buyTotalPrice").append(getBuyTotalPrice());
    }

    function renderBuyDetail(){
        $("#buyDetailTableBody").empty();
        buyItems.forEach(function(item, i){
            $("#buyDetailTableBody").append('<tr id=' + i + '></tr>');
            $("#"+ i).append('<td>' + i + '</td>');
            $("#"+ i).append('<td>' + item.product_id + '</td>');
            $("#"+ i).append('<td>' + item.product_description + '</td>');
            $("#"+ i).append('<td>' + item.cost_price + '</td>');
            $("#"+ i).append('<td>' + item.quantity + '</td>');
            $("#"+ i).append('<td>' + item.total_cost + '</td>');
            $("#"+ i).append('<td><button id="delete' + i + '">Borrar</button></td>');
            $("#delete"+ i).click(function(){
                buyItems.splice(i, 1);
                renderBuyDetail();
            });
        });
        updateBuyTotalPrice();
    }

    function putProviderInDetail(provider){
        $("#buyDetailProviderId").empty();
        $("#buyDetailProviderName").empty();
        $("#buyDetailProviderId").val(provider.provider_id);
        $("#buyDetailProviderName").val(provider.name);
    }

    function updateTotalItemToAdd(){
        $("#total_cost").empty();
        $("#total_cost").append(($("#cost_price").val() * $("#quantity").val()).toFixed(2));
    }

    function clearItemToAdd(){
        $("#product_id").empty();
        $("#product_description").empty();
        $("#cost_price").empty();
        $("#quantity").empty();
        $("#total_cost").empty();
    }

    function putItemToAdd(product){
        clearItemToAdd();
        $("#product_id").append(product.product_id);
        $("#product_description").append(product.description);
        $("#cost_price").val(product.cost_price); 
        $("#quantity").val(1);
        var totalCost = ($("#cost_price").val() * $("#quantity").val()).toFixed(2);
        $("#total_cost").append(totalCost);
    }

    function getItemToAdd(){
        var item = {product_id : $("#product_id").text().trim(), product_description : $("#product_description").text().trim(),
                    cost_price : parseFloat($("#cost_price").val()).toFixed(2), quantity : $("#quantity").val(), 
                    total_cost : parseFloat($("#total_cost").text()).toFixed(2)};
        return item;
    }

    function getProducts(provider){
        $("#itemToAdd").empty();
        var filterValue = "";
        $.get("./viewProducts", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) {
            products = JSON.parse(response);
            var flag = false;
            products.forEach(function(product) {
                if(product.provider_id == provider.provider_id){
                    if(!flag){
                        $("#itemToAdd").append('<option value=' + product.product_id + ' selected>' + product.description + '</option>');
                        putItemToAdd(product);
                        flag = true;
                    }else{
                        $("#itemToAdd").append('<option value=' + product.product_id + '>' + product.description + '</option>');
                    }
                }
            });
            $("#itemToAdd").change(function(){
                var prodId = this.value;
                products.forEach(function(product){
                    if(product.product_id === prodId){
                        putItemToAdd(product);
                    }
                });
            });
            // putItemToAdd(products[0]); // SOLUCIONADO CON EL FLAG
        });
    }   

    function getProviders(){
        $("#providers").empty();
        var filterValue = ""; // VER ESTO
        $.get("./viewProviders", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) {
            providers = JSON.parse(response);
            providers.forEach(function(provider) {
                $("#providers").append('<option value=' + provider['provider_id'] + '>' + provider['name'] + '</option>');
            });
            $("#providers").change(function(){
                clearBuyItems();
                var providerId = this.value;
                providers.forEach(function(provider){
                    if(provider.provider_id === providerId){
                        buyProvider = [];
                        buyProvider.push(provider);
                        putProviderInDetail(provider); 
                        getProducts(provider);
                    }
                });
            });
            buyProvider = [];
            buyProvider.push(providers[0]); //ACA
            putProviderInDetail(providers[0]); 
            getProducts(providers[0]);
        });
    }

    function getBuy(){
        var buy = {
            provider_id : buyProvider[0].provider_id,//$("#buyDetailProviderId").val().trim(), 
            total : getBuyTotalPrice(),
            items : buyItems,
            description : $("#buyDesc").val().trim()
        };
        return buy;
    }

    $("#cost_price").change(function(){
        $("#cost_price").val(parseFloat($("#cost_price").val()).toFixed(2));
        updateTotalItemToAdd();
    });

    // $("#prodPrice").change(function(){
    //     $("#prodPrice").val(parseFloat($("#prodPrice").val()).toFixed(2));
    //     updateTotalItemToAdd();
    // });

    $("#quantity").change(function(){
        $("#quantity").val(parseInt($("#quantity").val()));
        updateTotalItemToAdd();
    });

    $("#addItem").click(function(){
        if(validateItemToAdd()){
            buyItems.push(getItemToAdd());
            renderBuyDetail();
        }
    });

    $("#submitBuy").click(function(){
        if(validateForm()){
            var buy = getBuy();
            buy = JSON.stringify(buy);
            //alert(buy); //ACAAAAAAAAAA
            $.post("./newBuy", {new: true, buy: buy}, function(response){
                alert(response);
                console.log(response);
                locate("");
            });
        }
    });

    function clearBuyDetail(){
        clearBuyItems();
        clearBuyTotalPrice();
        clearBuyDesc();
    }

    $("#clearBuyDetail").click(function(){
        clearBuyDetail();
    });

    getProviders();
});
</script>   

