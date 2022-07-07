<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\CategoryTransformer;
use App\Repositories\Contracts\ICategoryRepository;

class CategoryController extends Controller
{
    private $repo;
    public function __construct(ICategoryRepository $repo) {
        $this->repo = $repo;
    }
    
    public function index() {
        try {
            $limit = request('limit');
            $page = request('page', 1);
            
            if($limit){
                $result = $this->repo->get(null, null, null, $page, $limit);
                return $this->buildFilterResponse($result, new CategoryTransformer(), 'success', 'categories');
            }else{
                $result = $this->repo->getAll(null, null, null);
                return $this->buildFilterNoMetaResponse($result, new CategoryTransformer(), 'success', 'categories');
            }
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
}
