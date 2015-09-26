<?php namespace AWME\Stocket\Classes;

class Calculator{

	public static function format($n)
	{
		return number_format($n, 2, '.', '');
	}

	/**
	 * Suma
	 */
	public static function suma($n)
	{
		return array_sum($n);
	}

	/**
	 * Suma
	 */
	public static function resta($a,$b)
	{
		return (array_sum($a) - array_sum($b));
	}

	public static function percent($a,$b){

		return ($a * $b)/100;
	}
	/**
	 * Multiply
	 */
	public static function multiply($a, $b)
	{
		return ($a * $b);
	}

	
}