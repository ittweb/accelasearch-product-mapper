<?php
namespace Ittweb\AccelaSearch\ProductMapper\DataMapper\Api;
use \RuntimeException;
use \Ittweb\AccelaSearch\ProductMapper\Collector as Subject;

class Collector {
    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function read(): Subject {
        $response = $this->client->get(Request::fromPath('/API/collector'));
        $body = $response->getBodyAsArray();
        if (isset($body['status']) && $body['status'] === 'ERROR') {
            throw new RuntimeException($body['message']);
        }
        return new Subject(
            $body['hostname'],
            $body['name'],
            $body['username'],
            $body['password']
        );
    }
}