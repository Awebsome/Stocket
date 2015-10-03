<?php namespace AWME\Stocket\Models;

use Model;
use BackendAuth;
/**
 * Till Model
 */
class Till extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'awme_stocket_tills';

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
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Set the Action field
     *
     * @param  string  $value
     * @return string
     */
    public function setActionAttribute($value)
    {
        $this->attributes['action'] = trans('awme.stocket::lang.tills.'.$value);
    }

    /**
     * Set the user's Seller
     *
     * @param  string  $value
     * @return string
     */
    public function setSellerAttribute($value)
    {
        $this->attributes['seller'] = BackendAuth::getUser()->first_name;
    }
}