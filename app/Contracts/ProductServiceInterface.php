<?php

namespace App\Contracts;

interface ProductServiceInterface
{
    public function getAll(array $filters);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function getSupplierItems(int $supplierId);

    public function checkExistence(int $supplierId, string $itemName): bool;

    public function getAvailableSupplierItems(int $supplierId);

    public function restock(int $productId, int $quantity);
}
