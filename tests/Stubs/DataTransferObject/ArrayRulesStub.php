<?php
declare(strict_types=1);

namespace Tests\LoyaltyCorp\SdkBlueprint\Stubs\DataTransferObject;

use LoyaltyCorp\SdkBlueprint\Sdk\DataTransferObject;

class ArrayRulesStub extends DataTransferObject
{
    protected function hasAttributes(): array
    {
        return ['attribute'];
    }

    public function hasValidationRules(): array
    {
        return ['attribute' => ['required', 'numeric']];
    }
}
