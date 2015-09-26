<?php namespace AWME\Stocket\Models;

use Model;
use Carbon\Carbon;

/**
 * Product Model
 */
class ProdCat extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'awme_stocket_prod_cat';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];
}
