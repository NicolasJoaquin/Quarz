<?php
//models/Products.php

require_once '../fw/fw.php';

class Products extends Model{

    //GETERS------------------------------------------------------------------------------------------------------------------------
    // Modificado desde acá
    public function getProductDetail($id) {
        $this->db->validateSanitizeId($id, "El identificador del producto es inválido");
        //QUERY INSERT
        $this->db->query("SELECT prod.product_id, prod.description AS product_name, prov.name AS provider_name,
                            prod.cost_price, prod.packing_unit, stock.quantity AS product_quantity, prices.product_price 
                            FROM products AS prod
                            LEFT JOIN stock_items AS stock ON prod.product_id = stock.product_id 
                            LEFT JOIN sale_prices AS prices ON prod.product_id = prices.product_id 
                            LEFT JOIN providers AS prov ON prod.provider_id = prov.provider_id 
                            WHERE stock.warehouse_id = 1 AND prices.price_list_id = 1 AND prod.product_id = $id"); 
        $this->db->validateLastQuery();
        $product = $this->db->fetch();
        if($this->db->numRows() != 1)
            throw new Exception("Hubo un error al consultar el producto #$id");
        return $product;
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
                                    p.packing_unit LIKE '%$filterValue%'"); // MODIFICAR LIMIT
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
                                    p.packing_unit LIKE '%$filterValue%'"); // MODIFICAR LIMIT Y FILTROS
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newProduct($product){
        //Valido description
        if(strlen($product->description) > 255 ) die ("error 1 newProduct/Products (modelo)");
        if(strlen($product->description) < 4 ) die ("error 2 newProduct/Products (modelo)");  
        $product->description = $this->db->escape($product->description);

        //Valido cost_price
        if(!is_numeric($product->cost_price)) die ("error 3 newProduct/Products (modelo)");

        //Valido packing_unit
        if(strlen($product->packing_unit) > 255 ) die ("error 4 newProduct/Products (modelo)");
        if(strlen($product->packing_unit) < 3 ) die ("error 5 newProduct/Products (modelo)");  
        $product->packing_unit = $this->db->escape($product->packing_unit);

        //Valido provider_id
        if(!ctype_digit($product->provider_id)) die ("error 6 newProduct/Products (modelo)");

        //QUERY INSERT
        $this->db->query("INSERT INTO products (description, provider_id, cost_price, packing_unit) 
                            VALUES ('$product->description', '$product->provider_id', '$product->cost_price', '$product->packing_unit')");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }

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

    public function updateProduct($product){
        //Valido id
        if(!ctype_digit($product->id)) die ("error 0 updateProduct/Products (modelo)");

        //Valido description
        if(strlen($product->description) > 50) die ("error 1 updateProduct/Products (modelo)");
        if(strlen($product->description) < 4) die ("error 2 updateProduct/Products (modelo)");  
        $product->description = $this->db->escape($product->description);

        //Valido cost_price
        if(!is_numeric($product->cost_price)) die ("error 3 newProduct/Products (modelo)");

        //Valido packing_unit
        if(strlen($product->packing_unit) > 255 ) die ("error 4 updateProduct/Products (modelo)");
        if(strlen($product->packing_unit) < 3 ) die ("error 5 updateProduct/Products (modelo)");  
        $product->packing_unit = $this->db->escape($product->packing_unit);

        //Valido provider_id
        if(!ctype_digit($product->provider_id)) die ("error 6 newProduct/Products (modelo)");

         //QUERY UPDATE
         $this->db->query("UPDATE products
                            SET description = '$product->description', provider_id = '$product->provider_id', cost_price = '$product->cost_price', packing_unit = '$product->packing_unit'
                            WHERE product_id = $product->id");
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }
}

?>