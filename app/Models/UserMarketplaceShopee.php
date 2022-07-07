<?php   
namespace App\Models;

use App\Lib\Shopee;
use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UserMarketplaceShopee extends Model
{

    protected $table = "user_marketplace";

    protected $fillable = [];
    
    protected $defaultSort = 'asc';

    public function getSortDirection()
    {
        return $this->defaultSort;
    }
    
    public static function auth($code, $shop_id)
    {
        $marketplace_id = 2;
        $library = new Shopee;
        $user_id = Auth::user()->user_id;
        $user_guid = Auth::user()->user_guid;
        $expired_at = date("Y-m-d H:i:s", strtotime('+24 hours', time()));
        
        if ($code && $shop_id && $user_id) {
            $user_marketplace = UserMarketplace::where('user_id', $user_id)->where('marketplace_id', $marketplace_id)->first(); 
            if($user_marketplace) {
                UserMarketplace::where('user_id', $user_id)->where('marketplace_id', $marketplace_id)->update(['token_marketplace' => $code, 'shop_id' => $shop_id, 'status' => 1, 'status_name'=>'Terhubung', 'expired_at' => $expired_at]);
                $response = ['status' => 'success', 'message'=>'User Marketplace Updated!'];
                $library->authToken();

            } else {
                UserMarketplace::create(['user_id' => $user_id, 'user_guid' => $user_guid, 'marketplace_id' => $marketplace_id, 'token_marketplace' => $code, 'shop_id' => $shop_id, 'status' => 1, 'status_name'=>'Terhubung']);
                $response = ['status' => 'success', 'message'=>'User Marketplace Created!']; 
                $library->authToken();     
            }
        } else {
            $response = ['status' => 'error', 'message'=>'Data cannot be empty!'];
        }
        return $response;
    }

}