<?php
declare(strict_types=1);

namespace LoyaltyCorp\SdkBlueprint\Sdk\Interfaces;

interface RequestValidationGroupAwareInterface
{
    /**
     * Set validation groups if request object needs.
     *
     * @return string[]
     */
    public function validationGroups(): array;
}
