<?php
declare(strict_types=1);

namespace Tests\LoyaltyCorp\SdkBlueprint\Stubs\DataTransferObject\Rules;

use Tests\LoyaltyCorp\SdkBlueprint\Stubs\DataTransferObject\DataTransferObjectStub;

class InstanceStub extends BaseStub
{
    protected function getRuleString(): string
    {
        return 'instance:' . DataTransferObjectStub::class;
    }
}
