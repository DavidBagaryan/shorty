<?php

declare(strict_types=1);

namespace App\Resolver;

use App\DTO\Filter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class DTOArgumentValueResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return stripos($argument->getType(), 'App\\DTO') === 0
            && $argument->getType() !== Filter::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
    }
}
