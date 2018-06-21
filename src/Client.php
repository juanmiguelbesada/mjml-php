<?php

namespace Mjml;

class Client
{
    const ENDPOINT = 'https://api.mjml.io/v1';

    /**
     * @var string
     */
    private $applicationId;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * Mjml constructor.
     *
     * @param string $applicationId
     * @param string $secretKey
     */
    public function __construct(string $applicationId, string $secretKey)
    {
        $this->applicationId = $applicationId;
        $this->secretKey = $secretKey;
    }

    /**
     * Render MJML to HTML.
     *
     * @param $mjml
     *
     * @return string The MJML markup to transpile to responsive HTML
     *
     * @throws Exception
     */
    public function render(string $mjml): string
    {
        $response = $this->request('/render', 'POST', json_encode(['mjml' => $mjml]));

        return $response['html'];
    }

    /**
     * @param $path
     * @param $method
     * @param $body
     * @param array|null $headers
     * @param array      $curlOptions
     *
     * @return mixed
     *
     * @throws Exception
     * @throws \RuntimeException
     */
    private function request(string $path, string $method, string $body, array $headers = null, array $curlOptions = []): array
    {
        if (!$headers) {
            $headers = [
                'Content-Type' => 'application/json',
            ];
        }

        $headers = array_map(function ($key, $value) {
            //If $value contains a ':' it is already in key:value format
            if (false !== strpos($value, ':')) {
                list($key, $value) = explode(':', $value);
            }

            return sprintf('%s: %s', $key, $value);
        }, array_keys($headers), $headers);

        $ch = curl_init(self::ENDPOINT.$path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, sprintf('%s:%s', $this->applicationId, $this->secretKey));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        foreach ($curlOptions as $option => $value) {
            curl_setopt($ch, $option, $value);
        }

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \RuntimeException(curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($response, true);
        if (json_last_error()) {
            throw new \RuntimeException(json_last_error_msg());
        }

        if (200 !== $statusCode) {
            $requestId = isset($response['requestId']) ? $response['requestId'] : null;
            $startedAt = isset($response['startedAt']) ? new \DateTime($response['startedAt']) : null;
            throw new Exception($response['message'], $statusCode, null, $requestId, $startedAt);
        }

        return $response;
    }
}
