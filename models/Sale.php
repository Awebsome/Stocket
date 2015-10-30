<?php namespace AWME\Stocket\Models;

use Model;
use Carbon\Carbon;

use Flash;
use ValidationException;

use AWME\Stocket\Models\Till;
use AWME\Stocket\Classes\Invoice;
use AWME\Stocket\Classes\CashRegister;
/**
 * Sale Model
 */
class Sale extends Model
{
    use \October\Rain\Database\Traits\Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'awme_stocket_sales';

    /**
     * @var array Validation rules
     */
    protected $rules = [
        'fullname' => ['required', 'between:4,255'],
        'invoice' => [
            'between:1,45',
            'unique:awme_stocket_sales'
        ],
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];


    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'products' => ['AWME\Stocket\Models\Product',
            'table' => 'awme_stocket_items_sales',
            'order' => 'title',
        ],
    ];

    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Set the Payment field
     *
     * @param  string  $value
     * @return string
     */
    public function setPaymentAttribute($value)
    {
        $this->attributes['payment'] = trans('awme.stocket::lang.invoice.'.$value);
    }

    /**
     * Validar si la caja esta abierta,
     * antes de crear una venta.
     * @return [type] [description]
     */
    public function beforeCreate()
    {
        if (!CashRegister::is_open()) {
            throw new ValidationException([
               'please_opening_cash_register' => trans('awme.stocket::lang.sales.please_opening_cash_register')
            ]);
        }
    }

    /**
     * valida que el invoice no este cerrado
     * antes del update.
     * @return [type] [description]
     */
    public function beforeUpdate()
    {
        if (Self::find($this->id)->status == 'closed') {
            throw new ValidationException([
               'sale_is_closed' => trans('awme.stocket::lang.sales.sale_is_closed'),
            ]);
        }
    }

    /**
     * borra la venta correspondiente de la caja
     * @return [type] [description]
     */
    public function beforeDelete()
    {
        Till::where('action', trans('awme.stocket::lang.tills.sale'))->where('op_id', $this->id)->delete();
    }

    /**
     * Muestra solo las ventas abiertas
     */
    public function scopeOpenOnly($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Muestra solo las ventas abiertas
     */
    public function scopeToday($query)
    {
        return $query->where('created_at','>=', '2015-10-03 19:34:28');
    }

    

    /**
     * Filtros por fecha
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeShowYear($query)
    {
        $show = date("Y");
        $date = $show.'-01-01 00:00:00';
        return $query->where('created_at','>=', $date);
    }

    public function scopeShowMonth($query)
    {
        $show = date("Y-m");
        $date = $show.'-01 00:00:00';
        return $query->where('created_at','>=', $date);
    }

    public function scopeShowWeek($query)
    {
        $show = date("Y-m-d", strtotime('-1 week'));
        $date = $show.' 00:00:00';
        return $query->where('created_at','>=', $date);
    }
 
    public function scopeShowToday($query)
    {
        $show = date("Y-m-d");
        $date = $show.' 00:00:00';
        return $query->where('created_at','>=', $date);
    }




    public function scopePayment($query, $categories)
    {
        /*return $query->whereHas('categories', function($q) use ($categories) {
            $q->whereIn('id', $categories);
        });*/
    }

}