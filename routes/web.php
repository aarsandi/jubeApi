<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Str;

$router->get('/', function () { return response()->json( [ 'code' => 404, 'status' => 'HTTP 404', ], 404 ); });

$router->post('/stok_change_shopai', 'WebhookController@stockChangeShopai');
$router->post('/stok_change_topai', 'WebhookController@stockChangeTopai');

$router->group(['prefix' => 'authentication', ], function () use ($router) {
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');
    $router->get('/info-kabupaten', 'AuthController@registerInfoKabupaten');
    $router->get('/info-kecamatan/{id}', 'AuthController@registerInfoKecamatan');
    $router->get('/info-village/{id}', 'AuthController@registerInfoVillage');
});

$router->group(['prefix' => 'catalog', ], function () use ($router) {
    $router->get('/{id}', 'UserController@findCatalog');
});

$router->group(['prefix' => 'categories', ], function () use ($router) {
    $router->get('/', 'CategoryController@index');
});

$router->group(['prefix' => 'products', ], function () use ($router) {
    $router->get('/', 'ProductController@index');
    $router->get('/{id}', 'ProductController@find');
});


$router->group(['prefix' => 'management'], function () use ($router) {
    
    $router->group(['prefix' => 'shopee', ], function () use ($router) {
        $router->get('/get-category', 'Marketplace\ShopeeController@get_category');
        $router->get('/create-category', 'Marketplace\ShopeeController@insertCategoryShopee');
    });
    
});

$router->group(['prefix' => 'management', 'middleware' => 'jwt_auth'], function () use ($router) {

    $router->group(['prefix' => 'shopee', ], function () use ($router) {
        $router->get('/', 'Marketplace\ShopeeController@index');
        $router->get('/auth-redirect', 'Marketplace\ShopeeController@authRedirect');
    });
   
    $router->group(['prefix' => 'tokopedia', ], function () use ($router) {
        $router->get('/', 'Marketplace\TokopediaController@index');
        $router->post('/', 'Marketplace\TokopediaController@store');
    });

    $router->group(['prefix' => 'products', ], function () use ($router) {
        $router->get('/', 'Management\ProductController@index');
        $router->get('/info', 'Management\ProductController@show');
        $router->get('/{id}', 'Management\ProductController@edit');
        $router->post('/', 'Management\ProductController@store');
        $router->patch('/', 'Management\ProductController@update');
        $router->delete('/{id}', 'Management\ProductController@destroy');
    });

    $router->group(['prefix' => 'sellers', ], function () use ($router) {
        $router->get('/', 'Management\SellerController@index');
        $router->get('/{id}', 'Management\SellerController@show');
        $router->post('/', 'Management\SellerController@store');
        $router->patch('/{id}', 'Management\SellerController@update');
        $router->delete('/{id}', 'Management\SellerController@destroy');
    });

});

