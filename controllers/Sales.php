<?php namespace AWME\Stocket\Controllers;

use Flash;
use Request;
use BackendMenu;
use ApplicationException;

use Backend\Classes\Controller;
use AWME\Stocket\Models\Sale;
use AWME\Stocket\Models\ItemSale;
use AWME\Stocket\Models\Product;

use AWME\Stocket\Classes\Calculator as Calc;
use AWME\Stocket\Classes\Invoice;

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

    /**
     * Update
     */
    public function update($recordId = null, $context = null)
    {
       
        $Invoice = new Invoice;
        $Invoice->saleId = $recordId;

        $this->vars['invoice'] = $Invoice->make();
        
        $this->asExtension('FormController')->update($recordId, $context);
    }


    /**
     * Recalculate
     */
    public function onRecalculate($recordId = null, $context = null)
    {
        $Invoice = new Invoice;
        $Invoice->saleId = $recordId;

        $this->vars['invoice'] = $Invoice->make();
        
        $this->asExtension('FormController')->update($recordId, $context);

        Flash::info("Invoice Recalculated" );
    }
}

