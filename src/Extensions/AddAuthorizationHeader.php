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

class AddAuthorizationHeader extends OperationExtension
{
    public function handle(Operation $operation, RouteInfo $routeInfo): void
    {
        $middlewares = $routeInfo->route->gatherMiddleware();

        $hasAuthMiddleware = collect($middlewares)->contains(function ($middleware) {
            return str_starts_with(strtolower(class_basename($middleware)), 'auth');
        });

        if (! $hasAuthMiddleware) {
            return;
        }

        $operation->addParameters([
            Parameter::make('Authorization', 'header')
                ->setSchema(Schema::fromType(new StringType))
                ->description('Bearer token for authentication')
                ->required(true)
                ->example('Bearer '.Str::uuid()->toString()),
        ]);
    }
}
