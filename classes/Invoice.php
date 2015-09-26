<?php namespace AWME\Stocket\Classes;

use Request;

use AWME\Stocket\Classes\Calculator as Calc;

use AWME\Stocket\Models\Product;
use AWME\Stocket\Models\Sale;
use AWME\Stocket\Models\ItemSale;

class Invoice{
    
    function _construct(){
        $this->saleId = "";
    }

	/**
	 * Make Invoice / Item List & Total
	 * @param  [array] $saleId
	 * @return [array]        
	 */
	public function make()
	{
        $invoice['subtotal']    = Self::opSubtotal();
        $invoice['taxes']       = Self::opTaxes();
		$invoice['total']		= Self::opTotal($this->saleId);
        $invoice['items']       = Self::itemList();

		return $invoice;
	}
	
	/**
	 * ItemList
	 * @param  int $saleId [get recordId]
	 * @return array       [items Sale]
	 */
	public function itemList()
	{
        /**
         * itemList
         */
        $itemList = ItemSale::where('sale_id', $this->saleId)->get();
        
        if(Request::input('qty'))
            $requestQty = Request::input('qty');
        
        foreach ($itemList as $items => $item) {
            
            $ItemSale = ItemSale::find($item['id']);
            $product = Product::find($item['product_id']);

            if(isset($requestQty[$item['id']])){
                $ItemSale->qty = $requestQty[$item['id']];
                $item['qty'] = $requestQty[$item['id']];
            }

            if($item['sale_price'] <= 0)
            {
                $ItemSale->sale_price = $product->price;
                $item['sale_price'] = $product->price;
            }

            if($item['subtotal'] <= 0){
                $ItemSale->subtotal = $product->price;
                $item['subtotal'] = $product->price;
            }

            $ItemSale->save();

            $item['product'] = $product;
        }

        return $itemList;
	}

	/**
	 * opSubtotal
	 * @param  array $itemList 	[get suma "subtotal" from ItemList]
	 * @return int          	[Suma]
	 */
	public function opSubtotal()
	{

		/**
         * Recalculate Invoice QTY ItemsSale List
         */
		foreach ($this->itemList() as $items => $item) {
            
            $ItemSale = ItemSale::find($item['id']);
            $subtotal = Calc::multiply($ItemSale->sale_price, $ItemSale->qty);
            $ItemSale->subtotal = $subtotal;
            $ItemSale->save();
            
            $operation[$items] = $ItemSale->subtotal;
        }

        if(!isset($operation))
        	$operation = [];
        
        $Sale = Sale::find($this->saleId);
        $Sale->subtotal = Calc::suma($operation);
        $Sale->save();

        return $Sale->subtotal;
	}


    public function opTaxes()
    {
        $Sale = Sale::find($this->saleId);
        if(Request::input('tax')){

            $Sale->taxes = json_encode(Request::input('tax'));
            $Sale->save();
        }
        return json_decode($Sale->taxes);
    }

	public static function opTotal($saleId)
	{	
		/**
         * Recalculate :
         * - Taxes
         * - Subtotal
         * - Total
         */
        $Sale = Sale::find($saleId);
        $Sale->total = $Sale->subtotal;
        $Sale->save();

		return  $Sale->total;
	}
}