<?php
declare(strict_types=1);

namespace common\CQS\Modules\Smspilot\Infrastructure\Adapter\Http;

use common\CQS\Modules\Smspilot\Application\Command\SendSms\SendSmsCommand;
use common\CQS\Modules\Smspilot\Domain\Interface\SmspilotHttpClientInterface;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use yii\helpers\ArrayHelper;

class SmspilotHttpClient implements SmspilotHttpClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string              $apiKey,
        private readonly string              $acceptFormat = 'json',
    )
    {
    }

    public function runSendSmsCommand(SendSmsCommand $command): array
    {
        return $this->runRequest(
            'GET',
            "/api.php",
            [
                'query' => ArrayHelper::merge(
                    $command->toArray(),
                    $this->getAdditionalQueryParam()
                ),
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
    }

    private function getAdditionalQueryParam(): array
    {
        return [
            'apikey' => $this->apiKey,
            'format' => $this->acceptFormat,
        ];
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @param int $expectedStatus
     * @return array
     * @throws \JsonException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function runRequest(
        string $method,
        string $url,
        array  $options = [],
        int    $expectedStatus = 200
    ): array
    {
        $response = $this->httpClient->request($method, $url, $options);

        if ($expectedStatus !== $response->getStatusCode()) {
            throw new Exception("Error request: {$response->getContent(false)}", $response->getStatusCode());
        }

        return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}