<?php

namespace App\Type;

use App\DBAL\AbstractEnumType;
use App\Entity\Result;

class ResultStatusType extends AbstractEnumType
{
    protected string $name = 'enum_result_status';

    protected array $values = [
        Result::STATUS_NEW,
        Result::STATUS_ACTIVE,
        Result::STATUS_FINISHED,
    ];
}
