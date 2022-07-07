<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\MarketProduct;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\ICategoryRepository;

class CategoryRepository extends GenericRepository implements ICategoryRepository
{
    public function __construct()
    {
        parent::__construct(app(Category::class));
    }
}