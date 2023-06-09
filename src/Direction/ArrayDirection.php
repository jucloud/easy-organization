<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Direction;

use Psr\Http\Message\ResponseInterface;
use JuCloud\EasyOrganization\Contract\DirectionInterface;
use JuCloud\EasyOrganization\Contract\PackerInterface;
use JuCloud\EasyOrganization\Exception\Exception;
use JuCloud\EasyOrganization\Exception\InvalidResponseException;

class ArrayDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): array
    {
        if (is_null($response)) {
            throw new InvalidResponseException(Exception::RESPONSE_NONE);
        }

        $body = (string) $response->getBody();

        if (!is_null($result = $packer->unpack($body))) {
            return $result;
        }

        throw new InvalidResponseException(Exception::UNPACK_RESPONSE_ERROR, 'Unpack Response Error', ['body' => $body, 'response' => $response]);
    }
}
