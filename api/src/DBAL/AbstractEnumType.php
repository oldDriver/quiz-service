<?php

namespace App\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Class AbstractEnumType.
 */
abstract class AbstractEnumType extends Type
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $values = [];

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $values = array_map(
            function ($val) {
                return "'".$val."'";
            },
            $this->values
        );

        return 'ENUM('.implode(', ', $values).')';
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->values)) {
            throw new \InvalidArgumentException("Invalid enum value given: '".$value."' for enum: '".$this->name."'");
        }

        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
