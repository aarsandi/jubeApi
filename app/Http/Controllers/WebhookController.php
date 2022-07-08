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

class WebhookController extends Controller
{

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
                        $decrement = Product::where('product_id', $find->product_id)->decrement('product_stock', $item['qty']);
                    }
                }
                else{
                    // stok di produk internal tidak cukup
                }
            };

            $changeProductShopai = [];
            foreach($arrProductFind as $item) {
                $find = DB::table('product_marketplace')
                ->where(['product_id' => $item->product_id, 'marketplace_id' => 7 ])
                ->first();
                if($find) {
                    array_push($changeProductShopai, [
                        "action"=> "stock_decrement",
                        "ItemId"=> intval($find->product_guid),
                        "qty"=> $item->qtyChange
                    ]);
                }
            };
            
            // shopai webhook
            if(!empty($changeProductShopai)) {
                $bodyInput = json_encode([
                    "items"=> $changeProductShopai
                ]);
                $client = new Client([ 'headers' => ['Content-Type' => 'application/json'] ]);
                $message = $client->request('PATCH', 'http://45.80.181.216:3006/item/updateStock', [ 'body' => $bodyInput ]);
            }else{
                // jika kosong
            }            

            return response()->json(
                [
                    'message' => "success",
                ]
            );
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }
    }

    public function stockChangeTopai(Request $request) {
        try {
            // marketplace_id = 7 = topai
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
                        $decrement = Product::where('product_id', $find->product_id)->decrement('product_stock', $item['qty']);
                    }
                }
                else{
                    // stok di produk internal tidak cukup
                }
            };

            $changeProductTopai = [];
            foreach($arrProductFind as $item) {
                $find = DB::table('product_marketplace')
                ->where(['product_id' => $item->product_id, 'marketplace_id' => 6 ])
                ->first();
                if($find) {
                    array_push($changeProductTopai, [
                        "action"=> "stock_decrement",
                        "ItemId"=> intval($find->product_guid),
                        "qty"=> $item->qtyChange
                    ]);
                }
            };
            
            // shopai webhook
            if(!empty($changeProductTopai)) {
                $bodyInput = json_encode([
                    "items"=> $changeProductTopai
                ]);
                $client = new Client([ 'headers' => ['Content-Type' => 'application/json'] ]);
                $message = $client->request('PATCH', 'http://45.80.181.216:3005/item/updateStock', [ 'body' => $bodyInput ]);
            }else{
                // jika kosong
            }            

            return response()->json(
                [
                    'message' => "success",
                ]
            );
        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }    
    }

 
}
