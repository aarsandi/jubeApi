<?php

namespace App\Http\Controllers\Marketplace;

use App\Lib\Shopee;
use Illuminate\Http\Request;
use App\Models\ShopeeCategory;
use App\Models\UserMarketplace;
use App\Models\UserMarketplaceShopee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductRequest;
use App\Transformers\Management\ProductTransformer;

class ShopeeController extends Controller
{
    public function __construct() {
        $this->marketplace_id = 2;
        $this->library = new Shopee;
        $this->model = new UserMarketplace;
    }
    public function index() {
        try {
            $check_status = $this->model->checkStatus($this->marketplace_id);
            $url_auth = $this->library->urlAuth();

            return response()->json([
                'status' => 'success',
                'data' => [[
                    'user_id' => $check_status ? $check_status->user_id : null,
                    'status' => $check_status ? $check_status->status : 0,
                    'status_name' => $check_status ? $check_status->status_name : 'Belum Terhubung',
                    'url_auth' => $url_auth ? $url_auth : null
                ]],
                'meta' => null
            ]);

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    }
    
    public function authRedirect(Request $request) {
        try {            
            $code = $request->code;
            $shop_id = $request->shop_id;
            $result = UserMarketplaceShopee::auth($code, $shop_id);

            return response()->json([
                'status' => $result['status'],
                'data' => $result['message']
            ]);

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    }

    public function get_category()
    {
        $result = $this->library->get_category();
        dd($result);
        
    }
    
    public function createCategory() {
        
        $result = $this->library->get_category();
        if($result){
            foreach($result as $item){
                $data_insert = [
                    'product_category_id' => $item->category_id,
                    'category_id' => 0,
                    'product_category_name' => $item->display_category_name,
                    'category_slug' => '',
                    'parent' => $item->parent_category_id ? $item->parent_category_id : 0,
                    'image' => '',
                    'sort_number' => 0,
                    'status' => 1,
                ];
                // ShopeeCategory::create($data_insert);
            
            }
        }
        dd('loop done!');


    }
    
    public function insertCategoryId() {
        $category_id = 8;
        $shopee_category_id = 100633;
        $parent = ShopeeCategory::where('product_category_id', $shopee_category_id)->first();

        if($parent){
            $update_parent = ShopeeCategory::where('product_category_id', $shopee_category_id)->update(['category_id' => $category_id]);
            $child_1 = ShopeeCategory::where('parent', $shopee_category_id)->get();
            if(count($child_1) > 0){
                foreach($child_1 as $item){ //update child 1

                    $update_child_1 = ShopeeCategory::where('product_category_id', $item->product_category_id)->update(['category_id' => $category_id]);
                    $child_2 = ShopeeCategory::where('parent', $item->product_category_id)->get();
                    if(count($child_2) > 0){
                        foreach($child_2 as $item){ //update child 2

                            $update_child_2 = ShopeeCategory::where('product_category_id', $item->product_category_id)->update(['category_id' => $category_id]);
                            $child_3 = ShopeeCategory::where('parent', $item->product_category_id)->get();
                            if(count($child_3) > 0){
                                foreach($child_3 as $item){ //update child 3
                
                                    $update_child_3 = ShopeeCategory::where('product_category_id', $item->product_category_id)->update(['category_id' => $category_id]);
                                    $child_4 = ShopeeCategory::where('parent', $item->product_category_id)->get();
                                    if(count($child_4) > 0){
                                        foreach($child_4 as $item){ //update child 4
                
                                            $update_child_4 = ShopeeCategory::where('product_category_id', $item->product_category_id)->update(['category_id' => $category_id]);
                                            $child_5 = ShopeeCategory::where('parent', $item->product_category_id)->get();
                                            if(count($child_5) > 0){
                                                foreach($child_5 as $item){ //update child 5
                        
                                                    $update_child_5 = ShopeeCategory::where('product_category_id', $item->product_category_id)->update(['category_id' => $category_id]);
                                                    $child_6 = ShopeeCategory::where('parent', $item->product_category_id)->get();
                                                    if(count($child_6) > 0){
                                                        // foreach($child_6 as $item){ //update child 6
                                
                                                        //     $update_child_6 = ShopeeCategory::where('product_category_id', $item->product_category_id)->update(['category_id' => $category_id]);
                                                        //     $child_7 = ShopeeCategory::where('parent', $item->product_category_id)->get();
                                                        //     if(count($child_7) > 0){
                                                        //         foreach($child_7 as $item){ //update child 7
                                        
                                                        //             $update_child_7 = ShopeeCategory::where('product_category_id', $item->product_category_id)->update(['category_id' => $category_id]);
                                                        //             $child_8 = ShopeeCategory::where('parent', $item->product_category_id)->get();
                                                        //             if(count($child_8) > 0){
                                                        //                 foreach($child_8 as $item){ //update child 8
                                                
                                                        //                     $update_child_8 = ShopeeCategory::where('product_category_id', $item->product_category_id)->update(['category_id' => $category_id]);
                                                        //                     $child_9 = ShopeeCategory::where('parent', $item->product_category_id)->get();
                                                        
                                                        
                                                        //                 }
                                                        //                 dd('child 8 done!', $child_2);
                                                        //             };
                                                        //         }
                                                        //         dd('child 7 done!', $child_2);
                                                        //     };
                                                        // }
                                                        dd('child 6 exist!', $child_2);
                                                    };
                                                }
                                                // dd('child 5 done!', $child_2);
                                            };
                                        }
                                        // dd('child 4 done!', $child_2);
                                    };
                                }
                                // dd('child 3 done!', $child_1);
                            };
                        }
                        // dd('child 2 done!', $child_2);
                    };
                }
                // dd('child 1 done!', $child_1);
            };
            
        }else{
            dd('parent not found!');

        }
        dd('loop done category '.$parent->product_category_name.'!');


    }
    
}
