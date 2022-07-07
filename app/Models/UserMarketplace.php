<?php   
namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UserMarketplace extends Model
{
    public $timestamps = false;

    protected $table = "user_marketplace";
    protected $defaultKey = 'user_id';
    protected $defaultSort = 'asc';

    protected $fillable = [
        'user_id',
        'user_guid',
        'shop_id',
        'shop_url',
        'shop_name',
        'marketplace_id',
        'token_marketplace',
        'status',
        'status_name',
    ];
    

    public function getSortDirection()
    {
        return $this->defaultSort;
    }
    
    public static function checkStatus($marketplace_id)
    {
        $status = UserMarketplace::where('user_id', Auth::user()->user_id)->where('marketplace_id', $marketplace_id)->first();
        return $status;
    }

}