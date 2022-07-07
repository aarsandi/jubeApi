<?php   
namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{

    protected $table = "bank_account";
    protected $defaultKey = 'bank_account_id ';
    protected $defaultSort = 'asc';
    public $timestamps = false;

    protected $fillable = [
        'user_guid',
        'seller_guid',
        'bank_name',
        'account_name',
        'account_number',
        'created_user',
        'created_date',
        'modified_user',
        'modified_date',
        'is_deleted',
    ];
    

    public function getSortDirection()
    {
        return $this->defaultSort;
    }

}