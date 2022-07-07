<?php   
namespace App\Models;

use App\Lib\Tokopedia;
use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UserMarketplaceTokopedia extends Model
{

    protected $table = "user_marketplace";

    protected $fillable = [];
    
    protected $defaultSort = 'asc';

    public function getSortDirection()
    {
        return $this->defaultSort;
    }
    
    public static function checkStatus($marketplace_id)
    {
        $library = new Tokopedia;
        $query = UserMarketplace::where('user_id', Auth::user()->user_id)->where('marketplace_id', $marketplace_id)->first();
        if($query){
            $check = $library->get_shop_info();
            if($check){
                dd('data ada');
            }else{
                
                dd('data tidak ada');
            }

        }

        return $status;
    }

    public static function auth($shop_name, $shop_url)
    {
        $marketplace_id = 1;
        $user_id = Auth::user()->user_id;
        $user_guid = Auth::user()->user_guid;
        
        if ($shop_name && $shop_url && $user_id) {
            $user_marketplace = UserMarketplace::where('user_id', $user_id)->where('marketplace_id', $marketplace_id)->first(); 
            if($user_marketplace) {
                UserMarketplace::where('user_id', $user_id)->where('marketplace_id', $marketplace_id)->update(['shop_url' => $shop_url, 'shop_name' => $shop_name, 'status_name'=>'Sedang Diproses Admin', 'modified_date' => date('Y-m-d H:i:s')]);
                $response = ['status' => 'success', 'message'=>'User Marketplace Updated!'];

            } else {
                UserMarketplace::create(['user_id' => $user_id, 'user_guid' => $user_guid, 'marketplace_id' => $marketplace_id, 'shop_url' => $shop_url, 'shop_name' => $shop_name, 'status' => 0, 'status_name'=>'Sedang Diproses Admin', 'created_date' => date('Y-m-d H:i:s')]);
                $response = ['status' => 'success', 'message'=>'User Marketplace Created!']; 
            }
        } else {
            $response = ['status' => 'error', 'message'=>'Data cannot be empty!'];
        }
        return $response;
    }

}