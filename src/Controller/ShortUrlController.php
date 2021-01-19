<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO;
use App\Entity\User;
use App\Service\ShortUrlKeeper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Annotation\Route;

class ShortUrlController extends AbstractController
{
    private ShortUrlKeeper $shortUrls;

    public function __construct(ShortUrlKeeper $shortUrls)
    {
        $this->shortUrls = $shortUrls;
    }

    /**
     * @Route("api/short-url", methods={"POST"})
     */
    public function create(DTO\UrlToCut $data): HttpFoundation\Response
    {
        if (null === ($user = $this->getUser()) || !$user instanceof User) {
            return $this->json(['error' => 'wow wow buddy, don\'t rush']); // this is impossible
        }

        return $this->json(DTO\Url::fromShortUrlEntity($this->shortUrls->new($user->getId(), $data->original)));
    }

    /**
     * @Route("api/short-url/", methods={"GET"})
     */
    public function list(): HttpFoundation\Response
    {
        return $this->json($this->shortUrls->list());
    }

    /**
     * stats options:
     *  - group by user-id string
     *  - group by created-at timestamp
     *
     * by default returns users with created urls statistics
     *
     * @example
     *     www.shorty.domain/api/short-url/statistics?user-id=uuid_string
     *     www.shorty.domain/api/short-url/statistics?created-at=timestamp
     *     www.shorty.domain/api/short-url/statistics?user-id=uuid_string&created-at=timestamp
     *
     * @Route("api/short-url/statistics", methods={"GET"})
     */
    public function stats(DTO\Filter $filter): HttpFoundation\Response
    {
        return $this->json([$this->shortUrls->stats($filter)]);
    }

    /**
     * @Route("api/short-url/redirect/{hash}", methods={"GET"})
     */
    public function jump(string $hash): HttpFoundation\Response
    {
        return $this->redirect($this->shortUrls->getById($hash)->getOriginal());
    }
}
