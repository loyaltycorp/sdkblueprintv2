<?php
declare(strict_types=1);

namespace LoyaltyCorp\SdkBlueprint\Sdk\Handlers;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\EntityInterface;
use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\Factories\SerializerFactoryInterface;
use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\Factories\UrnFactoryInterface;
use LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\Handlers\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

final class RequestHandler implements RequestHandlerInterface
{
    /**
     * Guzzle http client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    private $httpClient;

    /**
     * Symfony serializer.
     *
     * @var \Symfony\Component\Serializer\Serializer
     */
    private $serializer;

    /**
     * Urn factory instance.
     *
     * @var \LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\Factories\UrnFactoryInterface
     */
    private $urnFactory;

    /**
     * Construct request handler.
     *
     * @param \GuzzleHttp\ClientInterface $client Guzzle client
     * @param \LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\Factories\SerializerFactoryInterface $serializerFactory
     * @param \LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\Factories\UrnFactoryInterface $urnFactory
     */
    public function __construct(
        GuzzleClientInterface $client,
        SerializerFactoryInterface $serializerFactory,
        UrnFactoryInterface $urnFactory
    ) {
        $this->httpClient = $client;
        $this->serializer = $serializerFactory->create();
        $this->urnFactory = $urnFactory;
    }

    /**
     * @inheritdoc
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function delete(EntityInterface $entity, ?string $apikey = null): bool
    {
        $this->executeAndRespond($entity, self::DELETE, $apikey);

        return true;
    }

    /**
     * @inheritdoc
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function get(EntityInterface $entity, ?string $apikey = null): EntityInterface
    {
        return $this->executeAndRespond($entity, self::GET, $apikey);
    }

    /**
     * @inheritdoc
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function list(EntityInterface $entity, ?string $apikey = null): array
    {
        return $this->executeAndRespond($entity, self::LIST, $apikey);
    }

    /**
     * @inheritdoc
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function post(EntityInterface $entity, ?string $apikey = null): EntityInterface
    {
        return $this->executeAndRespond($entity, self::CREATE, $apikey);
    }

    /**
     * @inheritdoc
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function put(EntityInterface $entity, ?string $apikey = null): EntityInterface
    {
        return $this->executeAndRespond($entity, self::UPDATE, $apikey);
    }

    /**
     * Execute request.
     *
     * @param string $method Request method
     * @param string $uri URI
     * @param mixed[]|null $body Request body
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function execute(string $method, string $uri, ?array $body = null): ResponseInterface
    {
        if (\in_array(\mb_strtolower($method), [self::GET, self::LIST], true) === true) {
            return $this->httpClient->request(self::GET, $uri, $body ?? []);
        }

        return $this->httpClient->request($method, $uri, $body ?? []);
    }

    /**
     * Execute request and respond.
     *
     * @param \LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\EntityInterface $entity
     * @param string $method Request method
     * @param string|null $apikey Api key
     *
     * @return mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    private function executeAndRespond(EntityInterface $entity, string $method, ?string $apikey = null)
    {
        $options = [];

        if ($apikey !== null) {
            $options = \array_merge($options, [
                'auth' => [$apikey, null]
            ]);
        }

        // get endpoint uri based on request method
        // @todo: urn factory needs to reviewed and improvised
        $urn = $this->urnFactory->create($entity->getUris()[$method] ?? '');

        $response = $this->execute($method, $urn, $this->getBody($entity, $method, $options));

        if (\mb_strtolower($method) === self::DELETE) {
            return null;
        }

        $type = \mb_strtolower($method) === self::LIST ?
            \sprintf('%s[]', \get_class($entity)) : \get_class($entity);

        return $this->serializer->deserialize($response->getBody()->getContents(), $type, 'json');
    }

    /**
     * Generate the http body.
     *
     * @param \LoyaltyCorp\SdkBlueprint\Sdk\Interfaces\EntityInterface $entity
     * @param string $group
     * @param mixed[]|null $options
     *
     * @return mixed[]
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    private function getBody(EntityInterface $entity, string $group, ?array $options = null): array
    {
        $normalize = $this->serializer->normalize($entity, null, ['groups' => [$group]]);

        return \array_merge([
            'json' => $this->getFilterOptions($normalize)
        ], $options ?? []);
    }

    /**
     * Recursively filter options array, remove key value pairs when value is null.
     *
     * @param mixed[] $options
     *
     * @return mixed[]
     */
    private function getFilterOptions(array $options): array
    {
        $original = $options;

        $data = \array_filter($options);

        $data = \array_map(function ($element) {
            return \is_array($element) ? $this->getFilterOptions($element) : $element;
        }, $data);

        return $original === $data ? $data : $this->getFilterOptions($data);
    }
}