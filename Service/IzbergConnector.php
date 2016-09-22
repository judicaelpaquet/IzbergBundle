<?php

/**
 * @author Paquet Judicaël <judicael.paquet@gmail.com>
 */

namespace judicaelpaquet\IzbergBundle\Service;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;

/**
 * Class IzbergConnector
 * @package judicaelpaquet\IzbergBundle\Service
 */
class IzbergConnector
{
    /**
     * @var string
     */
    private $izbergUrl;
    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var \Predis\Client
     */
    private $redis;
    /**
     * @var boolean
     */
    private $force = false;

    /**
     * IzbergConnector constructor.
     * @param string $izbergUrl
     * @param Client $httpClient
     * @param \Predis\Client $redis
     */
    public function __construct(string $izbergUrl, Client $httpClient, \Predis\Client $redis)
    {
        $this->izbergUrl = $izbergUrl;
        $this->httpClient = $httpClient;
        $this->redis = $redis;
    }

    /**
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $secretKey
     */
    public function connect(string $email = '', string $firstName = '', string $lastName = '', string $secretKey = '')
    {
        // If the izberg_sso key already exists in redis, we just have to exit.

        if ($this->getAuthentication() && $this->force == false) {
            return;
        }

        // Otherwise, we have to use izberg url to generate a new authentication.
        if ($this->getAuthentication() && !$this->force) {
            return;
        }

        // Otherwise, we have to izberg url to generate a new authentication.
        $request = $this->httpClient->get(
            $this->generateUrl($email, $firstName, $lastName, $secretKey)
        );

        if ($response = $request->send()) {
            $this->setAuthentication($response);
        }
    }

    /**
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $secretKey
     */
    public function forceAuthAndConnect(
        string $email = '',
        string $firstName = '',
        string $lastName = '',
        string $secretKey = ''
    ) {
        $this->setForce(true);
        $this->connect($email, $firstName, $lastName, $secretKey);
    }

    /**
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $secretKey
     * @return string
     */
    public function generateUrl(
        string $email = '',
        string $firstName = '',
        string $lastName = '',
        string $secretKey = ''
    ):string
    {
        $timestamp = time();
        $toCompose = [$email, $firstName, $lastName, $timestamp];
        $message_auth = hash_hmac('sha1', implode(";", $toCompose), $secretKey);

        $url = $this->izbergUrl;
        $url .= 'first_name=' . urlencode($firstName) . '&last_name=' . urlencode($lastName) . '&message_auth=' . $message_auth . '&email=' . urlencode($email);
        $url .= '&timestamp=' . $timestamp . '&is_staff=true';
        return $url;
    }

    /**
     * <<<<<<< HEAD
     * @param bool $force
     */
    public function setForce(bool $force)
    {
        $this->force = $force;
    }

    /**
     * @return bool
     */
    public function getForce():bool
    {
        return $this->force;
    }

    /**
     * @param Response $response
     */
    public function setAuthentication(Response $response)
    {
        $this->redis->set('izberg_sso', json_encode(
                json_decode($response->getBody(), true)
            )
        );
    }

    /**
     * @return array
     */
    public function getAuthentication()
    {
        return json_decode($this->redis->get('izberg_sso'), true);
    }
}

