<?php

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\GeneralMaxpayException;
use Psr\Log\LoggerInterface;

/**
 * Class CurlClient
 * @package Maxpay\Lib\Util
 */
class CurlClient implements ClientInterface
{
    /** @var string */
    private $url;

    /** @var LoggerInterface */
    private $logger;

    /** @var ValidatorInterface */
    private $validator;

    const DEFAULT_CONNECT_TIMEOUT = 7500;

    /**
     * @param $url
     * @param LoggerInterface $logger
     * @throws GeneralMaxpayException
     */
    public function __construct($url, LoggerInterface $logger)
    {
        $this->validator = new Validator();
        $this->url = $this->validator->validateString('url', $url);
        $this->logger = $logger;
    }

    /**
     * @param mixed[] $data
     * @return mixed[]
     * @throws GeneralMaxpayException
     */
    public function send(array $data)
    {
        $start = microtime(true);
        $curl = curl_init();
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
                'packetSize' => strlen($result),
                'time' => microtime(true) - $start,
            ]
        );

        if ($errno === CURLE_OPERATION_TIMEOUTED) {
            $e = new GeneralMaxpayException('Client timeout');
            $this->logger->error(
                $e->getMessage(),
                [
                    'exception' => $e
                ]
            );

            throw $e;
        } elseif ($errno === CURLE_SSL_CACERT
            || $errno === CURLE_SSL_CERTPROBLEM
            || $errno === CURLE_SSL_CIPHER
            || $errno === CURLE_SSL_CONNECT_ERROR
            || $errno === CURLE_SSL_PEER_CERTIFICATE
            || $errno === CURLE_SSL_ENGINE_NOTFOUND
            || $errno === CURLE_SSL_ENGINE_SETFAILED
        ) {
            $e = new GeneralMaxpayException('Client SSL error, code ' . $error, null, intval($errno));
            $this->logger->error(
                $e->getMessage(),
                ['exception' => $e, 'errno' => $errno]
            );

            throw $e;
        } elseif ($errno !== CURLE_OK) {
            $e = new GeneralMaxpayException('Client error ' . $error, null, intval($errno));
            $this->logger->error(
                $e->getMessage(),
                ['exception' => $e, 'errno' => $errno]
            );

            throw $e;
        }

        if ($result === false) {
            $e = new GeneralMaxpayException(sprintf('Curl error. Received status %s, curl error %s', $status, $error));
            $this->logger->error(
                $e->getMessage(),
                ['exception' => $e, 'status' => $status]
            );

            throw $e;
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

    /**
     * @param string $stringAnswer
     * @return mixed[]
     * @throws GeneralMaxpayException
     */
    private function decode($stringAnswer)
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
