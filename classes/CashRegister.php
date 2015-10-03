<?php namespace AWME\Stocket\Classes;

use BackendAuth;

use AWME\Stocket\Models\Till;
use AWME\Stocket\Classes\Calculator as Calc;

/**
* 
*/
class CashRegister
{
	/**
	 * -------------------------------------------------------------------
	 * Open
	 * -------------------------------------------------------------------
	 * Abir caja
	 * 
	 */
	public function open()
	{
        $Till = new Till;
        $Till->action = 'opening_till';
        $Till->seller = BackendAuth::getUser()->first_name;
        $Till->cash = 0;
        $Till->credit_card = 0;
        $Till->total = 0;
        $Till->till = $this->tillOnLastClosing();
        $Till->save();
	}


	public static function getActionTrans($trans)
	{
		return trans('awme.stocket::lang.tills.'.$trans);
	}

	/**
	 * -------------------------------------------------------------------
	 * getLastOpen
	 * -------------------------------------------------------------------
	 * Obtener datos de la ultima apertura de caja.
	 * 
	 * @return array $lastOpen
	 */
    public static function getLastOpen()
    {
    	$lastOpen = Till::where('action', Self::getActionTrans('opening_till'))->orderBy('created_at', 'desc')->first();
		return $lastOpen;
    }

	/**
	 * -------------------------------------------------------------------
	 * getLastClose
	 * -------------------------------------------------------------------
	 * Obtener datos del ultimo cierre de caja.
	 * 
	 * @return array $lastClose
	 */
	public static function getLastClose()
	{
		$lastClose = Till::where('action', Self::getActionTrans('closing_till'))->orderBy('created_at', 'desc')->first();
       	return $lastClose;
	} 



	public static function getLastSales()
	{
		/**
		 * Total de depositos
		 * @var decimal
		 */
		$lastOpen = Self::getLastOpen()->toArray(); #$lastOpen['created_at']

		$sales = Till::where('action', Self::getActionTrans('sale'))
						->where('created_at','>=', $lastOpen['created_at'])->get()->toArray();
		$sales['last_open'] = $lastOpen;
		return $sales;
	}



	/**
	 * [is_open description]
	 * @return boolean [description]
	 */
	public static function is_open()
	{
		$lastOpen = Self::getLastOpen();
		$lastClose = Self::getLastClose();
		
		if(empty($lastOpen)){
			$status = false;
		}else {

			$status = true;

			if(!empty($lastClose))
			{
				if($lastClose->id > $lastOpen->id)
					$status = false;
			}

		}

		return $status;
	}

	public static function tillOnLastClosing()
	{
		$Tills = Till::where('action',Self::getActionTrans('closing_till'))->orderBy('created_at', 'desc')->first();
		
		if(empty($Tills->till))
			$till = 0.00;
		else $till = $Tills->till;

		return $till;
	}

	public static function onClosing()
	{

		$getLastOpen = Self::getLastOpen()->toArray(); #$lastOpen['created_at']
		$lastOpen = $getLastOpen['created_at'];

		/**
		 * Total de depositos
		 * @var decimal
		 */
		$deposites = Till::where('action', Self::getActionTrans('deposit'))->where('created_at','>=', $lastOpen)->get()->toArray();
		$deposites = array_sum(array_column($deposites, 'cash'));
		
		/**
		 * Total de Ventas
		 * @var decimal
		 */
		$cash_sales = Till::where('action', Self::getActionTrans('sale'))->where('created_at','>=', $lastOpen)->get()->toArray();
		$cash_sales = array_sum(array_column($cash_sales, 'cash'));

		/**
		 * Total de Ventas
		 * @var decimal
		 */
		$credit_sales = Till::where('action', Self::getActionTrans('sale'))->where('created_at','>=', $lastOpen)->get()->toArray();
		$credit_sales = array_sum(array_column($credit_sales, 'credit_card'));
		
		/**
		 * Total de Retiros
		 * @var decimal
		 */
		$withdrawals = Till::where('action', Self::getActionTrans('withdrawal'))->where('created_at','>=', $lastOpen)->get()->toArray();
		$withdrawals = array_sum(array_column($withdrawals, 'cash'));

		$till = [
			'total_in_open_till' => $getLastOpen['till'],		# total de depositos hechos
			'total_deposites' => $deposites,		# total de depositos hechos
			'total_cash_sales' => $cash_sales,		# total de ventas en efectivo
			'total_credit_sales' => $credit_sales,	# total de ventas en tarjeta
			'total_witdrawls' => $withdrawals,		# total de retiros de dinero
			'total_all_sales' => Calc::suma([$cash_sales, $credit_sales]),	# total de todas las ventas
			'total_till'	=> Calc::resta([$cash_sales, $deposites, $getLastOpen['till']], [$withdrawals, $credit_sales]), # lo que queda en caja
		];

		return (object) $till;
	}

	public function close()
	{
        $Till = new Till;
        $Till->action 		= 'closing_till';
        $Till->seller 		= BackendAuth::getUser()->first_name;
        $Till->cash 		= $this->onClosing()->total_cash_sales;
        $Till->credit_card 	= $this->onClosing()->total_credit_sales;
        $Till->total 		= $this->onClosing()->total_all_sales;
        $Till->till 		= $this->onClosing()->total_till;
        $Till->save();
	}
}