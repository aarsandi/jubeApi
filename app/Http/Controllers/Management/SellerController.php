<?php

namespace App\Http\Controllers\Management;

use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankRequest;
use App\Http\Requests\BankPatchRequest;
use App\Http\Requests\SellerRequest;
use App\Http\Requests\SellerPatchRequest;
use App\Transformers\Management\SellerTransformer;
use App\Transformers\Management\SellerDetailTransformer;
use App\Repositories\Contracts\ISellerRepository;

class SellerController extends Controller
{
    private $repo;
    public function __construct(ISellerRepository $repo) {
        $this->repo = $repo;
    }
    
    public function index() {
        try {
            $filter = request('q');
            $limit = request('limit');
            $page = request('page', 1);
            $order = request('order_by', 'created_date');
            $sort = request('sort_by');

            if($limit){
                $result = $this->repo->getSellers($order, $sort, $filter, $page, $limit);
                return $this->buildFilterResponse($result, new SellerTransformer(), 'success', 'sellers');
            }else{
                $result = $this->repo->getSellersAll($order, $sort, $filter);
                return $this->buildFilterNoMetaResponse($result, new SellerTransformer(), 'success', 'sellers');
            }

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
    
    public function show($id) {
        try {
            $result = $this->repo->getSeller($id);
            return $this->buildFilterNoMetaResponse($result, new SellerDetailTransformer(), 'success', 'products');

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    }
    
    public function store(Request $request) {
        try {
            $patch = new SellerRequest($request->all());
            $credentials = $patch->parse();
            $bank_patch = $request->bank_name ? new BankRequest($request->all()) : null;
            $bank_credentials = $request->bank_name ? $bank_patch->parse() : null;

            if ($credentials){
                $result = $this->repo->create($credentials);
                $bank_result = $request->bank_name ? $this->repo->createBank($bank_credentials,$credentials['seller_guid']) : null;
                if ($result){
                    return $this->buildResponse('success' , 'Seller Created!');
                } else {
                    return $this->buildErrorResponse('error' , 'User Not Found', 404);
                }
            }
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
    
    public function update(Request $request, $id) {
        try {
            $patch = new SellerPatchRequest($request->all());
            $credentials = $patch->parse();
            $bank_patch = $request->bank_name ? new BankPatchRequest($request->all()) : null;
            $bank_credentials = $request->bank_name ? $bank_patch->parse() : null;

            if ($credentials){
                $result = $this->repo->update($id, $credentials);
                $bank_result = $request->bank_name ? $this->repo->updateBank($bank_credentials,$id) : null;

                if ($result){
                    return $this->buildResponse('success' , 'Seller Updated!');
                } else {
                    return $this->buildErrorResponse('error' , 'User Not Found', 404);
                }
            }
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
    
    public function destroy($id) {
        try {
            $result = $this->repo->deleteSeller($id);
            if ($result){
                return $this->buildResponse('success' , 'Seller Deleted!');
            } else {
                return $this->buildErrorResponse('error' , 'User Not Found', 404);
            }
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
}
