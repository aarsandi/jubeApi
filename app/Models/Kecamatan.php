<?php   
namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{

    protected $table = "kecamatan";

    protected $fillable = [];
    
    protected $defaultSort = 'asc';

    public function getSortDirection()
    {
        return $this->defaultSort;
    }

}