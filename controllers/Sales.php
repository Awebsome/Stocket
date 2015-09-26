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
        /**
         * ItemsSale List
         */
        $ListItemsSale = ItemSale::where('sale_id', $recordId)->get();
        
        
        foreach($ListItemsSale as $item => $attr):
            
            //Item Sale
            $ItemSale = ItemSale::find($attr['id']);
            
            //Product attrs by ItemSale
            $product = Product::find($ItemSale->product_id);

            $ItemSale->qty = ($ItemSale->qty < 1) ? 1 : $ItemSale->qty;
            $ItemSale->sale_price = $product->price;
            $ItemSale->subtotal = Calc::multiply($product->price, $ItemSale->qty);
            $ItemSale->save();

        endforeach;


        $this->vars['invoice'] = Invoice::make($recordId);

        $this->asExtension('FormController')->update($recordId, $context);
    }


    /**
     * Recalculate
     */
    public function onRecalculate($recordId = null, $context = null)
    {
        /**
         * Recalculate Invoice QTY ItemsSale List
         */
        if(Request::input('qty')):
            foreach(Request::input('qty') as $item => $qty):            
                //Item Sale
                $ItemSale = ItemSale::find($item);
                
                //Product attrs by ItemSale
                $product = Product::find($ItemSale->product_id);

                $ItemSale->qty = $qty;
                $ItemSale->sale_price = $product->price;
                $ItemSale->subtotal = Calc::multiply($product->price, $qty);
                $ItemSale->save();
            endforeach;
        endif;

        /**
         * Recalculate :
         * - Taxes
         * - Subtotal
         * - Total
         */
        $Sale = Sale::find($recordId);
        $Sale->subtotal = Invoice::opSubtotal(Invoice::itemList($recordId));
        $Sale->save();

        /**
         * Invoice Item List
         */
        $this->vars['invoice'] = Invoice::make($recordId);
        $this->asExtension('FormController')->update($recordId, $context);


        Flash::info("Invoice Recalculated" );
    }
}

