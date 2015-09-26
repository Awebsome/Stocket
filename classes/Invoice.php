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
        $invoice['subtotal']    = Self::setSubtotal();
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
	 * setSubtotal
	 * @param  array $itemList 	[get suma "subtotal" from ItemList]
	 * @return int          	[Suma]
	 */
	public function setSubtotal()
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

    public function getSubtotal()
    {
        return Calc::format(Sale::find($this->saleId)->subtotal);
    }
    
    /**
     * ------------------------------------------------
     * Set Taxes
     * ------------------------------------------------
     *  - Inserta el los taxes en json en Model\Sale
     *
     * @return  array $taxes from RquestInput 
     */
    public function setTaxes(){

        $Sale = Sale::find($this->saleId);
        $taxes = Request::input('tax');
        if($taxes){
            $Sale->taxes = json_encode(Request::input('tax'));
            $Sale->save();
            
            return $taxes;
        }
    }

    /**
     * ------------------------------------------------
     * Get Taxes
     * ------------------------------------------------
     * - Obtener los taxes desde el modelo
     * 
     * @return array $taxes from Model\Sale
     */
    public function getTaxes()
    {
        $SaleTaxes = Sale::find($this->saleId)->taxes;
        $taxes = json_decode($SaleTaxes);

        return $taxes;
    }

    public function opTaxesDiscount()
    {
        $tax = $this->getTaxes();

        
            if(@array_key_exists('discount', $tax)){
                if($tax->discount->type == "$"){
                    $total = Calc::resta([$this->getSubtotal()], [$tax->discount->amount]); 
                }else if($tax->discount->type == "%"){
                    $total = Calc::resta([$this->getSubtotal()], [Calc::percent($tax->discount->amount,$this->getSubtotal())]); 
                }
            }else $total = 0;

            return Calc::format($this->getSubtotal() - $total);
        
    }


    
	public function opTotal()
	{	
		/**
         * Recalculate :
         * - Taxes
         * - Subtotal
         * - Total
         */
        $total = $this->getSubtotal() - $this->opTaxesDiscount();

        return Calc::format($total);
	}

    public function setTotal(){

        $Sale = Sale::find($this->saleId);
        $Sale->total = $this->opTotal;
        $Sale->save();
    }
}