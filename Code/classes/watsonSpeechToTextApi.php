<?php


class watsonSpeechToTextApi {

	public $apiUrl;
	public $username;
	public $password;
	public $arguments;
//'arguments' => [
//'continuous' => true,
//'interim_results' => true,
//'timestamps' => true,
//'speaker_labels' => true,
//'word_alternatives_threshold' => 0.01
//]

	public function  __construct($config){
		$this->apiUrl = $config['watson']['apiUrl'];
		$this->username = $config['watson']['username'];
		$this->password = $config['watson']['password'];
		$this->arguments = $config['watson']['arguments'];
	}

	public function getToken($username, $password) {
		// implementation
	}

	public function webSocketRecognizeAudio() {
		// implementation
	}

	public function getUrlForSessionLessRecognizeAudio() {
		// implementation
		$url  = $this->apiUrl;
		$url .= '/recognize?';
		$argumentArray = [];
		foreach($this->arguments as $key => $value) {
			echo "key: $key  value: $value | ";
			if ($value != '') {
				$argumentArray[] = "$key=$value";
			}
		}
		$url .= implode('&', $argumentArray);

		return $url;
	}

	public function getUrlForsessionLessRecognizeMultipart() {
		// implementation
	}

}