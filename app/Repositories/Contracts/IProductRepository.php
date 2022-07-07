<?php

namespace App\Repositories\Contracts;

interface IProductRepository extends IGenericRepository
{
    public function getProduct($order, $sort, $filter, $page, $limit, $category_id, $user_id);
    public function getProductAll($order, $sort, $filter, $category_id, $user_id);
    public function getProduct_M($order, $sort, $filter, $page, $limit, $category_id, $user_id);
    public function getProductAll_M($order, $sort, $filter, $category_id, $user_id);
    public function findProduct($id);
    public function getProductInfo($id);
    public function getProductFormInfo();
    public function createProduct($data);
    public function updateProduct($data);
    public function deleteProduct($product_id);
}