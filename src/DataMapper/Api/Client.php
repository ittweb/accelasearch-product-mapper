<?php
namespace Ittweb\AccelaSearch\ProductMapper\DataMapper\Api;
use \UnexpectedValueException;

class Client {
    public const DEFAULT_BASE_URL = 'https://svc11.accelasearch.io';
    private $base_url;
    private $api_key;

    public function __construct(string $base_url, string $api_key) {
        $this->base_url = $base_url;
        $this->api_key = $api_key;
    }

    public static function fromApiKey(string $api_key): self {
        return new Client(self::DEFAULT_BASE_URL, $api_key);
    }

    public function getApiKey(): string {
        return $this->api_key;
    }

    public function setApiKey(string $api_key): self {
        $this->api_key = $api_key;
        return $this;
    }

    public function get(Request $request): Response {
        return $this->request($request, 'GET');
    }

    public function post(Request $request): Response {
        return $this->request($request, 'POST');
    }

    public function out(Request $request): Response {
        return $this->request($request, 'PUT');
    }

    public function delete(Request $request): Response {
        return $this->request($request, 'DELETE');
    }

    private function request(Request $request, string $method): Response {
        $url = $this->base_url . $request->getPath();
        if ($method === 'GET' || $method === 'DELETE') {
            $url .= http_build_query($request->getParametersAsArray());
        }
        $request_headers = ['X-accelasearch-apikey:' . $this->api_key];
        foreach ($request->getHeadersAsArray() as $key => $value) {
            $request_headers[] = $key . ': ' . $value;
        }
        $headers = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $request_headers,
            /*
            CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) {
                    return $len;
                }
                $headers[strtolower(trim($header[0]))][] = trim($header[1]);
                return $len;
            }
            */
        ));
        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getParamtersAsArray());
        }
        $response = curl_exec($curl);
        curl_close($curl);
        $body = json_decode($response, true);
        if (is_null($body)) {
            throw new UnexpectedValueException('Cannot convert response: ' . json_last_error_msg() . ' (' . $response . ').');
        }
        return new Response($body, $headers);
    }
}