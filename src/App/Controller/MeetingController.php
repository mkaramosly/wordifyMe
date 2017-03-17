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

    public function post($request, $response, $args)
    {

        header('Content-Type: text/plain; charset=utf-8');

//        try {

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $finfo->file($_FILES['upfile']['tmp_name']);

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($_FILES['upfile']['error']) ||
                is_array($_FILES['upfile']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['upfile']['error'] value.
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            // You should also check filesize here.
            if ($_FILES['upfile']['size'] > 1000000) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            $fileName = time();
            $ext = '';
            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
//	$finfo = new finfo(FILEINFO_MIME_TYPE);
//	if (false === $ext = array_search(
//			$finfo->file($_FILES['upfile']['tmp_name']),
//			array(
//				'jpg' => 'image/jpeg',
//				'png' => 'image/png',
//				'gif' => 'image/gif',
//				'flac' => 'audio/flac'
//			),
//			true
//		)) {
//		throw new RuntimeException('Invalid file format.');
//	}

            $extensionMap = [
                'audio/flac' => 'flac',
                'audio/wav' => 'wav',
                'audio/vnd.wav' => 'wav'
            ];
            $meetingId = rand(0, 10);
            mkdir("./data/{$meetingId}_{$fileName}", 0777, true);

            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            if (!move_uploaded_file(
                $_FILES['upfile']['tmp_name'],
                "./data/{$meetingId}_{$fileName}/{$meetingId}." . $extensionMap[$_FILES['upfile']['type']]
//		sprintf('./data/%s',
//			//sha1_file($_FILES['upfile']['tmp_name']),
//			$fileName
//		)
            )
            ) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            echo 'File is uploaded successfully.';

//
//            /**
//             * Calling Watson
//             */
//            include_once "classes/HttpClient.php";
//            include_once "classes/watsonSpeechToTextApi.php";
//            include_once "config/config.php";
//            $http = new HttpClient(true);
//
//            var_dump($config);
//
//
//            $wstt = new watsonSpeechToTextApi($config);
//            $data = array(
//                'file' => '@' . realpath("./data/{$meetingId}_{$fileName}/{$meetingId}." . $extensionMap[$_FILES['upfile']['type']])
//            );
//
//            echo '<br><pre>';
//            var_dump($wstt);
//            var_dump($data);
//            echo '</pre>';
//            /**
//             * curl -X POST -u f0855686-4dbc-49a6-a0ec-3e40c7472c9b:pOOktT2JE3by
//             * --header "Content-Type: audio/flac"
//             * --header "Transfer-Encoding: chunked"
//             * --data-binary @/home/mehdi/audio-file.flac
//             * "https://stream.watsonplatform.net/speech-to-text/api/v1/recognize?continuous=true&interim_results=true&timestamps=true&speaker_labels=true&word_alternatives_threshold=0.1"
//             **/
//            var_dump($wstt->getUrlForSessionLessRecognizeAudio());
//
////	try {
////		$http->request($wstt->getUrlForSessionLessRecognizeAudio(),
////			$data,
////			$http::POST,
////			'data',
////			[
////				"Content-Type: " . $_FILES['upfile']['type'],
////				"Transfer-Encoding: chunked"
////			],
////			['filePath' => "./data/{$meetingId}_{$fileName}/{$meetingId}_response.json"]
////		);
////	} catch (Exception $exception) {
////		var_dump($ex);
////	}
//            echo 'curl -X POST -u ' . $config['watson']['username'] . ':' . $config['watson']['password'] . ' --header "Content-Type: audio/flac" --header "Transfer-Encoding: chunked" --data-binary @' . realpath("./data/{$meetingId}_{$fileName}/{$meetingId}." . $extensionMap[$_FILES['upfile']['type']]) . ' "https://stream.watsonplatform.net/speech-to-text/api/v1/recognize?continuous=true&interim_results=true&timestamps=true&word_alternatives_threshold=0.1&smart_formatting=true" > ./data/' . $meetingId . '_' . $fileName . '/' . $meetingId . '_response.json ';
//            exec('curl -X POST -u ' . $config['watson']['username'] . ':' . $config['watson']['password'] . ' --header "Content-Type: audio/flac" --header "Transfer-Encoding: chunked" --data-binary @' . realpath("./data/{$meetingId}_{$fileName}/{$meetingId}." . $extensionMap[$_FILES['upfile']['type']]) . ' "https://stream.watsonplatform.net/speech-to-text/api/v1/recognize?continuous=true&interim_results=true&timestamps=true&word_alternatives_threshold=0.1&smart_formatting=true" > ./data/' . $meetingId . '_' . $fileName . '/' . $meetingId . '_response.json ');
//        } catch (RuntimeException $e) {
//
//            echo $e->getMessage();
//        }

        return $this->view->render($response, 'meeting.phtml', $args);
    }
}
