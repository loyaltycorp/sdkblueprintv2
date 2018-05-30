<?php
declare(strict_types=1);

namespace Tests\LoyaltyCorp\SdkBlueprint\DataTransferObjects\Requests;

use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\RequestInterface;
use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\RequestMethodInterface;
use LoyaltyCorp\SdkBlueprint\Sdk\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;
use Tests\LoyaltyCorp\SdkBlueprint\DataTransferObjects\CreditCard;
use Tests\LoyaltyCorp\SdkBlueprint\DataTransferObjects\Gateway;
use Tests\LoyaltyCorp\SdkBlueprint\DataTransferObjects\Transaction;

class CreditCardAuthorise extends RequestObject
{
    /**
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $gateway;

    /**
     * @Assert\Valid()
     */
    private $creditCard;

    /**
     * @return mixed
     */
    public function getGateway(): Gateway
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     */
    public function setGateway(Gateway $gateway): void
    {
        $this->gateway = $gateway;
    }

    /**
     * @return mixed
     */
    public function getCreditCard(): CreditCard
    {
        return $this->creditCard;
    }

    /**
     * @param mixed $creditCard
     */
    public function setCreditCard(CreditCard $creditCard): void
    {
        $this->creditCard = $creditCard;
    }

    public function expectObject(): ?string
    {
        return Transaction::class;
    }

    public function getUris(): array
    {
        return [RequestMethodInterface::CREATE => 'create_uri'];
    }

    public function getOptions(): array
    {
        return [
            RequestMethodInterface::CREATE => ['json'=> ['data']]
        ];
    }

    public function getValidationGroups(): array
    {
        return [];
    }
}
