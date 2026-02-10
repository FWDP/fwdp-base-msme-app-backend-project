<?php

namespace App\Core\Payments\Contracts;

interface PaymentGateway
{
    public function createPayment(array $data): array;
}
