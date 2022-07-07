<?php

namespace App\Http\Controllers;

use App\Transformers\DataArraySerializer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function buildResourceResponse(ResourceAbstract $resource, $status = null, $data_meta = null)
    {
        $fractal = new \League\Fractal\Manager;
        $fractal->setSerializer(new DataArraySerializer());
        
        if($data_meta){
            $resource_meta = $fractal->createData($data_meta)->toArray();
            $meta = $resource_meta['meta'];
        }else{
            $meta = null;
        }

        return response()->json(
            [
                'status' => $status,
                'data' => $fractal->createData($resource)->toArray(),
                'meta' => $meta
            ]
        );

    }
    
    protected function buildFilterResourceResponse(ResourceAbstract $resource, $status = null, $data_meta = null)
    {
        $data = array();
        $fractal = new \League\Fractal\Manager;
        $fractal->setSerializer(new DataArraySerializer());
        $resources = $fractal->createData($resource)->toArray();
        if($data_meta){
            $resource_meta = $fractal->createData($data_meta)->toArray();
            $meta = $resource_meta['meta'];
        }else{  $meta = null;  }
        foreach($resources as $r){ if(!empty($r)){ $data[] = $r; }; };

        return response()->json(
            [
                'status' => $status,
                'data' => $data,
                'meta' => $meta
            ]
        );
    }

    protected function buildCartResourceResponse(ResourceAbstract $resource, $status = null, $data_meta = null)
    {
        $fractal = new \League\Fractal\Manager;
        $fractal->setSerializer(new DataArraySerializer());
        $cart = $fractal->createData($resource)->toArray();
        $total_cart = array_sum(array_column($cart, 'total_price'));

        if($data_meta){
            $resource_meta = $fractal->createData($data_meta)->toArray();
            $meta = $resource_meta['meta'];
        }else{
            $meta = null;
        }

        return response()->json(
            [
                'status' => $status,
                'data' => array([
                    'total_cart' => $total_cart,
                    'carts' => $cart
                ]),
                'meta' => $meta
            ]
        );

    }
    
    protected function buildCollectionResponse($collection, TransformerAbstract $transformer, $status, $key)
    {
        $resource = new Collection($collection, $transformer, $key);
        $data_meta = new Collection($collection, $transformer, $key);

        $data_meta->setMeta([
            'total' => $collection->total(),
            'limit' => (int)$collection->perPage(),
            'page' => $collection->currentPage(),
            'from' => $collection->firstItem(),
            'to' => $collection->lastItem(),
        ]);
        return $this->buildResourceResponse($resource, $status, $data_meta);
    }

    protected function buildCollectionNoMetaResponse($collection, TransformerAbstract $transformer, $status, $key)
    {
        $resource = new Collection($collection, $transformer, $key);
        
        return $this->buildResourceResponse($resource, $status);
    }

    protected function buildFilterResponse($collection, TransformerAbstract $transformer, $status, $key)
    {
        $resource = new Collection($collection, $transformer, $key);
        $data_meta = new Collection($collection, $transformer, $key);

        $data_meta->setMeta([
            'total' => $collection->total(),
            'limit' => (int)$collection->perPage(),
            'page' => $collection->currentPage(),
            'from' => $collection->firstItem(),
            'to' => $collection->lastItem(),
        ]);

        return $this->buildFilterResourceResponse($resource, $status, $data_meta);
    }

    protected function buildFilterNoMetaResponse($collection, TransformerAbstract $transformer, $status, $key)
    {
        $resource = new Collection($collection, $transformer, $key);

        return $this->buildFilterResourceResponse($resource, $status);
    }

    protected function buildCartCollectionResponse($collection, TransformerAbstract $transformer, $status, $key)
    {
        $resource = new Collection($collection, $transformer, $key);
        $data_meta = new Collection($collection, $transformer, $key);

        $data_meta->setMeta([
            'total' => $collection->total(),
            'limit' => (int)$collection->perPage(),
            'page' => $collection->currentPage(),
            'from' => $collection->firstItem(),
            'to' => $collection->lastItem(),
        ]);
        return $this->buildCartResourceResponse($resource, $status, $data_meta);
    }

    protected function buildCartCollectionNoMetaResponse($collection, TransformerAbstract $transformer, $status, $key)
    {
        $resource = new Collection($collection, $transformer, $key);
        
        return $this->buildCartResourceResponse($resource, $status);
    }

    protected function buildResponseWithToken($collection, TransformerAbstract $transformer, $token = null, $status = null, $key = null)
    {
        $fractal = new \League\Fractal\Manager;
        $fractal->setSerializer(new DataArraySerializer());
        $resource = new Collection($collection, $transformer, $key);

        $response = $fractal->createData($resource)->toArray();
        $response[0]['token'] =  $token;

        return response()->json(
            [
                'status' => $status,
                'data' =>  $response
            ]
        );
    }

    protected function buildRefreshTokenResponse($token = null, $status = null)
    {

        return response()->json(
            [
                'status' => $status,
                'data' =>  array($token)
            ]
        );
    }

    protected function buildResponse($status, $data = null)
    {
        return response()->json(
            [
                'status' => $status,
                'data' => $data
            ]
        );
    }
    
    protected function buildfailResponse($status, $message = null)
    {
        return response()->json(
            [
                'status' => $status,
                'data' => $message
            ],
        );
    }
    
    protected function buildErrorResponse($status, $message = null, $statusCode = null)
    {
        if($message == "Attempt to read property \"user_id\" on null"){$statusCode = 401;$message = 'Unauthorized';}else{$statusCode = 500;}
        return response()->json(
            [
                'code' => $statusCode,
                'status' => $status,
                'data' => $message
            ], $statusCode
        );
    }

    
}
