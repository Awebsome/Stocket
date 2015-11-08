<?php namespace AWME\Stocket\Models;

use Model;
use Carbon\Carbon;
use AWME\Stocket\Models\ProdCat;

/**
 * Product Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'awme_stocket_products';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules
     */
    protected $rules = [
        'title' => ['required', 'between:4,255'],
        'slug' => [
            'required',
            'alpha_dash',
            'between:1,255',
            'unique:awme_stocket_products'
        ],
        'model' => ['required', 'between:1,255'],
        'stock' => ['required','numeric'],
        'sku' => ['unique:awme_stocket_products'],
        'price' => ['required','numeric','min:00.25', 'max:99999999.99'],
    ];

    /**
     * @var array Attributes to mutate as dates
     */
    protected $dates = ['available_at', 'created_at', 'updated_at'];

    /**
     * Set the user's Seller
     *
     * @param  string  $value
     * @return string
     */
    public function setSkuAttribute($value)
    {
        if(!$value){
            $categories = ProdCat::where('product_id', $this->id)->first();
            
            if($categories)
            {
                $category = $categories->category_id;
            }else $category = '0';
            
            $this->attributes['sku'] = str_pad($category, 5, "0", STR_PAD_LEFT).'-'.str_pad($this->id, 5, "0", STR_PAD_LEFT);
        }else $this->attributes['sku'] = $value;
    }

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'categories' => [
            'AWME\Stocket\Models\Category',
            'table'    => 'awme_stocket_prod_cat',
            'key'      => 'product_id',
        ]
    ];

    /**
     * @var array Image attachments
     */
    public $attachMany = [
        'images' => ['System\Models\File']
    ];

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeAvailable($query)
    {
        return $this->enabled();
    }

    /**
     * Allows filtering for specifc categories
     *
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  array                     $categories List of category ids
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeFilterCategories($query, $categories)
    {
        return $query->whereHas('categories', function($q) use ($categories) {
            $q->whereIn('id', $categories);
        });
    }

    public function inStock()
    {
        if (!$this->is_stockable) {
            return true;
        }

        return $this->stock > 0;
    }

    public function outOfStock()
    {
        return !$this->inStock();
    }

    public function getSquareThumb($size, $image)
    {
        return $image->getThumb($size, $size, ['mode' => 'crop']);
    }

    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }
}
