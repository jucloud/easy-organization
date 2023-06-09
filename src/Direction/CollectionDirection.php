<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Direction;

use Psr\Http\Message\ResponseInterface;
use JuCloud\EasyOrganization\Contract\DirectionInterface;
use JuCloud\EasyOrganization\Contract\PackerInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Supports\Collection;

class CollectionDirection implements DirectionInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): Collection
    {
        return new Collection(
            EasyOrganization::get(ArrayDirection::class)->parse($packer, $response)
        );
    }
}
