<?php namespace AWME\Stocket\Classes;

use BackendAuth;
use AWME\Stocket\Models\Product;
use AWME\Stocket\Models\Expense;
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
        $widget['total_expenses'] = $this->getExpenses();
        $widget['covered_expenses'] = round($this->getCoveredExpenses());

        return $widget;
    }
    
    /**
     * [getSales]
     * Estadisticas de formas de pago
     * @return [array] [data]
     */
    public function getSales()
    {
        
        $cash               = count(Sale::where('payment',trans('awme.stocket::lang.invoice.cash'))->get());
        $debit              = count(Sale::where('payment',trans('awme.stocket::lang.invoice.debit'))->get());
        $credit_card        = count(Sale::where('payment',trans('awme.stocket::lang.invoice.credit_card'))->get());
        $current_account    = count(Sale::where('payment',trans('awme.stocket::lang.invoice.current_account'))->get());
        $total              = count(Sale::all()->toArray());

        $sales = [
            'cash' => $cash,
            'debit' => $debit,
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
        # $current_year = date('Y').'-01-01 00:00:00';
        $current_month = date('Y-m').'-01 00:00:00';
        # $current_week = date("Y-m-d", strtotime('-1 week')).' 00:00:00';
        # $current_day = date('Y-m-d').' 00:00:00';*/
        
        $profit = array_column(Sale::where('created_at', '>=', $current_month)->where('status','closed')->get()->toArray(), 'total');
        return array_sum($profit);
    }

    /**
     * [getExpenses description]
     * Gastos totales
     * @return [type] [description]
     */
    public function getExpenses()
    {
        $current_month = date('Y-m').'-01 00:00:00';
        $expenses = array_column(Expense::where('created_at', '>=', $current_month)->get()->toArray(), 'amount');
        return array_sum($expenses);
    }

    public function getCoveredExpenses(){

        if($this->getProfit() >= $this->getExpenses()){
            $covered = 100;
        }else $covered = ($this->getProfit() / $this->getExpenses()) * 100;

        return @$covered;
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
        $Sales = @array_count_values($TopItemSales);

        /**
         * Ordena el array > to <
         */
        arsort($Sales);

        return array_slice($Sales, 0,3);
    }
}

?>