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
