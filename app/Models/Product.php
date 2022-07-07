<?php   
namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable;
    public $timestamps = false;

    protected $table = "product";
    protected $defaultSort = 'asc';
    protected $defaultKey = 'product_id';

    protected $fillable = [
        'currency',
        'user_id',
        'user_guid',
        'seller_id',
        'seller_guid',
        'category_id',
        'product_guid',
        'product_slug',
        'product_name',
        'product_price',
        'price_seller',
        'product_stock',
        'product_weight',
        'product_long',
        'product_wide',
        'product_high',
        'product_unit',
        'product_image',
        'product_description',
        'created_user',
        'created_date',
        'modified_user',
        'modified_date',
        'is_deleted',
    ];
    

    public function getKeyName() { return $this->defaultKey; }
    public function getSortDirection() { return $this->defaultSort; }

    public function getSellerAddress($id) {
        $seller = Seller::where('seller_id', $id)->get()->toArray()[0];
        $village = Village::where('village_id', $seller['village'])->get()->toArray()[0]['village_name'];
        $kecamatan = Kecamatan::where('kecamatan_id', $seller['kecamatan'])->get()->toArray()[0]['kecamatan_name'];
        $kabupaten = Kabupaten::where('kabupaten_id', $seller['kabupaten'])->get()->toArray()[0]['kabupaten_name'];

        $address = 'Desa '.$village.', Kecamatan '.$kecamatan.', '.$kabupaten;
        return $address ? $address : null; 
    }

}