<?php

namespace App\Enums;

enum BillingType: string
{
    use EnumToArray;

    case BOLETO = 'BOLETO';
    case PIX = 'PIX';
    case CREDIT_CARD = 'CREDIT_CARD';
}
