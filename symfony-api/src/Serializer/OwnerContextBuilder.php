<?php

namespace App\Serializer;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use App\Entity\TaskList;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class OwnerContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    private $tokenStorage;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $currentUser = $this->tokenStorage->getToken()?->getUser() ?? null;
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $object = $request->attributes->get('data');
        $isLoggedIn = $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY');
        $resourceClass = $context['resource_class'] ?? null;

        if (($resourceClass instanceof Task || $resourceClass instanceof TaskList) &&  $object->getAuthor() === $currentUser && $isLoggedIn) {
            $context['groups'][] = 'owner:get';
        }

        return $context;
    }
}
