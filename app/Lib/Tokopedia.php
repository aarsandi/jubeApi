<?php
namespace App\Lib;

use GuzzleHttp\Client;
use App\Models\UserMarketplace;
use App\Models\ConfigMarketplace;
use Illuminate\Support\Facades\Auth;

class Tokopedia {
    public function __construct()
    {
        $this->marketplace_id = 1;
        $this->user_id = Auth::user()->user_id;
        $this->user_guid = Auth::user()->user_guid;
        $config = ConfigMarketplace::where('marketplace_id', $this->marketplace_id)->first();
        $model = UserMarketplace::where(['user_id'=> $this->user_id, 'marketplace_id' => $this->marketplace_id])->first();

        $this->shop_id = $model->shop_id;
        $this->etalase_id = $model->etalase_id;

        $this->url_api_auth = $config->marketplace_api_auth;
        $this->client_secret = $config->client_secret;
        $this->url_api = $config->marketplace_api;
        $this->client_id = $config->client_id;
        $this->app_id = $config->app_id;
        
    }
    
    public function get_api($url='',$method='', $param='',$headers='',$keyword='',$data='')
    {
    	$ch = curl_init($url.$param.$keyword);
    	
        //Set the headers that we want our cURL client 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    	//curl_setopt($ch, CURLOPT_USERPWD, $data);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$result = curl_exec($ch);
    	$info = curl_getinfo($ch);
    	curl_close($ch);
    	
        
    	return $result;
    }

    public function authenticate() {

        $method = 'POST';
        $param = '/token?grant_type=client_credentials';
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic '. base64_encode($this->client_id.':'.$this->client_secret)
        );      
        $keyword = '';
        $result_json = $this->get_api($this->url_api_auth, $method, $param, $headers, $keyword);
        
        if(isset($result_json)) {
            $result_arr = json_decode($result_json,true);
            return $result_arr;
        } else {
            return array("error"=>"internal server error");
        }
        
    }

    public function get_shop_info() {
        $authenticate = $this->authenticate();
        if (!empty($authenticate['error'])) {
            return $authenticate['error'];
        }

        $method = 'GET';
        $fs_id = $this->app_id;
        $keyword = '?shop_id='.$this->shop_id;
        $param = '/v1/shop/fs/'.$fs_id.'/shop-info';
        $headers = array( 'Authorization: '.$authenticate['token_type'].' '.$authenticate['access_token']  );

        $result_json = $this->get_api($this->url_api, $method, $param, $headers, $keyword);
        
        return json_decode($result_json);
    }

}