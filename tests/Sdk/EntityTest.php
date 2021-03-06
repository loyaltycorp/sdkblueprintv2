<?php
declare(strict_types=1);

namespace Tests\LoyaltyCorp\SdkBlueprint\Sdk;

use LoyaltyCorp\SdkBlueprint\Sdk\Entity;
use LoyaltyCorp\SdkBlueprint\Sdk\Exceptions\InvalidMethodCallException;
use Tests\LoyaltyCorp\SdkBlueprint\Stubs\Entities\EntityStub;
use Tests\LoyaltyCorp\SdkBlueprint\Stubs\Entities\UserStub;
use Tests\LoyaltyCorp\SdkBlueprint\TestCase;

/**
 * @covers \LoyaltyCorp\SdkBlueprint\Sdk\Entity
 */
final class EntityTest extends TestCase
{
    /**
     * Test the serialise method.
     *
     * @return void
     */
    public function testJsonSerialize(): void
    {
        // Build a stub that requires recursive serialisation
        $entity = new UserStub([
            'apikeys' => [
                new UserStub(['type' => 'child', 'userId' => '2']),
                new UserStub(['type' => 'child', 'userId' => '3']),
            ],
            'email' => 'test@test.com',
            'type' => 'parent',
            'userId' => '1',
        ]);

        // We expect an array as a result
        $expected = [
            'apikeys' => [
                [
                    'apikeys' => null,
                    'email' => null,
                    'type' => 'child',
                    'userId' => '2',
                ],
                [
                    'apikeys' => null,
                    'email' => null,
                    'type' => 'child',
                    'userId' => '3',
                ],
            ],
            'email' => 'test@test.com',
            'type' => 'parent',
            'userId' => '1',
        ];

        self::assertSame($expected, $entity->jsonSerialize());
    }

    /**
     * Test the __call method.
     *
     * @return void
     */
    public function testCallMagicMethod(): void
    {
        $this->expectException(InvalidMethodCallException::class);

        $entity = new EntityStub(['entityId' => 'entity-id']);

        self::assertCount(5, $entity->uris());
        self::assertSame('entity-id', $entity->getEntityId());

        $entity->setEntityId('updated-id');

        self::assertSame('updated-id', $entity->getEntityId());

        /** @noinspection PhpUndefinedMethodInspection method call handled by magic call */
        self::assertTrue($entity->hasEntityId('EntityId'));

        /** @noinspection PhpUndefinedMethodInspection method call handled by magic call */
        self::assertTrue($entity->isEntityId('EntityId'));

        /** @noinspection PhpUndefinedMethodInspection test itself is for undefined method */
        $entity->unknownMethod();
    }

    /**
     * Test __isset method.
     *
     * @return void
     */
    public function testIssetMagicMethod(): void
    {
        $entity = new EntityStub();

        /** @noinspection PhpUndefinedFieldInspection test itself is for undefined field */
        self::assertFalse(isset($entity->unknownProperty));
    }

    /**
     * Test has method.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function testHas(): void
    {
        $method = $this->getMethodAsPublic(Entity::class, 'has');

        self::assertFalse($method->invokeArgs(new EntityStub(), ['unknownProperty']));
    }
}
