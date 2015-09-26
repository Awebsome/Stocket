<?php namespace AWME\Stocket\Models;

use Model;
use Carbon\Carbon;

/**
 * ItemSale Model
 */
class ItemSale extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'awme_stocket_items_sales';

    /**
     * @var array Guarded fields
     */
    //protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['*'];

    public function product()
    {
        return $this->hasOne('Product', 'product_id');
    }
}
