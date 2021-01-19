<?php

declare(strict_types=1);

namespace App\Resolver;

use App\DTO\Filter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FilterResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Filter::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield Filter::fromTimestamp($request->get(Filter::USER_ID), $request->get(Filter::CREATED_AT));
    }
}
