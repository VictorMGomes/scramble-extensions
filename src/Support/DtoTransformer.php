<?php

declare(strict_types=1);

namespace Victormgomes\ScrambleExtensions\Support;

use DateTimeInterface;
use Dedoc\Scramble\Support\Generator\Types\ArrayType;
use Dedoc\Scramble\Support\Generator\Types\BooleanType;
use Dedoc\Scramble\Support\Generator\Types\IntegerType;
use Dedoc\Scramble\Support\Generator\Types\NumberType;
use Dedoc\Scramble\Support\Generator\Types\ObjectType;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Dedoc\Scramble\Support\Generator\Types\Type;
use Dedoc\Scramble\Support\Generator\Types\UnknownType;
use Illuminate\Support\Carbon;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class DtoTransformer
{
    public function toSchema(string $className, array $visited = []): ObjectType
    {
        $schema = new ObjectType;

        if (! class_exists($className)) {
            return $schema;
        }

        if (in_array($className, $visited)) {
            $reflection = new ReflectionClass($className);
            $shortName = $reflection->getShortName();
            $schema->setDescription("Circular reference to $shortName");

            return $schema;
        }

        $visited[] = $className;

        $reflection = new ReflectionClass($className);

        $shortName = $reflection->getShortName();
        $schema->setDescription("Objeto $shortName gerado via DtoTransformer");

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $propertyName = $property->getName();
            $propertyType = $this->resolvePropertyType($property, $visited);

            $schema->addProperty($propertyName, $propertyType);

            if ($property->getType() && ! $property->getType()->allowsNull()) {
                $schema->addRequired([$propertyName]);
            }
        }

        return $schema;
    }

    private function resolvePropertyType(ReflectionProperty $property, array $visited = []): Type
    {
        $type = $property->getType();

        if (! $type) {
            return new StringType;
        }

        if ($type instanceof ReflectionUnionType) {
            $firstType = $type->getTypes()[0];
            /** @var ReflectionNamedType $firstType */
            $typeName = $firstType->getName();
        } elseif ($type instanceof ReflectionNamedType) {
            $typeName = $type->getName();
        } else {
            return new StringType;
        }

        if (is_a($typeName, DataCollection::class, true)) {
            $attributes = $property->getAttributes(DataCollectionOf::class);

            if (! empty($attributes)) {
                $dtoClass = $attributes[0]->getArguments()[0];

                $childSchema = $this->toSchema($dtoClass, $visited);

                $arrayType = new ArrayType;
                $arrayType->setItems($childSchema);

                if ($type->allowsNull()) {
                    $arrayType->nullable(true);
                }

                return $arrayType;
            }

            $arrayType = new ArrayType;
            $arrayType->setItems(new UnknownType);

            return $arrayType;
        }

        if (is_a($typeName, Carbon::class, true) || is_a($typeName, DateTimeInterface::class, true)) {
            $schema = new StringType;
            $schema->format('date-time');
            if ($type->allowsNull()) {
                $schema->nullable(true);
            }

            return $schema;
        }

        if (class_exists($typeName)) {

            if (! in_array($typeName, ['int', 'float', 'bool', 'string', 'array', 'mixed', 'object'])) {
                return $this->toSchema($typeName, $visited);
            }
        }

        $schema = match ($typeName) {
            'int' => new IntegerType,
            'float' => new NumberType,
            'bool' => new BooleanType,
            'array' => (new ArrayType)->setItems(new UnknownType),
            'string' => new StringType,
            'mixed' => new StringType,
            default => new StringType,
        };

        if ($type->allowsNull()) {
            $schema->nullable(true);
        }

        return $schema;
    }
}
