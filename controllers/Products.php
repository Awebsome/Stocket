<?php namespace AWME\Stocket\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

use AWME\Stocket\Models\Product;
use AWME\Stocket\Models\ProdCat;

/**
 * Products Back-end Controller
 */
class Products extends Controller
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

        BackendMenu::setContext('AWME.Stocket', 'stocket', 'products');

        $this->addCss($this->assetsPath.'/css/modal-form.css');
        $this->addJs($this->assetsPath.'/js/product-form.js');
    }

    public function resetProductsSku(){

        $products = Product::all();

        foreach ($products as $prod => $value) {
            
            $categories = ProdCat::where('product_id', $value->id)->first();
            
            if($categories)
            {
                $category = $categories->category_id;
            }else $category = '0';
            
            $product = Product::find($value->id);
            $product->sku = str_pad($category, 5, "0", STR_PAD_LEFT).'-'.str_pad($value->id, 5, "0", STR_PAD_LEFT);
            $product->save();
        }
    }
}
