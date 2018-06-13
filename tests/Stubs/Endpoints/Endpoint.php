<?php
declare(strict_types=1);

namespace Tests\LoyaltyCorp\SdkBlueprint\Stubs\Endpoints;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DiscriminatorMap(typeProperty="type", mapping={
 *    "bank_account"="Tests\LoyaltyCorp\SdkBlueprint\Stubs\Endpoints\BankAccount",
 *    "credit_card"="Tests\LoyaltyCorp\SdkBlueprint\Stubs\Endpoints\CreditCardEndpoint"
 * })
 */
abstract class Endpoint
{
    /**
     * The id.
     *
     * @var null|string $id
     */
    private $id;

    /**
     * Type of endpoint.
     *
     * @var null|string $type
     */
    private $type;

    /**
     * The pan.
     *
     * @var null|string $pan
     */
    private $pan;

    /**
     * Get id.
     *
     * @return mixed
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Get pan.
     *
     * @return null|string
     */
    public function getPan(): ?string
    {
        return $this->pan;
    }

    /**
     * Get type of endpoint.
     *
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set pan.
     *
     * @param null|string $pan
     *
     * @return void
     */
    public function setPan(?string $pan): void
    {
        $this->pan = $pan;
    }

    /**
     * Set type.
     *
     * @param null|string $type
     *
     * @return void
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}