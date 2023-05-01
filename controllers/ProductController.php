<?php
//controllers/ProductController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Products.php';
require_once '../views/ViewProducts.php';
require_once '../views/ViewProduct.php';
require_once '../views/ViewProductsRestricted.php';
require_once '../views/FormNewProduct.php';

class ProductController extends Controller{
    public function __construct(){
        $this->models['products']     = new Products();
        $this->views['dashboard']     = new ViewProducts(includeJs: "js/viewProducts.js", includeCSS: "css/viewProducts.css");
        $this->views['productDetail'] = new ViewProduct(includeJs: "js/viewProduct.js", includeCSS: "css/viewProduct.css");
        $this->views['form']          = new FormNewProduct();        
    }

    // A partir de acá se actualiza el desarrollo 
    public function getProductsToDashboard() {
        $products = $this->models['products']->getProductsListStock($_GET['filterDesc']);
        return $products;
    }
    public function viewDashboard() { 
        $this->views['dashboard']->render();
    }
    public function viewProductDetail() { 
        if(empty($_GET['id'])) throw new Exception("Falta el identificador del producto a consultar");
        $product = $this->models['products']->getProductDetail($_GET['id']);
        $this->views['productDetail']->product = $product;
        $this->views['productDetail']->render();
    }
    public function modifyProduct() {
        $product = $this->validateExistProduct();
        $this->validateNotEmptyProduct($product);
        $this->models['products']->updateProduct($product);
        $msg = "Se modificó correctamente el producto #$product->product_id.";
        return $msg;
    }
    public function validateExistProduct() {
        if(!isset($_POST['product'])) throw new Exception("Envíe un producto");
        $product = json_decode($_POST['product']);
        if(!isset($product->product_id)) throw new Exception("Falta el identificador del producto");
        if(!isset($product->cost_price) && !isset($product->product_price) && !isset($product->product_quantity)) 
            throw new Exception("Envíe algún dato del producto a modificar");
        return $product;
    }
    public function validateNotEmptyProduct($product) {
        if(empty($product->product_id)) throw new Exception("El identificador del producto está vacío");
        if( (empty($product->cost_price) && $product->cost_price != 0) && 
            (empty($product->product_price) && $product->product_price != 0) && 
            (empty($product->product_quantity) && $product->product_quantity != 0) ) 
            throw new Exception("Envíe algún dato del producto a modificar");
        return true;
    }

    // Hasta acá
    public function get($filterValue) { // Falta fix, está función la consume el formulario de ventas y cotizaciones
        // MODIFICAR ACA
        try{
            $ret = $this->models['products']->getProductsListStock($filterValue);
        }
        catch(QueryErrorException $error){ //ACA HAY QUE VER COMO DEVOLVER Y MOSTRAR ESTE MSG DE ERROR (PROBABLEMENTE CONDICIONAL DESDE FRONT)
            $msg = "Se produjo un error intentando consultar los productos con el filtro " . $filterValue . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
            $ret = $msg;
        }
        return $ret;
    }











    public function new($product){ 
        $ret = true;
        try{
            $this->models['products']->newProduct($product);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando dar de alta el producto " . $product->description . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        if($ret === true){
            $ret = "Se ha dado de alta con éxito el producto " . $product->description;
        }      
        return $ret;
    }

    public function delete($id){
        $ret = true;
        try{
            $this->models['products']->deleteProductById($id);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando eliminar el producto con id " . $id . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        if($ret === true){
            $ret = "Se eliminó con éxito el producto con id " . $id;
        }      
        return $ret;
    }

    public function update($product){
        try{
            $this->models['products']->updateProduct($product);
        }
        catch(QueryErrorException $error){ 
            $msg = "Se produjo un error intentando dar de alta el producto  " . $product->description . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
            return false;
        }
        return true;
    }



    public function viewForm($perm){
        if($perm == 1){
            $this->views['form']->render();
        }else {
            echo "No tiene acceso al alta de productos";
            exit();
        }
    }
}

?>