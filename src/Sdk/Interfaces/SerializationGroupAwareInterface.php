<?php
declare(strict_types=1);

namespace LoyaltyCorp\SdkBlueprint\Sdk\Interfaces;

interface SerializationGroupAwareInterface
{
    /**
     * Set serializationGroup groups if request object needs. It should be an associative array with
     * request method as the key, validation groups as the value otherwise, this returned value will be ignored.
     *
     * For example,
     *
     * return [
     *    \LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\RequestAwareInterface::CREATE => ['registration']
     * ];
     *
     * @return mixed[]
     */
    public function serializationGroups(): array;
}
