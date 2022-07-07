<?php   
namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\Management\SellerTransformer;

class Seller extends Model
{
    use Filterable;
    public $timestamps = false;

    protected $table = "seller";
    protected $defaultSort = 'asc';
    protected $defaultKey = 'seller_id';

    protected $fillable = [
        'user_id',
        'status',
        'store_name',
        'seller_name',
        'seller_guid',
        'seller_phone',
        'seller_email',
        'seller_address',
        'kabupaten',
        'kecamatan',
        'village',
        'created_user',
        'created_date',
        'modified_user',
        'modified_date',
        'is_deleted',
    ];


    public function getKeyName() { return $this->defaultKey; }
    public function getSortDirection() { return $this->defaultSort; }


    public static function getSellerGuid($seller_id)
    {
        return Seller::where('seller_id', $seller_id)->get()[0]->seller_guid;
    }

    public static function getSellerList()
    {
        $query = Seller::where('user_id', Auth::user()->user_id)->get();
        return fractal($query, new SellerTransformer())->toArray()['data'];
    }

    public static function getBank($seller_guid)
    {
        $query = BankAccount::where('seller_guid', $seller_guid)->get();
        if(count($query)){ 
            $bank = $query[0];
            $data = ['id' => $bank->bank_account_id, 'bank_name' => $bank->bank_name, 'account_name' => $bank->account_name, 'account_number' => $bank->account_number];
        }else{
            $data = ['id' => null, 'bank_name' => null, 'account_name' => null, 'account_number' => null];
        };
        return $data;
    }
    
    public static function createGuid()
    {
        $guid = '';
        $namespace = rand(11111, 99999);
        $uid = uniqid('', true);
        $data = $namespace;
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = substr($hash,  0,  8) . '-' .
                substr($hash,  8,  4) . '-' .
                substr($hash, 12,  4) . '-' .
                substr($hash, 16,  4) . '-' .
                substr($hash, 20, 12);

        return Seller::checkGuidExist($guid);
    }
    
    public static function checkGuidExist($guid)
    {
        $query_user_guid = User::where('user_guid', $guid)->get();
        $query_seller_guid = Seller::where('seller_guid', $guid)->get();
        if(count($query_user_guid) == 0 AND count($query_seller_guid) == 0) {
            $user_guid = $guid;
        } else {  $this->createGuid(); }
        
        return $user_guid;
    }
}