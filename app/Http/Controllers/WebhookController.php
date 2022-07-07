<?php

namespace App\Http\Controllers;

use Socialite;
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
            // marketplace_id = 6
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $body = $request->all();      
            // dd($body['data']);     
            // $output->writeln(json_encode($body));
            foreach($body['data'] as $item) {
                $find = ProductMarketplace::where(['product_guid' => $item['ItemId'],'marketplace_id' => 6 ])->first();
                $decrement = Product::where('product_id', $find->product_id)->decrement('product_stock', $item['qty']);

                $bodyInput = json_encode([
                    "items"=> [
                        [
                            "action"=> $item['action'],
                            "ItemId"=> $item['ItemId'],
                            "qty"=> $item['qty']
                        ]
                    ]
                ]);
                $client = new Client([ 'headers' => ['Content-Type' => 'application/json'] ]);
                $message = $client->request('PATCH', 'http://localhost:3006/item/updateStock', [ 'body' => $bodyInput ]);
            };
            // dd($message);
            // cari product yang table shopay idnya sama trus di update stoknya
            // 

            return response()->json(
                [
                    'data' => $body,
                ]
            );

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }        
    }

    public function stockChangeTopai(Request $request) {
        try {
            // marketplace_id = 6
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $body = $request->all();            
            $output->writeln(json_encode($body));
            // dd($body['data']);
            // cari product yang table shopay idnya sama trus di update stoknya
            // 

            return response()->json(
                [
                    'data' => $body,
                ]
            );

        } catch (\Exception $exception) {
            return $this->buildErrorResponse('error' , $exception->getMessage(), $exception->getCode());
        }        
    }

 
}
