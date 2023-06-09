<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Contract;

use Psr\Http\Message\ResponseInterface;

interface DirectionInterface
{
    /**
     * @return mixed
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response);
}
