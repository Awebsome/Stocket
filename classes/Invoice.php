<?php namespace AWME\Stocket\Classes;

use AWME\Stocket\Classes\Calculator;

use AWME\Stocket\Models\Product;
use AWME\Stocket\Models\Sale;
use AWME\Stocket\Models\ItemSale;

class Invoice{

	/**
	 * Make Invoice / Item List & Total
	 * @param  [array] $saleId
	 * @return [array]        
	 */
	public static function make($saleId)
	{
		$invoice['items'] = Self::itemList($saleId);
		$invoice['subtotal'] = Sale::find($saleId)->subtotal;

		return $invoice;
	}
	
	/**
	 * ItemList
	 * @param  int $saleId [get recordId]
	 * @return array       [items Sale]
	 */
	public static function itemList($saleId)
	{
		/**
         * ListItemsSale
         */
        $ListItemsSale = ItemSale::where('sale_id', $saleId)->get();
        
        foreach ($ListItemsSale as $item => $attr) {
            $ListItemsSale[$item]['product'] = Product::find($attr['product_id']);
        }

        return $ListItemsSale;
	}

	/**
	 * opSubtotal
	 * @param  array $itemList 	[get suma "subtotal" from ItemList]
	 * @return int          	[Suma]
	 */
	public static function opSubtotal($itemList)
	{
		foreach ($itemList as $key => $value) {
            $operation[$key] = $value['subtotal'];
        }

        if(!isset($operation))
        	$operation = [];
        
        return Calculator::suma($operation);
	}
}