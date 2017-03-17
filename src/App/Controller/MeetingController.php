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
        $wordsObj = new \App\Model\Words();
        $words = $wordsObj->getWords($args['id']);

        return $this->view->render($response, 'meeting.phtml', ['words' => $words]);
    }
}
