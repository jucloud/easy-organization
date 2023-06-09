<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Contract;

interface PackerInterface
{
    public function pack(array $payload): string;

    public function unpack(string $payload): ?array;
}
