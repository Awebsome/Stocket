<?php namespace AWME\Stocket\Controllers;

use Flash;
use Request;
use Redirect;
use Backend;
use BackendAuth;
use BackendMenu;
use ApplicationException;

use Backend\Classes\Controller;
use AWME\Stocket\Models\Sale;
use AWME\Stocket\Models\ItemSale;
use AWME\Stocket\Models\Product;
use AWME\Stocket\Models\Till;

use AWME\Stocket\Classes\Calculator as Calc;
use AWME\Stocket\Classes\Invoice;
use AWME\Stocket\Classes\Widget;

/**
 * Sales Back-end Controller
 */
class Sales extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $bodyClass = 'compact-container';

    protected $assetsPath = '/plugins/awme/stocket/assets';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('AWME.Stocket', 'stocket', 'sales');


        $this->addCss($this->assetsPath.'/css/modal-form.css');
        $this->addJs($this->assetsPath.'/js/product-form.js');
    }

    public function index()
    {
        $Widget = new Widget;

        $this->vars['widget'] = $Widget->getAll();

        // Call the ListController behavior index() method
        $this->asExtension('ListController')->index();
    }

    /**
     * Update
     */
    public function update($recordId = null, $context = null)
    {
        $Invoice = new Invoice;
        $Invoice->saleId = $recordId;

        $Sale = Sale::find($recordId);

        $this->vars['invoice'] = $Invoice->get();
        $this->vars['invoiceStatus'] = $Sale->status;
        $this->asExtension('FormController')->update($recordId, $context);
    }


    /**
     * Recalculate
     */
    public function onRecalculate($recordId = null, $context = null)
    {
        $Invoice = new Invoice;
        $Invoice->saleId = $recordId;
        $Invoice->opRecalculate();
        
        $this->vars['invoice'] = $Invoice->get();
        $this->vars['invoiceStatus'] = Sale::find($recordId)->status;
        $this->asExtension('FormController')->update($recordId, $context);
    }

    public function onCheckout($recordId = null, $context = null)
    {
        $Invoice = new Invoice;
        $Invoice->saleId = $recordId;
        
        $Invoice->setStock(); # Descontar en stock

        $Invoice->close(); # Cerrar venta

        return Redirect::to(Backend::url('awme/stocket/sales'));
    }
}

