<?php
//controllers/ProductController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Products.php';
require_once '../views/ViewProducts.php';
require_once '../views/ViewProductsRestricted.php';
require_once '../views/FormNewProduct.php';

class ProductController extends Controller{
    public function __construct(){
        $this->models['products'] = new Products();
        // $this->views['adminCRUD'] = new ViewProducts();
        // $this->views['normalCRUD'] = new ViewProductsRestricted();
        $this->views['dashboard'] = new ViewProducts(includeJs: "js/viewProducts.js", includeCSS: "css/viewProducts.css");
        $this->views['form'] = new FormNewProduct();        
    }

    // A partir de acá se actualiza el desarrollo 
    public function getProductsToDashboard() {
        $products = $this->models['products']->getProductsListStock($_GET['filterDesc']);
        return $products;
    }
    // Hasta acá
    public function viewDashboard() { // Falta fix
        $this->views['dashboard']->render();
    }
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