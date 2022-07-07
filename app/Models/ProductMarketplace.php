<?php   
namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class ProductMarketplace extends Model
{
    use Filterable;

    protected $defaultSort = 'asc';
    protected $defaultKey = 'product_id';
    protected $table = "product_marketplace";

    protected $fillable = [];
    

    public function getKeyName() { return $this->defaultKey; }
    public function getSortDirection() { return $this->defaultSort; }

    public static function getProductURL($product_id) {
        $product = ProductMarketplace::where('product_id', $product_id)->get();
        $url = [ 'tokopedia' => null, 'shopee' => null,  ];

        if(count($product)){
            foreach($product as $item){
                if($item->marketplace_id == 1){
                    $url['tokopedia'] = $item->product_url != '' ? $item->product_url : null;
                }elseif($item->marketplace_id == 2){
                    $url['shopee'] = $item->product_url != '' ? $item->product_url : null;
                }
            }
        }
        return $url; 
    }

}