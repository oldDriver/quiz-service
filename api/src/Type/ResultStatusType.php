<?php

namespace App\Type;

use App\DBAL\AbstractEnumType;
use App\Entity\Result;

class ResultStatusType extends AbstractEnumType
{
    /**
     * @var string
     */
    protected $name = 'enum_result_status';

    /**
     * @var array
     */
    protected $values = [
        Result::STATUS_NEW,
        Result::STATUS_ACTIVE,
        Result::STATUS_FINISHED,
    ];
}
