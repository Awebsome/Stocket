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

        $this->vars['formModel'] = Sale::find($recordId);
        
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

        $this->vars['formModel'] = Sale::find($recordId);
        
        $this->vars['invoice'] = $Invoice->make();
        $this->asExtension('FormController')->update($recordId, $context);

        Flash::info(trans('awme.stocket::lang.sales.sale_recalculate'));
    }

    public function onCheckout($recordId = null, $context = null)
    {
        $Invoice = new Invoice;
        $Invoice->saleId = $recordId;
        $Invoice->opStock();

        $Sale = Sale::find($recordId);
        
        $Till = new Till;
        $Till->action = 'sale';
        $Till->seller = BackendAuth::getUser()->first_name;

        if($Sale->payment == 'cash')
            $Till->cash = $Sale->total; 
        else $Till->credit_card = $Sale->total;

        $Till->save();


        Flash::success(trans('awme.stocket::lang.sales.sale_successfully'));

        //Redirect To Sale
        return Redirect::to(Backend::url('awme/stocket/sales'));
    }
}

