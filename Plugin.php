<?php namespace AWME\Stocket;

use App;
use Backend;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;

/**
 * Shop Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Stocket',
            'description' => 'awme.stocket::lang.plugin.description',
            'author'      => 'AWME/LucasZdv',
            'icon'        => 'icon-shopping-cart'
        ];
    }

    public function boot()
    {
        // Register service providers
        App::register('\Gloudemans\Shoppingcart\ShoppingcartServiceProvider');

        // Register facades
        $facade = AliasLoader::getInstance();
        $facade->alias('Cart', '\Gloudemans\Shoppingcart\Facades\Cart');
    }

    /**
     * Register Permissions
     * 
     */
    public function registerPermissions()
    {
        return [
            'awme.stocket.read_products'   => ['label' => "Manage the shop's products"],
            'awme.stocket.access_products'   => ['label' => "Manage the shop's products"],
            'awme.stocket.access_categories' => ['label' => "Manage the shop categories"],
        ];
    }
    

    public function registerComponents()
    {
        return [
            'AWME\Stocket\Components\Basket' => 'shopBasket',
            'AWME\Stocket\Components\Categories' => 'shopCategories',
            'AWME\Stocket\Components\Product' => 'shopProduct',
            'AWME\Stocket\Components\ProductList' => 'shopProductList',
        ];
    }

    public function registerNavigation()
    {
        return [
            'stocket' => [
                'label'       => 'awme.stocket::lang.plugin.name',
                'url'         => Backend::url('awme/stocket/sales'),
                'icon'        => 'icon-shopping-cart',
                'permissions' => ['awme.stocket.*'],
                'order'       => 300,

                'sideMenu' => [
                    'sales' =>   [
                        'label'       => 'awme.stocket::lang.sales.menu_label',
                        'icon'        => 'icon-cart-plus',
                        'url'         => Backend::url('awme/stocket/sales'),
                        'permissions' => ['awme.stocket.*'],
                    ],

                    'tills' =>   [
                        'label'       => 'awme.stocket::lang.tills.menu_label',
                        'icon'        => 'icon-money',
                        'url'         => Backend::url('awme/stocket/tills'),
                        'permissions' => ['awme.stocket.*'],
                    ],
                    
                    'products' =>   [
                        'label'       => 'awme.stocket::lang.products.menu_label',
                        'icon'        => 'icon-cubes',
                        'url'         => Backend::url('awme/stocket/products'),
                        'permissions' => ['awme.stocket.read_products'],
                    ],

                    'categories' =>   [
                        'label'       => 'awme.stocket::lang.categories.menu_label',
                        'icon'        => 'icon-folder',
                        'url'         => Backend::url('awme/stocket/categories'),
                        'permissions' => ['awme.stocket.read_categories'],
                    ],
                ]
            ],
        ];
    }

}
