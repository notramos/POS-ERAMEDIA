<?php

namespace App\Contracts;

interface PurchaseServiceInterface
{
    public function getAll(array $filters);

    public function getSuppliers();

    public function getSupplierItems(int $supplierId);

    public function create(array $data);

    public function delete($id);
}
