<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductPatchRequest;
use App\Transformers\Management\ProductTransformer;
use App\Transformers\Management\ProductDetailTransformer;
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
            $user_id = Auth::user()->user_id;
            $category_id = request('category_id');
            $order = request('order_by', 'created_date');
            $sort = request('sort_by', 'desc');

            if($limit){
                $result = $this->repo->getProduct_M($order, $sort, $filter, $page, $limit, $category_id, $user_id);
                return $this->buildFilterResponse($result, new ProductTransformer(), 'success', 'products');
            }else{
                $result = $this->repo->getProductAll_M($order, $sort, $filter, $category_id, $user_id);
                return $this->buildFilterNoMetaResponse($result, new ProductTransformer(), 'success', 'products');
            }

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
    
    public function show() {
        try {
            $result = $this->repo->getProductFormInfo();
            return $this->buildResponse('success' , $result);

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
    
    public function store(Request $request) {
        // try {
            $patch = new ProductRequest($request->all());
            $credentials = $patch->parse();

            if ($credentials){
                $result = $this->repo->createProduct($credentials);
                if ($result){
                    return $this->buildResponse('success' , 'Product Created!');
                } else {
                    return $this->buildErrorResponse('error' , 'User Not Found', 404);
                }
            }
        // } catch (\Exception $exception) {
        //     return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        // }
    } 
    
    public function edit($id) {
        try {
            $result = $this->repo->getProductInfo($id);
            return $this->buildFilterNoMetaResponse($result, new ProductDetailTransformer(), 'success', 'products');

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 

    public function update(Request $request) {
        // try {
            $patch = new ProductPatchRequest($request->all());
            $credentials = $patch->parse();
            if ($credentials){
                $result = $this->repo->updateProduct($credentials);
                if ($result){
                    return $this->buildResponse('success' , 'Product Updated!');
                } else {
                    return $this->buildErrorResponse('error' , 'User Not Found', 404);
                }
            }
        // } catch (\Exception $exception) {
        //     return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        // }
    } 
    
    public function destroy($id) {
        try {
            $result = $this->repo->deleteProduct($id);
            if ($result){
                return $this->buildResponse('success' , 'Product Deleted!');
            } else {
                return $this->buildErrorResponse('error' , 'User Not Found', 404);
            }
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
    
}
