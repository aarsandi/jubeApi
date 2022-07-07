<?php

namespace App\Repositories\Contracts;

interface ISellerRepository extends IGenericRepository
{
    public function getSeller($id);
    public function getSellers($order, $sort, $filter, $page, $limit);
    public function getSellersAll($order, $sort, $filter);
    public function createBank($data, $seller_guid);
    public function updateBank($data, $id);
    public function deleteSeller($id);
}