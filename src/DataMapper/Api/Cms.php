<?php
namespace Ittweb\AccelaSearch\ProductMapper\DataMapper\Api;
use \Ittweb\AccelaSearch\ProductMapper\Cms as Subject;

class Cms {
    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function search(): array {
        $response = $this->client->get(Request::fromPath('/API/cms'));
        $cms = [];
        foreach ($response->getBodyAsArray() as $cms_data) {
            $cms[] = new Subject($cms_data['id'], $cms_data['label'], $cms_data['version']);
        }
        return $cms;
    }
}