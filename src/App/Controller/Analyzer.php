<?php
namespace App\Controller;

use GuzzleHttp\Client;

class Analyzer
{
    public function __construct()
    {

    }

    public function analyze($args)
    {
        error_log(print_r($args, true), 3, '/tmp/err.log');
        error_log("HERE".PHP_EOL, 3, '/tmp/err.log');
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://gateway.watsonplatform.net',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $body = [
            "text" => "IBM is an American multinational technology company headquartered in Armonk, New York, United States, with operations in over 170 countries.",
            "features" => [
                "entities" => [
                    "emotion" => true,
                    "sentiment" => true,
                    "limit" => 2
            ],
            "keywords" => [
                    "emotion" => true,
                    "sentiment" => true,
                    "limit" => 2
                ]
            ]
        ];

        // Send a request to https://foo.com/api/test
        $response = $client->request(
            'POST',
            '/natural-language-understanding/api/v1/analyze?version=2017-02-27',
            [
                'headers' => [
                    'content-type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'auth' => [
                    'a00d3b79-bfd0-402f-8530-cb565ed3cb43',
                    'LCzPXTilw5cD'
                ],
                'body' => json_encode($body),
            ]
        );
        $response = $response->getBody()->getContents();

        $analyzerModel = new \App\Model\Analyzer();
        $analyzerModel->storeKeyword($response, $args['id']);

        return $response;
    }
}
