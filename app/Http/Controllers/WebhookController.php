<?php

namespace App\Http\Controllers;

use Socialite;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Models\ProductMarketplace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Transformers\UserTransformer;

class WebhookController extends Controller {

    public function stockChangeShopai(Request $request) {
        try {
            // marketplace_id = 6 = shopai
            $body = $request->all();
            $arrProductFind = [];
            foreach($body['data'] as $item) {
                $find = DB::table('product_marketplace')
                ->leftJoin('product', 'product_marketplace.product_id', '=', 'product.product_id')
                ->where(['product_marketplace.product_guid' => $item['ItemId'],'product_marketplace.marketplace_id' => 6 ])
                ->select('product_marketplace.marketplace_id', 'product_marketplace.product_id', 'product.product_stock', 'product_marketplace.product_guid as marketplace_guid')
                ->first();
                if($find) {
                    if($find->product_stock>=$item['qty']) {
                        $find->qtyChange = $item['qty'];
                        array_push($arrProductFind,$find);
                    }else{
                        // stok di produk internal tidak cukup
                        DB::table('error_log')->insert([
                            'marketplace_id' => 6,
                            'error_message' => 'stok di produk internal tidak cukup',
                            'error_function' => 'webhook_shopai',
                            'user_id' => 0,
                            'created_date' => date('Y-m-d H:i:s'),
                            'data' => json_encode([
                                'product.id' => $find->product_id,
                                'product_marketplace.product_guid' => $item['ItemId'],
                                'product.product_stock' => $find->product_stock,
                                'input.qty' => $item['qty']
                            ])
                        ]);
                    }
                }
                else{
                    DB::table('error_log')->insert([
                        'marketplace_id' => 6,
                        'error_message' => 'data tidak ditemukan',
                        'error_function' => 'webhook_shopai',
                        'user_id' => 0,
                        'created_date' => date('Y-m-d H:i:s'),
                        'data' => ''
                    ]);
                }
            };
            
            foreach($arrProductFind as $item) {
                // cari data produk topai yang konek sama item ini
                $find = DB::table('product_marketplace')
                ->where(['product_id' => $item->product_id, 'marketplace_id' => 7 ])
                ->first();
                
                if($find) {
                    $sendData = [[
                        "action"=> "stock_decrement",
                        "ItemId"=> intval($find->product_guid),
                        "qty"=> $item->qtyChange
                    ]];
                    
                    $bodyInput = json_encode([
                        "items"=> $sendData
                    ]);
                    $client = new Client([ 'headers' => ['Content-Type' => 'application/json'] ]);
                    $message = $client->request('PATCH', 'http://45.80.181.216:3006/item/updateStock', [ 'body' => $bodyInput ]);
                    if($message->getStatusCode() !== 200) {
                        DB::table('error_log')->insert([
                            'marketplace_id' => 6,
                            'error_message' => 'error send data to topai',
                            'error_function' => 'webhook_shopai',
                            'user_id' => 0,
                            'created_date' => date('Y-m-d H:i:s'),
                            'data' => json_encode($sendData)
                        ]);
                    }
                }else{
                    DB::table('error_log')->insert([
                        'marketplace_id' => 6,
                        'error_message' => 'data yang konek tidak ada',
                        'error_function' => 'webhook_shopai',
                        'user_id' => 0,
                        'created_date' => date('Y-m-d H:i:s'),
                        'data' => ''
                    ]);
                }
                
                // decrease di internal
                $decrement = Product::where('product_id', $item->product_id)->decrement('product_stock', $item->qtyChange);
                
                if(!$decrement) {
                    DB::table('error_log')->insert([
                        'marketplace_id' => 6,
                        'error_message' => 'decrease produk internal gagal',
                        'error_function' => 'webhook_shopai',
                        'user_id' => 0,
                        'created_date' => date('Y-m-d H:i:s'),
                        'data' => ''
                    ]);
                }
            };
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    }

    public function stockChangeTopai(Request $request) {
        try {
            $body = $request->all();
            $arrProductFind = [];
            foreach($body['data'] as $item) {
                $find = DB::table('product_marketplace')
                ->leftJoin('product', 'product_marketplace.product_id', '=', 'product.product_id')
                ->where(['product_marketplace.product_guid' => $item['ItemId'],'product_marketplace.marketplace_id' => 7 ])
                ->select('product_marketplace.marketplace_id', 'product_marketplace.product_id', 'product.product_stock', 'product_marketplace.product_guid as marketplace_guid')
                ->first();
                if($find) {
                    if($find->product_stock>=$item['qty']) {
                        $find->qtyChange = $item['qty'];
                        array_push($arrProductFind,$find);
                    }else{
                        // stok di produk internal tidak cukup
                        DB::table('error_log')->insert([
                            'marketplace_id' => 7,
                            'error_message' => 'stok di produk internal tidak cukup',
                            'error_function' => 'webhook_topai',
                            'user_id' => 0,
                            'created_date' => date('Y-m-d H:i:s'),
                            'data' => json_encode([
                                'product.id' => $find->product_id,
                                'product_marketplace.product_guid' => $item['ItemId'],
                                'product.product_stock' => $find->product_stock,
                                'input.qty' => $item['qty']
                            ])
                        ]);
                    }
                }
                else{
                    DB::table('error_log')->insert([
                        'marketplace_id' => 7,
                        'error_message' => 'data tidak ditemukan',
                        'error_function' => 'webhook_topai',
                        'user_id' => 0,
                        'created_date' => date('Y-m-d H:i:s'),
                        'data' => ''
                    ]);
                }
            };
            
            foreach($arrProductFind as $item) {
                // cari data produk topai yang konek sama item ini
                $find = DB::table('product_marketplace')
                ->where(['product_id' => $item->product_id, 'marketplace_id' => 6 ])
                ->first();
                
                if($find) {
                    $sendData = [[
                        "action"=> "stock_decrement",
                        "ItemId"=> intval($find->product_guid),
                        "qty"=> $item->qtyChange
                    ]];
                    
                    $bodyInput = json_encode([
                        "items"=> $sendData
                    ]);
                    $client = new Client([ 'headers' => ['Content-Type' => 'application/json'] ]);
                    $message = $client->request('PATCH', 'http://45.80.181.216:3005/item/updateStock', [ 'body' => $bodyInput ]);
                    if($message->getStatusCode() !== 200) {
                        DB::table('error_log')->insert([
                            'marketplace_id' => 7,
                            'error_message' => 'error send data to topai',
                            'error_function' => 'webhook_topai',
                            'user_id' => 0,
                            'created_date' => date('Y-m-d H:i:s'),
                            'data' => json_encode($sendData)
                        ]);
                    }
                }else{
                    DB::table('error_log')->insert([
                        'marketplace_id' => 7,
                        'error_message' => 'data yang konek tidak ada',
                        'error_function' => 'webhook_topai',
                        'user_id' => 0,
                        'created_date' => date('Y-m-d H:i:s'),
                        'data' => ''
                    ]);
                }
                
                // decrease di internal
                $decrement = Product::where('product_id', $item->product_id)->decrement('product_stock', $item->qtyChange);
                
                if(!$decrement) {
                    DB::table('error_log')->insert([
                        'marketplace_id' => 7,
                        'error_message' => 'decrease produk internal gagal',
                        'error_function' => 'webhook_topai',
                        'user_id' => 0,
                        'created_date' => date('Y-m-d H:i:s'),
                        'data' => ''
                    ]);
                }
            };
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }   
    }

 
}
