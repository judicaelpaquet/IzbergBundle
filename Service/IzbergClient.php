<?php

namespace judicaelpaquet\IzbergBundle\Service;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Class IzbergClient
 * @package judicaelpaquet\IzbergBundle\Service
 */
abstract class IzbergClient
{
    /**
     * @var IzbergConnector
     */
    protected $izbergConnector;
    /**
     * @var Client
     */
    protected $httpClient;
    /**
     * @var string
     */
    protected static $baseUri = 'https://api.sandbox.iceberg.technology';
    /**
     * @var string
     */
    protected $nameSpace;

    /**
     * @param IzbergConnector $izbergConnector
     * @param string $nameSpace
     */
    public function setIzbergConnector(IzbergConnector $izbergConnector)
    {
        $this->izbergConnector = $izbergConnector;
    }

    /**
     * @param Client $httpClient
     */
    public function setHttpClient(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->httpClient->setBaseUrl(self::$baseUri);
    }

    /**
     * @param string $namespace
     */
    public function setNameSpace(string $nameSpace)
    {
       $this->nameSpace = $nameSpace;
    }

    /**
     * @return array
     */
    public function generateHeader()
    {
        $namespace = '';
        if ($this->izbergConnector->getAuthentication()['username'] == 'Anonymous') {
            $namespace = $this->nameSpace;
            $namespace = ':'.$namespace;
        }
        return [
            'Content-Type' => 'application/json',
            'Authorization' => vsprintf("Bearer %s%s:%s", [$this->izbergConnector->getAuthentication()['username'], $namespace, $this->izbergConnector->getAuthentication()['api_key']]),
        ];
    }

    /**
     * @param string $url
     * @return array
     */
    protected function getObjects(string $url)
    {
        $request = $this->httpClient->get($url);
        $request->setHeaders($this->generateHeader());
        return $request->send()->json()['objects'];
    }

    /**
     * @param string $url
     * @param int $id
     * @return string
     */
    protected function getObject(string $url, int $id)
    {
        $request = $this->httpClient->get($url.'/'.$id);
        $request->setHeaders($this->generateHeader());

        try {
            return $request->send()->json();
        } catch(BadResponseException $e) {
            echo $e->getMessage();
            echo 'There is no object with this id:'.$id;
        }
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getObjectSchema(string $url)
    {
        $request = $this->httpClient->get($url.'/schema');
        $request->setHeaders($this->generateHeader());
        return $request->send()->json();
    }

    /**
     * @param $functionName
     * @param $params
     * @return array|string
     */
    public function __call($functionName, $params)
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $apiNameCalled = str_replace('Izberg', '', $className);

        switch($functionName) {
            case 'get'.$apiNameCalled.'s':
                return $this->getObjects(static::$url);
                break;
            case 'get'.$apiNameCalled:
                return $this->getObject(static::$url, $params[0]);
                break;
            case 'get'.$apiNameCalled.'Schema':
                return $this->getObjectSchema(static::$url);
                break;
        }
    }
}
