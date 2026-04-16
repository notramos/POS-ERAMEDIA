<?php

namespace App\Contracts;

interface ProductServiceInterface
{
    public function getAll(array $filters);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getSupplierItems(int $supplierId);

    public function checkExistence(int $supplierId, string $itemName): bool;
}
