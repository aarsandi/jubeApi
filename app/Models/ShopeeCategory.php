<?php   
namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ShopeeCategory extends Model
{

    protected $table = "product_category_shopee";
    public $timestamps = false;

    protected $fillable = [
        'product_category_id',
        'category_id',
        'product_category_name',
        'category_slug',
        'parent',
        'image',
        'sort_number',
        'status',
    ];
    
    protected $defaultSort = 'asc';

    public function getSortDirection()
    {
        return $this->defaultSort;
    }

}