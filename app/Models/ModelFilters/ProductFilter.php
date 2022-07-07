<?php 

namespace App\Models\ModelFilters;

use EloquentFilter\ModelFilter;

class ProductFilter extends ModelFilter
{

    public $relations = [];

    public function id($id)
    {
        return $this->find($id);

    }
    
    public function name($name)
    {
        return $this->where('product_name', 'like', '%' . $name . '%');
    }
    
    public function category($category_id)
    {
        return $this->where('product.category_id', '=',$category_id);
    }
    
    public function user($user_id)
    {
        return $this->where('product.user_id', '=',$user_id);
    }
    
    public function product($product_id)
    {
        return $this->where('product.product_id', '=',$product_id);
    }
    
    public function image($image)
    {
        return $this->where('product_image', '!=','');
    }
    
    public function deleted($deleted)
    {
        return $this->where('product.is_deleted', '=',$deleted);
    }
    
   
}
