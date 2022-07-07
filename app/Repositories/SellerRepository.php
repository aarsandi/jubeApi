<?php

namespace App\Repositories;

use App\Models\Seller;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\ISellerRepository;

class SellerRepository extends GenericRepository implements ISellerRepository
{
    public function __construct()
    {
        parent::__construct(app(Seller::class));
    }

    public function getSeller($id)
    {        
        $data = [
            'seller' =>  $id,
            'deleted' =>  0,
        ];

        return $this->model->filter($data)->get();
    }
    
    public function getSellers($order, $sort, $filter, $page, $limit)
    {        
        $orderBy = $order ? $order : $this->model->getKeyName();
        $sortBy = $sort ? $sort : $this->model->getSortDirection();

        $data = [
            'id' =>  Auth::user()->user_id,
            'name' =>  $filter,
            'deleted' =>  0,
        ];

        return $this->model->filter($data)
            ->orderBy($orderBy, $sortBy)
            ->limit($limit)
            ->paginate($limit);
    }
    
    public function getSellersAll($order, $sort, $filter)
    {
        $orderBy = $order ? $order : $this->model->getKeyName();
        $sortBy = $sort ? $sort : $this->model->getSortDirection();

        $data = [
            'id' =>  Auth::user()->user_id,
            'name' =>  $filter,
            'deleted' =>  0,
        ];

        return $this->model
        ->filter($data)
        ->orderBy($orderBy, $sortBy)->get();
    }
    
    public function createBank($data, $seller_guid)
    {
        $data['seller_guid'] = $seller_guid;
        return BankAccount::create($data);
    }
    
    public function updateBank($data, $id)
    {
        $seller = $this->model->where('seller_id', $id)->get()[0];
        $bank = BankAccount::where('seller_guid', $seller->seller_guid)->first();
        if($bank){
            return BankAccount::where('seller_guid', $seller->seller_guid)->update($data);
        }else{
            return $this->createBank($data, $seller->seller_guid);
        }
    }

    public function deleteBank($seller_guid)
    {
        $data = array('is_deleted'=>1 ,'modified_user'=>Auth::user()->user_id,'modified_date'=> date('Y-m-d H:i:s'));
        return BankAccount::where('seller_guid', $seller_guid)->update($data);
    }

    public function deleteSeller($seller_id)
    {       
        $data = array('is_deleted'=>1 ,'modified_user'=>Auth::user()->user_id,'modified_date'=> date('Y-m-d H:i:s'));
        $seller = $this->model->findOrFail($seller_id);
        $delete_bank = $this->deleteBank($seller->seller_guid);

        return $seller->update($data);
    }
}