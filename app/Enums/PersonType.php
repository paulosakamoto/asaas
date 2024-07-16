<?php

namespace App\Enums;

enum PersonType: string
{
    use EnumToArray;

    case FISICA = 'FISICA';
    case JURIDICA = 'JURIDICA';
}
