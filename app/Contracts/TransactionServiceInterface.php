<?php

namespace App\Contracts;

interface TransactionServiceInterface
{
    public function getAll(array $filters);

    public function createFromRequest(array $items, ?float $paidAmount, string $paymentMethod, float $discount = 0);

    public function searchProducts(string $query, int $limit = 100);

    public function delete($id);
}
