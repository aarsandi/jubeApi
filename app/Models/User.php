<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\KabupatenTransformer;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model
{
    use Authenticatable, Authorizable, HasFactory, Filterable;
    public $timestamps = false;
    
    protected $table = "user";
    protected $defaultSort = 'asc';
    protected $primaryKey = "user_id";
    protected $guarded = array('user_id');


    protected $fillable = [
         'full_name',
         'username',
         'email',
         'password',
         'user_category',
         'kabupaten',
         'kecamatan',
         'village',
         'status',
         'user_type',
         'provinsi',
         'created_user',
         'created_date',
         'user_guid',
         'catalog_name',
         'token',
     ];

    

    public function getSortDirection()
    {
        return $this->defaultSort;
    }

    public function tokenRevoke(){
        $user_id = auth()->user()->id;
        if($user_id){
            $query = $this->findOrFail($user_id);
            $data = $query->toArray();
    
            if($data){
                $data['token'] = null; #jwt_edited
                $query->update($data);
                return true;
            }else{ return false; }

        }else{ return false; };
    }

    public static function getJobCategory()
    {
        $job_category = [ 
            ['name'=>'BUMDES'], 
            ['name'=>'Kelompok Usaha Bersama'], 
            ['name'=>'Koperasi'], 
            ['name'=>'UMKM'], 
            ['name'=>'Kelompok Tani'], 
            ['name'=>'Lainnya'], 
        ];
        return $job_category;
    }
    
    public static function getKabupatenList()
    {
        $query = Kabupaten::get();
        $data = fractal($query, new KabupatenTransformer())->toArray()['data'];
        return $data;
    }

    public static function getKecamatanList($kabupaten_id)
    {
        $query = Kecamatan::where('kabupaten_id',$kabupaten_id)->get();
        return $query;
    }
    
    public static function getVillageList($kecamatan_id)
    {
        $query = Village::where('village_kecamatan',$kecamatan_id)->get();
        return $query;
    }
}
