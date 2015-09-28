<?php namespace AWME\Stocket\Classes;

use BackendAuth;
use AWME\Stocket\Models\Product;
use AWME\Stocket\Models\Sale;
use AWME\Stocket\Models\ItemSale;

class Widget
{
    public function getCustomer()
    {
        $user = BackendAuth::getUser();

        return $user->first_name;
    }

    /**
     * ------------------------------------------------
     * getAll
     * ------------------------------------------------
     * - carga y obtiene todos los widgets de stats
     * 
     * @return array [Widget data in array]
     */
    public function getAll()
    {

        $widget = [];
        $widget['customer'] = $this->getCustomer();
        $widget['sale'] = $this->getSales();
        $widget['profit'] = $this->getProfit();
        $widget['top_item_sales'] = $this->getTopItemSales();

        return $widget;
    }
    
    /**
     * [getSales]
     * Estadisticas de formas de pago
     * @return [array] [data]
     */
    public function getSales()
    {
        
        $cash               = count(Sale::where('payment','cash')->get());
        $credit_card        = count(Sale::where('payment','credit_card')->get());
        $current_account    = count(Sale::where('payment','current_account')->get());
        $total              = count(Sale::all()->toArray());

        $sales = [
            'cash' => $cash,
            'credit_card' => $credit_card,
            'current_account' => $current_account,
            'total' => $total,
        ];

        return $sales;
    }
    /**
     * [getProfit]
     * Ingresos totales
     * @return float [data]
     */
    public function getProfit()
    {

        $profit = array_column(Sale::all()->toArray(), 'total');

        return array_sum($profit);
    }

    /**
     * ------------------------------------------------
     * getTopItemSales
     * ------------------------------------------------
     * - Muestra las categorias de producto mas vendidos.
     *
     * - Obtiene lista de items vendidos.
     * - Obtiene las categorias de los productos
     * @return [type] [description]
     */
    public function getTopItemSales()
    {
        $ItemSales = ItemSale::all()->toArray();

        $TopItemSales = [];

        foreach ($ItemSales as $item => $attr) {
            
            $Product = Product::find($attr['product_id']);
            $TopItemSales[$item] = @$Product->categories->first()->name;
        }

        /**
         * Cuenta los valores
         * @var array
         */
        $Sales = array_count_values($TopItemSales);

        /**
         * Ordena el array > to <
         */
        arsort($Sales);

        return array_slice($Sales, 0,3);
    }
}

?>