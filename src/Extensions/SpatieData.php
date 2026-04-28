<?php

declare(strict_types=1);

namespace Victormgomes\ScrambleExtensions\Extensions;

use Dedoc\Scramble\Extensions\TypeToSchemaExtension;
use Dedoc\Scramble\Support\Generator\Types as OpenApiTypes;
use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Type;
use Spatie\LaravelData\Data;
use Victormgomes\ScrambleExtensions\Support\DtoTransformer;

class SpatieData extends TypeToSchemaExtension
{
    public function shouldHandle(Type $type): bool
    {
        if (! $type instanceof ObjectType) {
            return false;
        }

        /** @var ObjectType $type */
        return is_a($type->name, Data::class, true);
    }

    public function toSchema(Type $type): ?OpenApiTypes\Type
    {
        /** @var ObjectType $type */
        $transformer = new DtoTransformer;

        return $transformer->toSchema($type->name);
    }
}
