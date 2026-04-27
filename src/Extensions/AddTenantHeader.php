<?php

declare(strict_types=1);

namespace Victormgomes\ScrambleExtensions\Extensions;

use Dedoc\Scramble\Extensions\OperationExtension;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Parameter;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Dedoc\Scramble\Support\RouteInfo;
use Illuminate\Support\Str;

class AddTenantHeader extends OperationExtension
{
    public function handle(Operation $operation, RouteInfo $routeInfo): void
    {
        $middlewares = $routeInfo->route->gatherMiddleware();

        $hasTenantMiddleware = collect($middlewares)->contains(function ($middleware) {
            return str_starts_with(strtolower(class_basename($middleware)), 'initializetenancy');
        });

        if (! $hasTenantMiddleware) {
            return;
        }

        $operation->addParameters([
            Parameter::make('X-tenant', 'header')
                ->setSchema(
                    Schema::fromType(new StringType)
                )
                ->description('Tenant ID')
                ->required(true)
                ->example(Str::uuid()->toString()),
        ]);
    }
}
