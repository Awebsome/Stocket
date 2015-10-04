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

    public function beforeCreate()
    {
        if (!CashRegister::is_open()) {
            throw new ValidationException([
               'please_opening_cash_register' => trans('awme.stocket::lang.sales.please_opening_cash_register')
            ]);
        }
    }

    public function beforeUpdate()
    {
        if (Self::find($this->id)->status == 'closed') {
            throw new ValidationException([
               'sale_is_closed' => trans('awme.stocket::lang.sales.sale_is_closed'),
            ]);
        }
    }

    public function beforeDelete()
    {
        Till::where('action', trans('awme.stocket::lang.tills.sale'))->where('op_id', $this->id)->delete();
    }
}