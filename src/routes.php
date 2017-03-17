<?php
// Routes

$app->get('/', '\App\Controller\Controller:index');
$app->get('/meeting/[{id}]', '\App\Controller\MeetingController:get');
$app->get('/parser/{id}', '\App\Controller\JSONParserController:parseJSONFile');
//$app->get('/[{name}]', function ($request, $response, $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});
$app->get('/transcribe/[{id}]', function ($request, $response, $args) {
    return error_log('TRANSCRIBING'.PHP_EOL, 3, '/tmp/err.log');
});

$app->get('/parse/[{id}]', function ($request, $response, $args) {
    return error_log('PARSING'.PHP_EOL, 3, '/tmp/err.log');
});

$app->get('/analyze/[{id}]', function ($request, $response, $args) {
    return error_log('ANALYZING'.PHP_EOL, 3, '/tmp/err.log');
});
