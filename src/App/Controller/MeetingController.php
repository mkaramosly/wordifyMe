<?php
namespace App\Controller;


class MeetingController extends Controller
{
    public function __construct($view)
    {
        parent::__construct($view);
    }

    public function get($request, $response, $args)
    {
        return $this->view->render($response, 'meeting.phtml', $args);
    }
}
