<?php 

namespace App\Models\ModelFilters;

use EloquentFilter\ModelFilter;

class CategoryFilter extends ModelFilter
{

    public $relations = [];

    public function id($id)
    {
        return $this->find($id);

    }
   
}
