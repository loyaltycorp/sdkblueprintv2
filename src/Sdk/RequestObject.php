<?php
declare(strict_types=1);

namespace LoyaltyCorp\SdkBlueprint\Sdk;

abstract class RequestObject
{
    abstract public function expectObject(): ?string;
    abstract public function getUris(): array;
}
