<?php
namespace AccelaSearch\ProductMapper\DataMapper\Api;
use \RuntimeException;

class Shop {
    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function index(int $shop_identifier): self {
        $response = $this->client->post(Request::fromPath('/API/shops/' . $shop_identifier . '/index'));
        $body = $response->getBodyAsArray();
        if (isset($body['status']) && $body['status'] === 'ERROR') {
            throw new RuntimeException($body['message']);
        }
        return $this;
    }
}
