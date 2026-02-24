<?php

declare(strict_types=1);

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Psr\Log\LoggerInterface;

class CurlClient implements ClientInterface
{
    private string $url;

    private LoggerInterface $logger;

    private ValidatorInterface $validator;

    private const DEFAULT_CONNECT_TIMEOUT = 7500;

    public function __construct(string $url, LoggerInterface $logger)
    {
        $this->validator = new Validator();
        $this->url = $this->validator->validateString('url', $url);
        $this->logger = $logger;
    }

    /**
     * @param array $data
     * @return array
     * @throws GeneralMaxpayException
     */
    public function send(array $data): array
    {
        $start = microtime(true);
        $curl = curl_init();
        // @phpstan-ignore-next-line
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, self::DEFAULT_CONNECT_TIMEOUT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        curl_close($curl);

        $this->logger->info(
            'Received answer',
            [
                'packetSize' => is_string($result) ? strlen($result) : 0,
                'time' => microtime(true) - $start,
            ]
        );

        if ($errno !== CURLE_OK) {
            $exception = match (true) {
                $errno === CURLE_OPERATION_TIMEOUTED => new GeneralMaxpayException('Client timeout'),

                in_array($errno, [
                    CURLE_SSL_CACERT,
                    CURLE_SSL_CERTPROBLEM,
                    CURLE_SSL_CIPHER,
                    CURLE_SSL_CONNECT_ERROR,
                    CURLE_SSL_PEER_CERTIFICATE,
                    CURLE_SSL_ENGINE_NOTFOUND,
                    CURLE_SSL_ENGINE_SETFAILED,
                ], true) => new GeneralMaxpayException(
                    'Client SSL error, code ' . $error,
                    null,
                    $errno
                ),

                default => new GeneralMaxpayException(
                    'Client error ' . $error,
                    null,
                    $errno
                ),
            };

            $this->logger->error(
                $exception->getMessage(),
                ['exception' => $exception, 'errno' => $errno]
            );

            throw $exception;
        }

        if ($result === false) {
            $e = new GeneralMaxpayException(sprintf('Curl error. Received status %s, curl error %s', $status, $error));
            $this->logger->error(
                $e->getMessage(),
                ['exception' => $e, 'status' => $status]
            );

            throw $e;
        }
        if (!is_string($result)) {
            throw new GeneralMaxpayException('Curl result should be a string');
        }

        try {
            $result = $this->decode($result);
        } catch (\Exception $exception) {
            $error = new GeneralMaxpayException('Failed to decode answer', $exception);
            $this->logger->error(
                $error->getMessage(),
                ['exception' => $error]
            );
            throw $error;
        }

        return $result;
    }

    private function decode(string $stringAnswer): array
    {
        $stringAnswer = $this->validator->validateString('answer', $stringAnswer);

        $data = json_decode($stringAnswer, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $message = 'JSON unserialization error';
            if (function_exists('json_last_error_msg')) {
                $message .= ' ' . json_last_error_msg();
            }

            $e = new GeneralMaxpayException($message, null, json_last_error());
            $this->logger->error(
                $e->getMessage(),
                ['exception' => $e]
            );

            throw $e;
        }

        $this->logger->info('Packet successfully decoded', []);

        return $data;
    }
}
