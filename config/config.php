<?php
/**
 * Created by PhpStorm.
 * User: mehdi.karamosly
 * Date: 3/16/2017
 * Time: 6:31 PM
 */

//https://stream.watsonplatform.net/speech-to-text/api/v1/recognize?
//continuous=true&
//interim_results=true&
//timestamps=true&
//speaker_labels=true&
//word_alternatives_threshold=0.01

/**
 * curl -X POST -u f0855686-4dbc-49a6-a0ec-3e40c7472c9b:pOOktT2JE3by
 * --header "Content-Type: audio/flac"
 * --header "Transfer-Encoding: chunked"
 * --data-binary @/home/mehdi/audio-file.flac "https://stream.watsonplatform.net/speech-to-text/api/v1/recognize?continuous=true&interim_results=true&timestamps=true&speaker_labels=true&word_alternatives_threshold=0.1"
 */

$config = [
	'watson' => [
		'apiUrl' => 'https://stream.watsonplatform.net/speech-to-text/api/v1',
		'username' => 'f0855686-4dbc-49a6-a0ec-3e40c7472c9b',
		'password' => 'pOOktT2JE3by',
		'arguments' => [
			'continuous' => 'true',
			'interim_results' => 'true',
			'timestamps' => 'true',
			'speaker_labels' => 'true',
			'word_alternatives_threshold' => 0.01
		]
	]
];