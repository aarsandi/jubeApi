<?php

namespace App\Http\Controllers;

use Socialite;
use App\Models\User;
use App\Models\ConfigMarketplace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Transformers\UserTransformer;
use App\Repositories\Contracts\IUserRepository;

use App\Lib\Shopee;
use App\Lib\Tokopedia;

class HomeController extends Controller
{
    private $repo;
    public function __construct(IUserRepository $repo) {
        $this->repo = $repo;
    }
    
    public function index(Request $request) {
        // dd('JWT Success');
        $result = User::get();
        return $this->buildCollectionNoMetaResponse($result, new UserTransformer(), 'success', 'user');
        try {
            // $tokopedia = new Tokopedia();
            // $shopee = new Shopee();
            // $refreshToken = $shopee->refreshToken([
            //     'shop_id' => $userMarketplaceData->shop_id, "refresh_token" => $userMarketplaceData->refresh_token
            // ]);
            // dd($refreshToken);
            // return ["data" => $refreshToken];
        } catch (\Exception $exception) {
            return "error";
        }
    } 
}
