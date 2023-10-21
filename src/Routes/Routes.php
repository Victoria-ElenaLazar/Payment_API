<?php
declare(strict_types=1);

namespace PaymentApi\Routes;

enum Routes: string
{
    case PaymentMethods = 'payment method';
    case Customers = 'customer';
    case Transaction = 'transaction';
    case Basket = 'basket';
    case User = 'user';

    public function toSingular(): string
    {
        return match ($this){
            Routes::PaymentMethods => 'payment method',
            Routes::Customers => 'customer',
            Routes::Transaction => 'transaction',
            Routes::Basket => 'basket',
            Routes::User => 'user'
        };
    }
}