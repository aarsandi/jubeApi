<?php

namespace App\Http\Controllers\Marketplace;

use App\Lib\Tokopedia;
use Illuminate\Http\Request;
use App\Models\ShopeeCategory;
use App\Models\UserMarketplace;
use App\Models\UserMarketplaceTokopedia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductRequest;
use App\Transformers\Management\ProductTransformer;

class TokopediaController extends Controller
{
    public function __construct() {
        $this->marketplace_id = 1;
        $this->library = new Tokopedia;
        $this->model = new UserMarketplaceTokopedia;
    }
    
    public function index() {
        // try {
            $check_status = $this->model->checkStatus($this->marketplace_id);

            return response()->json([
                'status' => 'success',
                'data' => [[
                    'user_id' => $check_status ? $check_status->user_id : null,
                    'status' => $check_status ? $check_status->status : 0,
                    'status_name' => $check_status ? $check_status->status_name : 'Belum Terhubung',
                    'shop_name' => $check_status ? $check_status->shop_name : null,
                    'shop_url' => $check_status ? $check_status->shop_url : null,
                ]],
                'meta' => null
            ]);

        // } catch (\Exception $exception) {
        //     return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        // }
    }

    public function store(Request $request) {
        try {
            $result = $this->model->auth($request->shop_name, $request->shop_url);
            if ($result){
                return response()->json([
                    'status' => $result['status'],
                    'data' => $result['message']
                ]);
            } else {
                return $this->buildErrorResponse('error' , 'User Not Found', 404);
            }
            
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    } 
    
    
}
