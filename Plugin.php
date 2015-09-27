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
            'description' => 'The eCommerce plugin that lets you set up an online/local shop with ease.',
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
                'label'       => 'Stock',
                'url'         => Backend::url('awme/stocket/sales'),
                'icon'        => 'icon-shopping-cart',
                'permissions' => ['awme.stocket.*'],
                'order'       => 300,

                'sideMenu' => [

                    'sales' =>   [
                        'label'       => 'Sales',
                        'icon'        => 'icon-cart-plus',
                        'url'         => Backend::url('awme/stocket/sales'),
                        'permissions' => ['awme.stocket.*'],
                    ],

                    'products' =>   [
                        'label'       => 'Products',
                        'icon'        => 'icon-cubes',
                        'url'         => Backend::url('awme/stocket/products'),
                        'permissions' => ['awme.stocket.read_products'],
                    ],

                    'categories' =>   [
                        'label'       => 'Categories',
                        'icon'        => 'icon-folder',
                        'url'         => Backend::url('awme/stocket/categories'),
                        'permissions' => ['awme.stocket.read_categories'],
                    ],
                ]
            ],
        ];
    }

}
