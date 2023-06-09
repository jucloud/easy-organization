<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization;

use JsonSerializable as JsonSerializableInterface;
use JuCloud\EasyOrganization\Supports\Traits\Accessable;
use JuCloud\EasyOrganization\Supports\Traits\Arrayable;
use JuCloud\EasyOrganization\Supports\Traits\Serializable;

class Request extends \GuzzleHttp\Psr7\Request implements JsonSerializableInterface
{
    use Accessable;
    use Arrayable;
    use Serializable;

    public function toArray(): array
    {
        return [
            'url' => $this->getUri()->__toString(),
            'method' => $this->getMethod(),
            'headers' => $this->getHeaders(),
            'body' => (string) $this->getBody(),
        ];
    }
}
