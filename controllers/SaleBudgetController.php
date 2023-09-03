<?php
//controllers/SaleBudgetController.php
require_once '../fw/fw.php'; 
require_once '../models/Sales.php';
require_once '../models/Budgets.php';
require_once '../models/Stock.php';
require_once '../models/ShipmentStates.php';
require_once '../models/PaymentStates.php';
require_once '../views/FormNewSaleBudget.php';
require_once '../views/NewBudgetVersion.php';
require_once '../views/ViewSales.php';
require_once '../views/ViewSale.php';
require_once '../views/ViewBudgets.php';
require_once '../views/ViewBudget.php';

class SaleBudgetController extends Controller {
    public function __construct() {
        /* Models */
        $this->models['sales']   = new Sales();
        $this->models['budgets'] = new Budgets();
        $this->models['stock']   = new Stock();
        $this->models['shipment_states'] = new ShipmentStates();
        $this->models['payment_states']  = new PaymentStates();
        $this->models['shipment_methods'] = new ShipmentStates();
        $this->models['payment_states']  = new PaymentStates();
        /* Views */
        $this->views['dashboard']       = new ViewSales(title: "Dashboard ventas", includeJs: "js/viewSales.js", includeCSS: "css/viewSales.css", includesCSS: ["css/stdCustom.css"]);
        $this->views['budgetDashboard'] = new ViewBudgets(title: "Dashboard cotizaciones", includeJs: "js/viewBudgets.js", includesCSS: ["css/stdCustom.css"]);
        $this->views['form']            = new FormNewSaleBudget(title: "Nueva venta o cotización", includeJs: "./js/formSaleBudget.js", includeCSS: "./css/formSaleBudget.css");
        $this->views['saleDetail']      = new ViewSale(includeJs: "js/viewSale.js", includeCSS: "css/stdCustom.css");
        $this->views['budgetDetail']    = new ViewBudget(includeJs: "js/viewBudget.js", includeCSS: "css/stdCustom.css");
        /* Budget versions */
        $this->views['formNewBudgetVersion'] = new NewBudgetVersion(includeJs: "js/newBudgetVersion.js", includeCSS: "css/stdCustom.css");
    }
    // Vistas
    public function viewFormNewBudgetVersion() {
        if(empty($_GET['number'])) throw new Exception("El número de la cotización está vacío o es inválido");
        if(!$budget = $this->models['budgets']->existNumber($_GET['number'])) { 
            header("Location: ./viewBudgets");
            exit();
        }    
        $this->views['formNewBudgetVersion']->budgetNumber  = $budget['budget_number'];
        $this->views['formNewBudgetVersion']->budgetVersion = $budget['version'];
        $this->views['formNewBudgetVersion']->render();
    }
    public function viewForm() {
        $this->views['form']->render();
    }
    public function viewDashboard() { 
        $this->views['dashboard']->render();
    }
    public function viewBudgetDashboard() { 
        $this->views['budgetDashboard']->render();
    }
    public function viewBudgetDetail() { 
        /* Se toma el number que viene desde el front para filtrar por budget_number (budget_versions) */
        if(empty($_GET['number'])) throw new Exception("El número de la cotización está vacío o es inválido");
        $budget = new stdClass();
        if(empty($_GET['version'])) {
            if(!$this->models['budgets']->existNumber($_GET['number'])) { 
                header("Location: ./viewBudgets");
                exit();
            }    
            /* Se trae como cotización principal, siempre la última versión */
            $budget->info  = $this->models['budgets']->getLastVersionBudgetInfo($_GET['number']);
            $budget->items = $this->models['budgets']->getLastVersionBudgetItems($_GET['number']);
        }
        else {
            if(!$this->models['budgets']->existNumberVersion($_GET['number'], $_GET['version'])) { 
                /* Por default redirecciona a la última versión del número de cotización provisto */
                header("Location: ./viewBudget-".$_GET['number']);
                exit();
            }    
            $budget->info  = $this->models['budgets']->getNumberVersionBudgetInfo($_GET['number'], $_GET['version']); 
            $budget->items = $this->models['budgets']->getNumberVersionBudgetItems($_GET['number'], $_GET['version']); 
        }
        $budget->versionsIds    = $this->models['budgets']->getBudgetVersionsIds($_GET['number']); 
        $budget->info['start_date'] = $this->sqlDateToNormal($budget->info['start_date']);
        $this->views['budgetDetail']->budget = $budget;
        $this->views['budgetDetail']->render();
    }
    public function viewSaleDetail() { 
        if(empty($_GET['id'])) throw new Exception("El identificador de la venta a consultar está vacío o es inválido");
        $sale = new stdClass();
        if(!$this->models['sales']->exist($_GET['id'])){
            header("Location: ./viewSales");
            exit();
        }
        $sale->info        = $this->models['sales']->getSaleInfo($_GET['id']);
        $sale->items       = $this->models['sales']->getSaleItems($_GET['id']);
        $sale->payChanges  = $this->models['sales']->getPaymentStateChanges($_GET['id']);
        $sale->shipChanges = $this->models['sales']->getShipmentStateChanges($_GET['id']);
        /* Format de fechas para mostrar en el front */
        foreach($sale->payChanges as $k => $change) 
            $sale->payChanges[$k]['date'] = $this->sqlDateToNormal($change['date']);
        foreach($sale->shipChanges as $k => $change) 
            $sale->shipChanges[$k]['date'] = $this->sqlDateToNormal($change['date']);
        $sale->info['start_date'] = $this->sqlDateToNormal($sale->info['start_date']);
        $this->views['saleDetail']->sale = $sale;
        $this->views['saleDetail']->render();
    }
    // Getters
    public function getBudgetsToDashboard() { 
        $filters        = new stdClass();
        $orders         = new stdClass();
        $data           = new stdClass();
        $limitOffset    = 0;
        $limitLength    = 10000;
        if(!empty($_GET['filters']))
            $filters = json_decode($_GET['filters']);
        if(!empty($_GET['orders']))
            $orders = json_decode($_GET['orders']);
        if(!empty($_GET['limitOffset']))
            $limitOffset = json_decode($_GET['limitOffset']);
        if(!empty($_GET['limitLength']))
            $limitLength = json_decode($_GET['limitLength']);
        $data->budgets = $this->models['budgets']->getBudgets($filters, $orders, $limitOffset, $limitLength);
        /* Format de fecha para mostrar en el front */
        foreach($data->budgets as $k => $budget) 
            $data->budgets[$k]['start_date'] = $this->sqlDateToNormal($budget['start_date']);
        $data->subtotal    = $this->models['budgets']->getSubtotalOfBudgets($filters, $limitOffset, $limitLength);
        $data->total       = $this->models['budgets']->getTotalOfBudgets($filters, $limitOffset, $limitLength);
        $data->registers   = $this->models['budgets']->getTotalRegisters($filters);
        return $data;
    }
    public function getSalesToDashboard() {
        $filters        = new stdClass();
        $orders         = new stdClass();
        $sales          = new stdClass();
        $limitOffset    = 0;
        $limitLength    = 10000;
        if(!empty($_GET['filters']))
            $filters = json_decode($_GET['filters']);
        if(!empty($_GET['orders']))
            $orders = json_decode($_GET['orders']);
        if(!empty($_GET['limitOffset']))
            $limitOffset = json_decode($_GET['limitOffset']);
        if(!empty($_GET['limitLength']))
            $limitLength = json_decode($_GET['limitLength']);
        $sales->sales = $this->models['sales']->getSales($filters, $orders, $limitOffset, $limitLength);
        /* Format de fecha para mostrar en el front */
        foreach($sales->sales as $k => $sale) 
            $sales->sales[$k]['start_date'] = $this->sqlDateToNormal($sale['start_date']);
        $sales->total       = $this->models['sales']->getTotalOfSales($filters, $limitOffset, $limitLength);
        $sales->registers   = $this->models['sales']->getTotalRegisters($filters);
        $sales->lastShipState = $this->models['shipment_states']->getLastStep()['title'];
        $sales->lastPayState  = $this->models['payment_states']->getLastStep()['title'];
        return $sales;
    }
    public function getBudgetToNewVersion() {
        if(empty($_GET['number'])) throw new Exception("El número de la cotización está vacío o es inválido");
        $budget = new stdClass();
        if(!$this->models['budgets']->existNumber($_GET['number'])) { 
            header("Location: ./viewBudgets");
            exit();
        }    
        /* Se trae como cotización principal, siempre la última versión */
        $budget->info  = $this->models['budgets']->getLastVersionBudgetInfo($_GET['number']);
        $budget->info['start_date'] = $this->sqlDateToNormal($budget->info['start_date']);
        $budget->items = $this->models['budgets']->getLastVersionBudgetItems($_GET['number']);
        /* Pendiente de fix */
        $budget->client     = new stdClass();
        $budget->shipMethod = new stdClass();
        $budget->payMethod  = new stdClass();
        $budget->client->client_id              = $budget->info['client_id'];
        $budget->subtotalPrice                  = $budget->info['subtotal'];
        $budget->discount                       = $budget->info['discount'];
        $budget->tax                            = $budget->info['tax'];
        $budget->ship                           = $budget->info['ship'];
        $budget->totalPrice                     = $budget->info['total'];
        $budget->shipMethod->shipment_method_id = $budget->info['shipment_method_id'];
        $budget->payMethod->payment_method_id   = $budget->info['payment_method_id'];
        $budget->notes                          = $budget->info['description'];
        /* End: Pendiente de fix */        
        return $budget;
    }
    // Validadores
    public function validateExistItems($items) {
        foreach($items as $k => $item) {
            if(!isset($item->cost_price)) throw new Exception("Falta el precio de costo del ítem #$k");
            if(!isset($item->description)) throw new Exception("Falta la descripción del ítem #$k");
            if(!isset($item->product_id)) throw new Exception("Falta el identificador del ítem #$k");
            if(!isset($item->quantity)) throw new Exception("Falta la cantidad del ítem #$k");
            if(!isset($item->sale_price)) throw new Exception("Falta el precio de venta del ítem #$k");
        }
        return true;
    }
    public function validateNotEmptyItems($items) {
        foreach($items as $k => $item) {
            // if(empty($item->cost_price)) throw new Exception("Falta el precio de costo del ítem #$k");
            if(empty($item->description)) throw new Exception("Falta la descripción del ítem #$k");
            if(empty($item->product_id)) throw new Exception("Falta el identificador del ítem #$k");
            if(empty($item->quantity)) throw new Exception("Falta la cantidad del ítem #$k");
            // if(empty($item->sale_price)) throw new Exception("Falta el precio de venta del ítem #$k");
        }
        return true;
    }
    public function validateExistBudget() { // Revisar, se podría pasar todo a validateNotEmptyBudget
        if(!isset($_POST['budget'])) throw new Exception("Envíe una cotización para dar de alta");
        $budget = json_decode($_POST['budget']);
        if(!isset($budget->client->client_id)) throw new Exception("Falta el cliente de la cotización");
        if(!isset($budget->items)) throw new Exception("Faltan los ítems de la cotización");
        $this->validateExistItems($budget->items);
        if(!isset($budget->subtotalPrice)) throw new Exception("Falta el precio subtotal de la cotización");
        if(!isset($budget->totalPrice)) throw new Exception("Falta el precio total de la cotización");
        return $budget;
    }
    public function validateNotEmptyBudget($budget) {
        if(empty($budget->items) || count($budget->items) == 0) throw new Exception("Envíe ítems para dar de alta en la cotización");
        $this->validateNotEmptyItems($budget->items);
        if(empty($budget->client->client_id)) throw new Exception("Envíe el identificador del cliente para dar de alta la cotización");
        if(empty($budget->subtotalPrice) && $budget->subtotalPrice != 0) throw new Exception("Envíe el precio subtotal de la cotización");
        if(empty($budget->totalPrice) && $budget->totalPrice != 0) throw new Exception("Envíe el precio total de la cotización");
        return true;
    }
    public function validateExistSale() {
        if(!isset($_POST['sale'])) throw new Exception("Envíe una venta para dar de alta");
        $sale = json_decode($_POST['sale']);
        if(!isset($sale->client->client_id)) throw new Exception("Falta el cliente de la venta");
        if(!isset($sale->items)) throw new Exception("Faltan los ítems de la venta");
        $this->validateExistItems($sale->items);
        if(!isset($sale->subtotalPrice)) throw new Exception("Falta el precio subtotal de la venta");
        if(!isset($sale->totalPrice)) throw new Exception("Falta el precio total de la venta");
        return $sale;
    }
    public function validateNotEmptySale($sale) {
        if(empty($sale->items) || count($sale->items) == 0) throw new Exception("Envíe ítems para dar de alta en la venta");
        $this->validateNotEmptyItems($sale->items);
        if(empty($sale->client->client_id)) throw new Exception("Envíe el identificador del cliente para dar de alta la venta");
        if(empty($sale->subtotalPrice) && $sale->subtotalPrice != 0) throw new Exception("Envíe el precio subtotal de la venta");
        if(empty($sale->totalPrice) && $sale->totalPrice != 0) throw new Exception("Envíe el precio total de la venta");
        return true;
    }
    // Altas y modificaciones
    public function newBudget() {
        $budget = $this->validateExistBudget();
        $this->validateNotEmptyBudget($budget);
        $budget->id     = $this->models['budgets']->newBudget($budget);
        $budget->number = $this->models['budgets']->newBudgetFirstVersion($budget->id); 
        $this->models['budgets']->newBudgetItems($budget->items, $budget->id);
        $msg = "Se dió de alta la cotización #$budget->number";
        return $msg;
    }
    public function newBudgetVersion() {
        $budget = $this->validateExistBudget();
        $this->validateNotEmptyBudget($budget);
        $budget->number     = $budget->info->budget_number;
        $budget->id         = $this->models['budgets']->newBudget($budget);
        $budget->version    = $this->models['budgets']->newBudgetLastVersion($budget->id, $budget->number); 
        $this->models['budgets']->newBudgetItems($budget->items, $budget->id);
        $msg = "Se dió de alta la cotización #$budget->number (v$budget->version)";
        return $msg;
    }
    public function newSale() {
        $sale = $this->validateExistSale();
        $this->validateNotEmptySale($sale);
        $this->models['stock']->validateStockItems($sale->items); // Acá arroja excepciones si no hay stock, la venta no se genera
        $sale->id = $this->models['sales']->newSale($sale);
        $this->models['sales']->newSaleItems($sale->items, $sale->id);
        $this->models['stock']->registerStockChanges($sale->id); // Acá se hacen los descuentos
        $msg = "Se dió de alta la venta #$sale->id";
        return $msg;
    }
    public function newBudgetToSale() { 
        /* Se toma el number que viene desde el front para filtrar por budget_number (budget_versions) */
        if(empty($_POST['number'])) throw new Exception("El número de la cotización está vacío o es inválido");
        if(!$this->models['budgets']->existNumber($_POST['number'], false)) { 
            header("Location: ./viewBudgets");
            exit();
        }    
        $budget = new stdClass();
        if(empty($_POST['version'])) {
            /* Se trae como cotización principal, siempre la última versión */
            $budget->info  = $this->models['budgets']->getLastVersionBudgetInfo($_POST['number']);
            $budget->items = $this->models['budgets']->getLastVersionBudgetItems($_POST['number']);
        }
        else {
            if(!$this->models['budgets']->existNumberVersion($_POST['number'], $_POST['version'])) { 
                /* Por default redirecciona a la última versión del número de cotización provisto */
                header("Location: ./viewBudget-".$_POST['number']);
                exit();
            }    
            $budget->info  = $this->models['budgets']->getNumberVersionBudgetInfo($_POST['number'], $_POST['version']); 
            $budget->items = $this->models['budgets']->getNumberVersionBudgetItems($_POST['number'], $_POST['version']); 
        }
        $budget->items = $this->arrayItemsToObject($budget->items);
        $this->models['stock']->validateStockItems($budget->items); // Acá arroja excepciones si no hay stock, la venta no se genera
        $sale     = new stdClass();
        $sale->id = $this->models['sales']->newSale2($budget->info); // Falta formatear la info. a objeto (hay que hacer nueva función de formateo, definir si en el modelo o controlador)
        $this->models['sales']->newSaleItems2($budget->items, $sale->id);
        $this->models['stock']->registerStockChanges($sale->id); // Acá se hacen los descuentos (hacer métodos mas pequeños en los modelos)
        return $sale;
    }
    public function changeSaleState() { 
        if(empty($_POST['sale_id'])) throw new Exception("La acción a realizar está vacía o es inválida");
        if(empty($_POST['action'])) throw new Exception("La acción a realizar está vacía o es inválida");
        $response = new stdClass();
        if(!$response->sale_id = $this->models['sales']->exist($_POST['sale_id'])) 
            throw new Exception("La venta no existe"); 
        $response->change = $this->models['sales']->changeSaleState($_POST['action'], $_POST['sale_id']);
        $response->successMsg = "Se cambió el estado de " . $response->change->action 
                                . " de la venta #" . sprintf("%'.04d\n", $response->sale_id) 
                                . " de " . $response->change->old_state 
                                . " a " . $response->change->new_state;
        return $response;
    }

    // Fixeado de acá para arriba

    private function sendMail($sale){
        if(empty($_SESSION['user_email'])) return false;

        $user = $_SESSION['user'];
        $email = $_SESSION['user_email'];
        if(!empty($sale['description'])){
            $coment = "Usted ha creado la venta Nro. " . $sale['sale_id'];
        }else{
            $coment = "Usted ha creado la venta Nro. " . $sale['sale_id'] . 
            ". Notas de la venta: " . $sale['description'];
        }

        $asunto="Venta generada";
        $mensaje="Usuario: " . $user . " Mensaje: " . $coment;

        $header="From: Quarz <noreply@quarz.com>";

        $enviado = mail($email,$asunto,$mensaje,$header);

        if($enviado == true){
            return true;
        }else{
            return false;
        }
    }
}

?>