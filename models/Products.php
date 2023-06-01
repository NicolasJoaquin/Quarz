<?php
//models/Products.php

require_once '../fw/fw.php';

class Products extends Model{
    // Modificado desde acá
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getProductDetail($id) {
        $this->db->validateSanitizeId($id, "El identificador del producto es inválido");

        $this->db->query("SELECT prod.product_id, prod.description AS product_name, prov.name AS provider_name,
                            prod.cost_price, prod.packing_unit, stock.quantity AS product_quantity, prices.product_price 
                            FROM products AS prod
                            JOIN stock_items AS stock ON prod.product_id = stock.product_id 
                            JOIN sale_prices AS prices ON prod.product_id = prices.product_id 
                            JOIN providers AS prov ON prod.provider_id = prov.provider_id 
                            WHERE stock.warehouse_id = 1 AND prices.price_list_id = 1 AND prod.product_id = $id"); 
        $this->db->validateLastQuery();
        $product = $this->db->fetch();
        if($this->db->numRows() != 1)
            throw new Exception("Hubo un error al consultar el producto #$id");
        return $product;
    }
    public function getProductChanges($id) {
        $this->db->validateSanitizeId($id, "El identificador del producto es inválido");
        $this->db->query("SELECT pc.product_change_id, pc.user_id, pc.product_id, pc.cost_price, 
                        pc.first_charge, pc.date, pc.old_cost_price, prod.description AS product_name, u.user AS user_name
                        FROM product_changes AS pc
                        JOIN products AS prod ON pc.product_id = prod.product_id 
                        JOIN users AS u ON pc.user_id = u.user_id
                        WHERE pc.product_id = $id");
        $this->db->validateLastQuery();
        $changes = $this->db->fetchAll();
        $this->db->query("SELECT description FROM products WHERE product_id = $id");
        $this->db->validateLastQuery();
        $changes[-1]['product_id']   = $id;
        $changes[-1]['product_name'] = $this->db->fetch()['description'];
        return $changes;
    }
    public function getPriceChanges($id) {
        $this->db->validateSanitizeId($id, "El identificador del producto es inválido");
        $this->db->query("SELECT pc.price_change_id, pc.user_id, pc.sale_price_id, pc.product_price, 
                        pc.first_charge, pc.date, pc.old_product_price, prod.description AS product_name, u.user AS user_name, prod.product_id
                        FROM price_changes AS pc
                        JOIN sale_prices AS sp ON pc.sale_price_id = sp.sale_price_id 
                        JOIN products AS prod ON sp.product_id = prod.product_id 
                        JOIN users AS u ON pc.user_id = u.user_id
                        WHERE sp.product_id = $id AND sp.price_list_id = 1"); // Por ahora sólo lista 1
        $this->db->validateLastQuery();
        $changes = $this->db->fetchAll();
        $this->db->query("SELECT description FROM products WHERE product_id = $id");
        $this->db->validateLastQuery();
        $changes[-1]['product_id']   = $id;
        $changes[-1]['product_name'] = $this->db->fetch()['description'];
        return $changes;
    }
    public function getStockChanges($id) {
        $this->db->validateSanitizeId($id, "El identificador del producto es inválido");
        $this->db->query("SELECT sc.stock_change_id, sc.user_id, sc.sale_item_id, sc.buy_item_id, sc.stock_item_id, sc.quantity, 
                        sc.first_charge, sc.date, sc.old_quantity, prod.description AS product_name, u.user AS user_name, prod.product_id,
                        s.sale_id, b.buy_id
                        FROM stock_changes AS sc
                        LEFT JOIN stock_items AS si ON sc.stock_item_id = si.stock_item_id 
                        LEFT JOIN products AS prod ON si.product_id = prod.product_id 
                        LEFT JOIN sales_items AS sitems ON sc.sale_item_id = sitems.sale_item_id
                        LEFT JOIN buys_items AS bitems ON sc.buy_item_id = bitems.buy_item_id
                        LEFT JOIN sales AS s ON sitems.sale_id = s.sale_id
                        LEFT JOIN buys AS b ON bitems.buy_id = b.buy_id
                        LEFT JOIN users AS u ON sc.user_id = u.user_id
                        WHERE si.product_id = $id AND si.warehouse_id = 1"); // Por ahora sólo depósito 1
        $this->db->validateLastQuery();
        $changes = $this->db->fetchAll();
        $this->db->query("SELECT description FROM products WHERE product_id = $id");
        $this->db->validateLastQuery();
        $changes[-1]['product_id']   = $id;
        $changes[-1]['product_name'] = $this->db->fetch()['description'];
        return $changes;
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newProduct($product) {
        $this->db->validateSanitizeString($product->desc, "La descripción del producto es inválida");
        $this->db->validateSanitizeString($product->packingUnit, "La unidad de empaque del producto es inválida");
        $this->db->validateSanitizeId($product->provider, "El proveedor es inválido");
        $this->db->validateSanitizeFloat($product->costPrice, "El costo del producto es inválido");
        if($product->costPrice < 0)  // Pasar a validación desde Database
            throw new Exception("El costo del producto no puede ser menor a 0"); 
        $this->db->validateSanitizeFloat($product->salePrice, "El precio de venta del producto es inválido");
        if($product->salePrice < 0)  // Pasar a validación desde Database
            throw new Exception("El precio de venta del producto no puede ser menor a 0"); 
        $this->db->validateSanitizeFloat($product->quantity, "La cantidad del producto es inválida");
        if($product->quantity < 0)  // Pasar a validación desde Database
            throw new Exception("La cantidad del producto no puede ser menor a 0"); 

        $this->db->query("INSERT INTO products (description, provider_id, cost_price, packing_unit) 
                    VALUES ('$product->desc', $product->provider, $product->costPrice, '$product->packingUnit')");
        $this->db->validateLastQuery();
        $lastProd = $this->db->getLastInsertId();
        if(!$lastProd)
            throw new Exception("Hubo un error al dar de alta el producto");
        // A partir de acá falta fix
        if($product->salePrice > 0) {
            $this->db->query("UPDATE sale_prices SET 
                        product_price = $product->salePrice
                        WHERE price_list_id = 1 AND product_id = $lastProd"); // Por ahora sólo lista 1
            $this->db->validateLastQuery();

            $this->db->query("SELECT sale_price_id 
                        FROM sale_prices 
                        WHERE price_list_id = 1 AND product_id = $lastProd");
            $this->db->validateLastQuery();

            $salePriceId = $this->db->fetch()['sale_price_id']; // Provisorio, falta fix

            $this->db->query("UPDATE price_changes SET 
                        product_price = $product->salePrice
                        WHERE first_charge = 1 AND sale_price_id = $salePriceId"); // Provisorio, falta fix
            $this->db->validateLastQuery();
        }
        if($product->quantity > 0) {
            $this->db->query("UPDATE stock_items SET 
                        quantity = $product->quantity
                        WHERE warehouse_id = 1 AND product_id = $lastProd"); // Por ahora sólo depósito 1
            $this->db->validateLastQuery();

            $this->db->query("SELECT stock_item_id
                        FROM stock_items 
                        WHERE warehouse_id = 1 AND product_id = $lastProd");
            $this->db->validateLastQuery();
            $stockItemId = $this->db->fetch()['stock_item_id']; // Provisorio, falta fix

            $this->db->query("UPDATE stock_changes SET 
                        quantity = $product->quantity
                        WHERE first_charge = 1 AND stock_item_id = $stockItemId"); // Provisorio, falta fix
            $this->db->validateLastQuery();
        }

        return $lastProd;
    }

    
    public function updateProduct($product) {
        $this->db->validateSanitizeId($product->product_id, "El identificador del producto es inválido");

        $this->db->validateSanitizeFloat($product->cost_price, "El costo del producto es inválido");
        if($product->cost_price < 0)  
            throw new Exception("El costo del producto no puede ser menor a 0");

        $this->db->validateSanitizeFloat($product->product_price, "El precio del producto es inválido");
        if($product->product_price < 0)  
            throw new Exception("El precio del producto no puede ser menor a 0");

        $this->db->validateSanitizeFloat($product->product_quantity, "La cantidad del producto es inválida");
        if($product->product_quantity < 0)  
            throw new Exception("La cantidad del producto no puede ser menor a 0");

        $user = $_SESSION['user_id'];

        if(!empty($product->cost_price) || $product->cost_price == 0) { // Costo
            $this->db->query("SELECT cost_price
                            FROM products 
                            WHERE product_id = $product->product_id"); 
            $this->db->validateLastQuery();
            $prod   = $this->db->fetch();
            $oldCost = $prod['cost_price'];

            if($oldCost != $product->cost_price) {
                $this->db->query("INSERT INTO product_changes (user_id, product_id, cost_price, old_cost_price) 
                                VALUES ($user, $product->product_id, $product->cost_price, $oldCost)");
                $this->db->validateLastQuery();

                $this->db->query("UPDATE products SET 
                                cost_price = $product->cost_price
                                WHERE product_id = $product->product_id");
                $this->db->validateLastQuery();
            }
        }
        if(!empty($product->product_price) || $product->product_price == 0) { // Precio de venta
            $this->db->query("SELECT sale_price_id, product_price
                            FROM sale_prices 
                            WHERE product_id = $product->product_id AND price_list_id = 1"); // Sólo lista 1 por ahora
            $this->db->validateLastQuery();
            $salePrice   = $this->db->fetch();
            $salePriceId = $salePrice['sale_price_id'];
            $oldPrice = $salePrice['product_price'];

            if($oldPrice != $product->product_price) {
                $this->db->query("INSERT INTO price_changes (user_id, sale_price_id, product_price, old_product_price) 
                                VALUES ($user, $salePriceId, $product->product_price, $oldPrice)");
                $this->db->validateLastQuery();

                $this->db->query("UPDATE sale_prices SET 
                                product_price = $product->product_price
                                WHERE product_id = $product->product_id AND price_list_id = 1"); // Sólo lista 1 por ahora
                $this->db->validateLastQuery();
            }
        }
        if(!empty($product->product_quantity) || $product->product_quantity == 0) { // Stock
            $this->db->query("SELECT stock_item_id, quantity
                            FROM stock_items 
                            WHERE product_id = $product->product_id AND warehouse_id = 1"); // Sólo depósito 1 por ahora
            $this->db->validateLastQuery();
            $stockItem   = $this->db->fetch();
            $stockItemId = $stockItem['stock_item_id'];
            $oldQuantity = $stockItem['quantity'];

            if($oldQuantity != $product->product_quantity) {
                $this->db->query("INSERT INTO stock_changes (user_id, stock_item_id, quantity, old_quantity) 
                                VALUES ($user, $stockItemId, $product->product_quantity, $oldQuantity)");
                $this->db->validateLastQuery();

                $this->db->query("UPDATE stock_items SET 
                                quantity = $product->product_quantity
                                WHERE product_id = $product->product_id AND warehouse_id = 1"); // Sólo depósito 1 por ahora
                $this->db->validateLastQuery();
            }
        }
        return true;
    }
    // Hasta acá
    
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM products");
        return $this->db->fetchAll();
    }

    public function getProducts($filterValue){
        // VALIDO FILTRO
        if(!empty($filterValue)){
            $filterValue = substr($filterValue, 0, 50);
            $filterValue = $this->db->escape($filterValue);
            $filterValue = $this->db->escapeWildcards($filterValue);
        }

        $this->db->query("SELECT *
                            FROM view_products as p 
                            WHERE p.product_id LIKE '%$filterValue%' OR 
                                    p.description LIKE '%$filterValue%' OR
                                    p.packing_unit LIKE '%$filterValue%'
                            ORDER BY product_id"); // MODIFICAR LIMIT
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    public function getProductsListStock($filterValue) { // Por ahora sólo depósito Hur. y lista minorista FALTA FIX
        // VALIDO FILTRO
        if(!empty($filterValue)){
            $filterValue = substr($filterValue, 0, 100);
            $filterValue = $this->db->escape($filterValue);
            $filterValue = $this->db->escapeWildcards($filterValue);
        }

        $this->db->query("SELECT *
                            FROM view_products_list_stock as p 
                            WHERE p.product_id LIKE '%$filterValue%' OR 
                                    p.description LIKE '%$filterValue%' OR
                                    p.packing_unit LIKE '%$filterValue%'
                            ORDER BY product_id DESC"); // MODIFICAR LIMIT Y FILTROS
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------

    public function deleteProductById($id){
        //Valido id
        if(!ctype_digit($id)) die ("error 1 deleteProductById/Products (modelo)");

        //QUERY DELETE
        $this->db->query("DELETE FROM products 
                            WHERE product_id = $id");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }

}

?>