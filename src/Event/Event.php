<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Event;

use JuCloud\EasyOrganization\Rocket;

class Event
{
    public ?Rocket $rocket = null;

    public function __construct(?Rocket $rocket = null)
    {
        $this->rocket = $rocket;
    }
}
