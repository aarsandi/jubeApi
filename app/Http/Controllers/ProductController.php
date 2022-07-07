<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\ProductTransformer;
use App\Transformers\ProductDetailTransformer;
use App\Repositories\Contracts\IProductRepository;

class ProductController extends Controller
{
    private $repo;
    public function __construct(IProductRepository $repo) {
        $this->repo = $repo;
    }
    
    public function index() {
        try {
            $filter = request('q');
            $limit = request('limit');
            $page = request('page', 1);
            $user_id = request('user_id');
            $category_id = request('category_id');
            $order = request('order_by', 'created_date');
            $sort = request('sort_by', 'asc');

            if($limit){
                $result = $this->repo->getProduct($order, $sort, $filter, $page, $limit, $category_id, $user_id);
                return $this->buildFilterResponse($result, new ProductTransformer(), 'success', 'products');
            }else{
                $result = $this->repo->getProductAll($order, $sort, $filter, $category_id, $user_id);
                return $this->buildFilterNoMetaResponse($result, new ProductTransformer(), 'success', 'products');
            }

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
    
    public function find($id) {
        try {
            $result = $this->repo->findProduct($id);
            return $this->buildFilterNoMetaResponse($result, new ProductDetailTransformer(), 'success', 'products');

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
}
