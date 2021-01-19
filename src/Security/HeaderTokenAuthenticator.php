<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\AuthTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class HeaderTokenAuthenticator extends AbstractGuardAuthenticator
{
    private const X_AUTH_TOKEN = 'X-AUTH-TOKEN';
    private AuthTokenRepository $tokens;

    public function __construct(AuthTokenRepository $tokens)
    {
        $this->tokens = $tokens;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has(self::X_AUTH_TOKEN);
    }

    public function getCredentials(Request $request): string
    {
        return $request->headers->get(self::X_AUTH_TOKEN);
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (null === $credentials) {
            return null;
        }

        $token = $this->tokens->find($credentials);
        if (null === $token || $token->isExpired()) {
            return null;
        }

        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $data = ['message' => strtr($exception->getMessageKey(), $exception->getMessageData())];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse(['message' => 'Authentication Required'], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
