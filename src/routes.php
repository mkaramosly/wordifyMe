<?php
// Routes

$app->get('/', '\App\Controller\Controller:index');
$app->get('/meeting/[{id}]', '\App\Controller\MeetingController:get');

$app->post('/meeting', '\App\Controller\MeetingController:post');
$app->get('/transcribe/[{id}]', '\App\Controller\MeetingController:transcribe');

//$app->get('/[{name}]', function ($request, $response, $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});
