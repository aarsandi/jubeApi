<?php

namespace App\Repositories;
 
use App\Repositories\Contracts\IGenericRepository;

abstract class GenericRepository implements IGenericRepository
{
    protected $model;

    protected $callback = true;

    public function __construct($model, $callback = true)
    {
        $this->model = $model;
        $this->callback = $callback;
    }

    public function get($order, $sort, $filter, $page, $limit)
    {        
        $orderBy = $order ? $order : $this->model->getKeyName();
        $sortBy = $sort ? $sort : $this->model->getSortDirection();

        $data = [
            'name' =>  $filter,
        ];

        return $this->model->filter($data)
            ->orderBy($orderBy, $sortBy)
            ->offset(($page - 1) * $page)
            ->limit($limit)
            ->paginate($limit);
    }
    
    public function getAll($order = null,$sort = null, $filter = null)
    {
        $orderBy = $order ? $order : $this->model->getKeyName();
        $sortBy = $sort ? $sort : $this->model->getSortDirection();

        $data = [
            'name' =>  $filter,
        ];
            
        return $this->model->filter($data)->orderBy($orderBy, $sortBy)->get();
    }
    
    public function find($id, $field = null, $join = [])
    {
        $data = [
            'id' =>  $id,
            'field' =>  $field,
        ];
        
        return $this->model->filter($data)->get();
    }

    
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $query = $this->model->findOrFail($id);

        $query->update($data);
        return $query;
    }

    public function delete($id){
        $query = $this->model->findOrFail($id);
        return $query->delete();
    }
    
    public function uploadImage(array $data, $folder)
    {
        $image_name = '';
        foreach($data as $image){ if(isset($image)){
            $name = rand(100000, 10000000).'_'.$image->getClientOriginalName();
            if(!file_exists(rtrim(app()->basePath('public/storage/images/'.$folder.'/'.$name)))){       
                $destinationPath = rtrim( app()->basePath('public/storage/images/'.$folder.'/') );
                $image->move($destinationPath, $name);
                $image_name = !next($data) ? $image_name.$name : $image_name.$name.',';
            }
        }}
        return $image_name;
    }
    
    public function createGuid()
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

        return $this->checkGuidExist($guid);
    }
    
    public function checkGuidExist($guid)
    {
        $query_user_guid = User::where('user_guid', $guid)->get();
        $query_seller_guid = Seller::where('seller_guid', $guid)->get();
        if(count($query_user_guid) == 0 AND count($query_seller_guid) == 0) {
            $user_guid = $guid;
        } else {  $this->createGuid(); }
        
        return $user_guid;
    }

}