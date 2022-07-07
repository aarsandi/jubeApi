<?php

namespace App\Repositories;

use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\IProductRepository;

class ProductRepository extends GenericRepository implements IProductRepository
{
    public function __construct()
    {
        parent::__construct(app(Product::class));
    }
    
    public function getProduct($order, $sort, $filter, $page, $limit, $category_id, $user_id)
    {        
        $orderBy = $order ? $order : $this->model->getKeyName();
        $sortBy = $sort ? $sort : $this->model->getSortDirection();

        $data = [
            'category' =>  $category_id,
            'user' =>  $user_id,
            'name' =>  $filter,
            'deleted' =>  0,
            'image' =>  1,
        ];

        return $this->model->filter($data)
            ->select('product.*','category.category_name')
            ->join('category', 'category.category_id', '=', 'product.category_id')
            ->orderBy($orderBy, $sortBy)
            ->limit($limit)
            ->paginate($limit);
    }
    
    public function getProductAll($order, $sort, $filter, $category_id, $user_id)
    {
        $orderBy = $order ? $order : $this->model->getKeyName();
        $sortBy = $sort ? $sort : $this->model->getSortDirection();

        $data = [
            'category' =>  $category_id,
            'user' =>  $user_id,
            'name' =>  $filter,
            'deleted' =>  0,
            'image' =>  1,
        ];

        return $this->model
        ->select('product.*','category.category_name')
        ->join('category', 'category.category_id', '=', 'product.category_id')
        ->filter($data)
        ->orderBy($orderBy, $sortBy)->get();
    }

    public function findProduct($id)
    {        
        $data = [
            'product' =>  $id,
            'deleted' =>  0,
        ];

        return $this->model->filter($data)
            ->select('product.*','category.category_name', 'category.category_id', 'product_unit.product_unit_name', 'user.user_id', 'user.full_name', 'user.catalog_name')
            ->join('user', 'user.user_id', '=', 'product.user_id')
            ->join('category', 'category.category_id', '=', 'product.category_id')
            ->join('product_unit', 'product_unit.product_unit_id', '=', 'product.product_unit')
            ->get();
    }


    // ADMIN MANAGEMENT
        
    public function getProduct_M($order, $sort, $filter, $page, $limit, $category_id, $user_id)
    {        
        $orderBy = $order ? $order : $this->model->getKeyName();
        $sortBy = $sort ? $sort : $this->model->getSortDirection();

        $data = [
            'category' =>  $category_id,
            'user' =>  $user_id,
            'name' =>  $filter,
            'deleted' =>  0,
            'image' =>  1,
        ];

        return $this->model->filter($data)
            ->select('product.*','seller.seller_name','seller.store_name', 'product_unit.product_unit_name')
            ->join('seller', 'seller.seller_guid', '=', 'product.seller_guid')
            ->join('product_unit', 'product_unit.product_unit_id', '=', 'product.product_unit')
            ->orderBy($orderBy, $sortBy)
            ->limit($limit)
            ->paginate($limit);
    }
    
    public function getProductAll_M($order, $sort, $filter, $category_id, $user_id)
    {
        $orderBy = $order ? $order : $this->model->getKeyName();
        $sortBy = $sort ? $sort : $this->model->getSortDirection();

        $data = [
            'category' =>  $category_id,
            'user' =>  $user_id,
            'name' =>  $filter,
            'deleted' =>  0,
            'image' =>  1,
        ];

        return $this->model
        ->select('product.*','seller.seller_name','seller.store_name', 'product_unit.product_unit_name')
        ->join('seller', 'seller.seller_guid', '=', 'product.seller_guid')
        ->join('product_unit', 'product_unit.product_unit_id', '=', 'product.product_unit')
        ->filter($data)
        ->orderBy($orderBy, $sortBy)->get();
    }
    
    public function getProductInfo($id)
    {        
        $data = [
            'product' =>  $id,
            'deleted' =>  0,
        ];

        return $this->model->filter($data)->get();
    }
    
    public function getProductFormInfo()
    {        
        $sellers = Seller::getSellerList();
        $categories = Category::getCategoryList();
        $product_units = ProductUnit::getUnitList();

        $data = [
            'sellers' => $sellers,
            'categories' => $categories,
            'product_units' => $product_units,
        ];

        return $data;
    }
    
    public function createProduct($data)
    {        
        if($data['image_exist']){
            $data_image = array($data['product_image_1'], $data['product_image_2'], $data['product_image_3'], $data['product_image_4'],$data['product_image_5']);
            $image_name = $this->saveImage($data_image, 'products');
            $data['product_image'] = $image_name;
        }
        $product = $this->model->create($data);
        return $product;
    }
    
    public function updateProduct($data)
    {        
        if($data['image_exist']){
            $delete = $this->deleteImage($data['product_id']);
            $data_image = array($data['product_image_1'], $data['product_image_2'], $data['product_image_3'], $data['product_image_4'],$data['product_image_5']);
            $image_name = $this->uploadImage($data_image, 'products');
            $data['product_image'] = $image_name;
            $data['image_exist'] = null;
            $data['product_image_1'] = null;
            $data['product_image_2'] = null;
            $data['product_image_3'] = null;
            $data['product_image_4'] = null;
            $data['product_image_5'] = null;
        }
        $data_product = array_filter($data, function ($value) { return $value !== null; });
        $product = $this->model->where('product_id',$data['product_id'])->update($data_product);
        return $product;
    }
    
    public function deleteProduct($product_id)
    {       
        $data_product = array('is_deleted'=>1 ,'modified_user'=>Auth::user()->user_id,'modified_date'=> date('Y-m-d H:i:s'));
        $product = $this->model->where('product_id',$product_id)->update($data_product);
        return $product;
    }
    
    public function deleteImage($product_id)
    {
        $product_image = $this->model->where('product_id', $product_id)->get()[0]->product_image;
        $explode = explode(",", $product_image);
        foreach($explode as $key => $value){
            if(file_exists(rtrim(app()->basePath('public/storage/images/products/'.$value)))){       
                File::delete(rtrim(app()->basePath('public/storage/images/products/'.$value)));
            }
        }
        return true;
    }
    
}