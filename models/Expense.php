<?php namespace AWME\Stocket\Models;

use Model;

/**
 * Expense Model
 */
class Expense extends Model
{

    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var string The database table used by the model.
     */
    public $table = 'awme_stocket_expenses';
    
    /**
     * @var array Validation rules
     */
    protected $rules = [
        'title' => ['required', 'between:3,255'],
        'amount' => [
            'required',
            'numeric'
        ],
        'expiration' => ['date'],
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
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}