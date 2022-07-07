<?php

namespace App\Repositories\Contracts;

interface IGenericRepository
{
    public function get($order, $sort, $filter, $page, $limit);
    public function getAll($order, $sort, $filter);
    public function find($id, $field, $join);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function uploadImage(array $data, $folder);
    public function createGuid();
    public function checkGuidExist($guid);

}