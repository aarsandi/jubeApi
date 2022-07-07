<?php
namespace App\Lib;

use GuzzleHttp\Client;
use App\Models\UserMarketplace;
use App\Models\ConfigMarketplace;
use Illuminate\Support\Facades\Auth;

class Shopee {
    public function __construct()
    {
        $this->marketplace_id = 2;
        $this->user_id = Auth::user()->user_id;
        $this->user_guid = Auth::user()->user_guid;
        $config = ConfigMarketplace::where('marketplace_id', $this->marketplace_id)->first();

        $this->path_api = $config->path_api;
        $this->partner_id = $config->app_id;
        $this->partner_key = $config->api_key;
        $this->path_api_auth = $config->path_api_auth;
        $this->url_api = $config->marketplace_api_auth;


    }

    public function urlAuth(){
        
        $timestamp = time();
        $redirect_url = url('management/shopee/auth-redirect');
        $sign = hash_hmac('sha256', $this->partner_id . $this->path_api_auth . $timestamp, $this->partner_key);

        $url_auth = $this->url_api.$this->path_api_auth.'?timestamp='.$timestamp.'&partner_id='.$this->partner_id.'&sign='.$sign.'&redirect='.$redirect_url;
        return $url_auth;
    }

    public function authToken()
    {
      $path = "auth/token/get";
      $url = $this->generateUrlRequestAuth($path);    

      $query = UserMarketplace::where(['user_id'=> $this->user_id, 'marketplace_id' => $this->marketplace_id])->first();
      $model['code'] = $query->token_marketplace;
      $model['shop_id'] = intval($query->shop_id);
      $model['partner_id'] = intval($this->partner_id);
      
      $response = json_decode($this->sendHttpRequest($url, json_encode($model), "POST"));

      $expire_in = $response->expire_in;
      $access_token = $response->access_token;
      $refresh_token = $response->refresh_token;
      $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours', time()));
      
      $data = array('access_token' => $access_token, 'refresh_token' => $refresh_token, 'expired_in' => $expire_in, 'expired_at' => $expired_at, 'modified_user' => $this->user_id, 'modified_date' => date('Y-m-d H:i:s'));
      return UserMarketplace::where('user_id', $this->user_id)->where('marketplace_id', $this->marketplace_id)->update($data);
    }
    
	  public function refreshToken() {
      $path = "auth/access_token/get";
      $url = $this->generateUrlRequestAuth($path);

      $query = UserMarketplace::where(['user_id'=> $this->user_id, 'marketplace_id' => $this->marketplace_id])->first();

      $model['partner_id'] = intval($this->partner_id);
      $model['refresh_token'] = $query->refresh_token;
      $model['shop_id'] = intval($query->shop_id);

      $response = json_decode($this->sendHttpRequest($url, json_encode($model), "POST"));
      if($response->error) {
          $update = UserMarketplace::where(['user_id'=> $this->user_id, 'marketplace_id' => $this->marketplace_id])->update(['status' => 0, 'status_name' => 'Gagal terhubung', 'modified_date' => date('Y-m-d H:i:s')]);
          return $response->message;
      } else {
          $access_token = $response->access_token;
          $refresh_token = $response->refresh_token;
          $expire_in = $response->expire_in;
          $expired_at = date("Y-m-d H:i:s", strtotime('+1 days', time()));

          if ($access_token != "" and $refresh_token != "" and $expire_in != "") {
            UserMarketplace::where(['user_id'=> $this->user_id, 'marketplace_id' => $this->marketplace_id])->update(['status' => 1, 'status_name' => 'Terhubung', 'access_token' => $access_token, 'refresh_token' => $refresh_token, 'expired_in' => $expire_in, 'expired_at' => $expired_at, 'modified_date' => date('Y-m-d H:i:s')]);
          }
          return 'success';
      } 
    }

    
    public function get_category()
    {
      $path = "product/get_category";

      //Refresh Token
      $this->refreshToken();
      $url = $this->generateUrlRequest($path);

      $param['language'] = "id";
      $data = $this->sendHttpRequest($url, $param, "GET");
      $rescategory = json_decode($data);
      return $rescategory->response->category_list;
    }

    public function generateUrlRequestAuth($path, $redirect_url = "") {
        $host = $this->url_api;
        $path_api = $this->path_api;
        $partner_id = $this->partner_id;
        $partner_key = $this->partner_key;

        $time = time();
        $full_path_api = $path_api . $path;
        $base_string = $partner_id . $full_path_api . $time;
        $sign = hash_hmac('sha256', $base_string, $partner_key);

        $url = $host . $full_path_api . "?timestamp=" . $time . "&partner_id=" . $partner_id . "&sign=" . $sign;

        if (!empty($redirect_url)) {
            $url += $url . "&redirect=" . $redirect_url;
        }
        return $url;
    }

    public function generateUrlRequest($path, $redirect_url = "")
    {
        $query = UserMarketplace::where(['user_id'=> $this->user_id, 'marketplace_id' => $this->marketplace_id])->first();

        $time = time();
        $host = $this->url_api;
        $path_api = $this->path_api;
        $partner_id = $this->partner_id;
        $partner_key = $this->partner_key;
        $access_token = $query->access_token;
        $shop_id = intval($query->shop_id);

        $full_path_api = $path_api . $path;
        $base_string = $partner_id . $full_path_api . $time . $access_token . $shop_id;

        $sign = hash_hmac('sha256', $base_string, $partner_key);

        $url = $host . $full_path_api . "?timestamp=" . $time . "&partner_id=" . $partner_id . "&sign=" . $sign . "&access_token=" . $access_token . "&shop_id=" . $shop_id;

        if (!empty($redirect_url)) {
            $url += $url . "&redirect=" . $redirect_url;
        }
        return $url;
    }

    public function sendHttpRequest($url, $body, $method) {
      if($method == 'POST'){
        $client = new Client([ 'headers' => ['Content-Type' => 'application/json'] ]);
        $message = $client->request($method, $url, [ 'body' => $body ]); 

      }elseif($method == 'GET'){
        $client = new Client;
        $parameter_query = "";
        foreach ($body as $key => $value) {
            $parameter_query .= '&' . $key . '=' . $value;
        }
        $host = $url . $parameter_query;
        $message = $client->request('GET', $host);

      }

      return $message->getBody()->getContents();
  }

}