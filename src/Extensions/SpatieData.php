<?php

declare(strict_types=1);

namespace Victormgomes\ScrambleExtensions\Extensions;

use Dedoc\Scramble\Extensions\TypeToSchemaExtension;
use Dedoc\Scramble\Support\Generator\Types as OpenApiTypes;
use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Type;
use Illuminate\Support\Carbon;
use ReflectionClass;
use ReflectionProperty;
use Spatie\LaravelData\Data;

class SpatieData extends TypeToSchemaExtension
{
    public function shouldHandle(Type $type): bool
    {

        return $type instanceof ObjectType

        && is_a($type->name, Data::class, true);

    }

    /**
     * @return mixed
     */
    public function toSchema(Type $type): ?OpenApiTypes\Type
    {

        $class = $type->name;

        $reflection = new ReflectionClass($class);

        $openApiObjectType = new OpenApiTypes\ObjectType;

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {

            $propertyName = $property->getName();

            if ($property->isStatic()) {

                continue;

            }

            $propertyType = $this->resolvePropertyType($property);

            $openApiObjectType->addProperty($propertyName, $propertyType);

            if (! $property->getType()?->allowsNull()) {

                $openApiObjectType->addRequired([$propertyName]);

            }

        }

        return $openApiObjectType;

    }

    private function resolvePropertyType(ReflectionProperty $property): OpenApiTypes\Type
    {

        $type = $property->getType();

        if (! $type) {

            return new OpenApiTypes\StringType;

        }

        $typeName = $type->getName();

        if (is_a($typeName, Carbon::class, true) || $typeName === \DateTimeInterface::class) {

            $schema = new OpenApiTypes\StringType;

            $schema->format('date-time');

            if ($type->allowsNull()) {

                $schema->nullable(true);

            }

            return $schema;

        }

        $schema = match ($typeName) {

            'int' => new OpenApiTypes\IntegerType,

            'float' => new OpenApiTypes\NumberType,

            'bool' => new OpenApiTypes\BooleanType,

            'array' => new OpenApiTypes\ArrayType(new OpenApiTypes\UnknownType),

            default => new OpenApiTypes\StringType,

        };

        if ($type->allowsNull()) {

            $schema->nullable(true);

        }

        return $schema;

    }
}
