<?php

namespace App\Http\Controllers;

use Socialite;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use App\Transformers\CatalogTransformer;
use App\Http\Requests\UserPatchRequest;
use App\Repositories\Contracts\IUserRepository;

class UserController extends Controller
{
    private $repo;
    public function __construct(IUserRepository $repo){
        $this->repo = $repo;
    }

    public function index()
    {
        try {
            $user_id = auth()->user()->id;
            $result = $this->repo->find($user_id);
            return $this->buildCollectionNoMetaResponse($result, new UserTransformer(), 'success', 'user');

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
        
    }

    public function update(Request $request)
    {   
        $validate = new UserPatchRequest($request->all());
        $data = $validate->parse();
        $user_id = auth()->user()->id;
        try{
            $update = $this->repo->userUpdate($user_id, $data);
            $result = $this->repo->find($user_id);
            return $this->buildCollectionNoMetaResponse($result, new UserTransformer(), 'success', 'user');
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    }

    public function findCatalog($id)
    {   
        try{
            $result = $this->repo->find($id);
            return $this->buildCollectionNoMetaResponse($result, new CatalogTransformer(), 'success', 'user');
            
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    }

 
}
