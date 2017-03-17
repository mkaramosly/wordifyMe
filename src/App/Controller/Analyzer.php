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
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://gateway.watsonplatform.net',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $analyzerModel = new \App\Model\Analyzer();

        $transcript = $analyzerModel->getTranscript($args['id']);
        $body = [
            "text" => $transcript[0],
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
        $analyzerModel->storeKeyword($response, $args['id']);

        return $response;
    }
}
