<?php

declare(strict_types=1);

namespace Victormgomes\ScrambleExtensions\Support;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use ReflectionMethod;
use Throwable;

class AstHelper
{
    public static function getStatements(ReflectionMethod $reflectionMethod): ?array
    {
        $fileName = $reflectionMethod->getFileName();
        if (! $fileName || ! file_exists($fileName)) {
            return null;
        }

        $code = file_get_contents($fileName);
        $parser = (new ParserFactory)->createForHostVersion();

        try {
            $ast = $parser->parse($code);
        } catch (Throwable $e) {
            return null;
        }

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new NameResolver);

        return $traverser->traverse($ast);
    }

    public static function resolveName(Node $node): ?string
    {
        if ($node instanceof ClassConstFetch && $node->class instanceof Name) {
            return $node->class->getAttribute('resolvedName')?->toString() ?? $node->class->toString();
        }

        return null;
    }
}
