<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Direction;

use Psr\Http\Message\ResponseInterface;
use JuCloud\EasyOrganization\Contract\DirectionInterface;
use JuCloud\EasyOrganization\Contract\PackerInterface;

class NoHttpRequestDirection implements DirectionInterface
{
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        return $response;
    }
}
