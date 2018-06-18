<?php
declare(strict_types=1);

namespace Tests\LoyaltyCorp\SdkBlueprint\Stubs\Requests;

use LoyaltyCorp\SdkBlueprint\Sdk\BaseDataTransferObject;
use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\RequestMethodInterface;
use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\RequestObjectInterface;
use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\RequestOptionAwareInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SuppressWarnings(PHPMD.ShortVariable) id is a required attribute name
 * in order to be used by normalization and de-normalization correctly.
 *
 * @method null|string getId()
 * @method null|string getName()
 * @method null|string getEmail()
 * @method mixed[] getEwallets()
 * @method self setEwallets(array $ewallets)
 */
class User extends BaseDataTransferObject implements RequestObjectInterface, RequestOptionAwareInterface
{
    /**
     * User id
     *
     * @Assert\NotBlank(groups={"delete"})
     *
     * @Groups({"delete"})
     *
     * @var null|string
     */
    protected $id;

    /**
     * Name.
     *
     * @Assert\NotBlank(groups={"update","create"})
     *
     * @Groups({"create", "update"})
     *
     * @var null|string
     */
    protected $name;

    /**
     * Email.
     *
     * @Assert\NotBlank(groups={"update","create"})
     *
     * @Groups({"create", "update"})
     *
     * @var null|string
     */
    protected $email;

    /**
     * Ewallets.
     *
     * @Groups({"create", "update"})
     *
     * @var \Tests\LoyaltyCorp\SdkBlueprint\Stubs\Requests\Ewallet[]
     */
    protected $ewallets;

    /**
     * Post code.
     *
     * @Groups({"create", "update"})
     *
     * @var null|int
     */
    protected $postCode;

    /**
     * Add ewallet object into the collection.
     *
     * @param \Tests\LoyaltyCorp\SdkBlueprint\Stubs\Requests\Ewallet $ewallet
     *
     * @return void
     */
    public function addEwallet(Ewallet $ewallet): void
    {
        $this->ewallets[] = $ewallet;
    }

    /**
     * {@inheritdoc}
     */
    public function expectObject(): string
    {
        return self::class;
    }

    /**
     * {@inheritdoc}
     */
    public function options(): array
    {
        return [
            'debug' => true
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function uris(): array
    {
        return [
            RequestMethodInterface::CREATE => 'create_uri',
            RequestMethodInterface::DELETE => 'delete_uri',
            RequestMethodInterface::GET => 'get_uri',
            RequestMethodInterface::LIST => 'list_uri',
            RequestMethodInterface::UPDATE => 'update_uri'
        ];
    }
}
