<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UrlToCut
{
    /**
     * @Assert\Length(max=555)
     */
    public string $original;

    public function __construct(string $original)
    {
        $this->original = $original;
    }
}
