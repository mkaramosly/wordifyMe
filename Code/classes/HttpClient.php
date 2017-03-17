<?php

/**
 * Created by PhpStorm.
 * User: mehdi.karamosly
 * Date: 3/16/2017
 * Time: 6:03 PM
 */
class HttpClient
{

	/**
	 * Define all supported HTTP Request Methods.
	 */
	const GET = 'GET';
	const POST = 'POST';
	const PUT = 'PUT';
	const PATCH = 'PATCH';
	const DELETE = 'DELETE';
	const OPTIONS = 'OPTIONS';
	const HEAD = 'HEAD';

	/**
	 * @var bool $sendHeader Indicates if Response Header should be included in the output.
	 */
	public $sendHeader;
	/**
	 * @var int $timeout Request Timeout interval in seconds.
	 */
	public $timeout;
	/**
	 * @var int $useSslHostValidation Indicates if validation of remote SSL Certificate Name against its Host should be
	 *                                performed.
	 */
	public $useSslHostValidation;
	/**
	 * @var int $useSslPeerValidation Indicates if validation of remote SSL Certificate Identity against its CA
	 *                                (Certificate Authority) should be performed.
	 */
	public $useSslPeerValidation;


	/**
	 * Constructor.
	 *
	 * Sets all HTTP Client required configuration parameters. It doesn't have to be instantiated as it already exists
	 * as a Service unless custom parameters need to be used.
	 *
	 * @param bool $sendHeader Indicates if Response Header should be included in the output (@default=false).
	 * @param int $timeout Request Timeout interval in seconds (@default=60).
	 * @param bool $useSslHostValidation Indicates if validation of remote SSL Certificate Name against its Host should
	 *                                   be performed (@default=true).
	 * @param bool $useSslPeerValidation Indicates if validation of remote SSL Certificate Identity against its CA
	 *                                   (Certificate Authority) should be performed (@default=false).
	 */
	public function __construct(
		$sendHeader = false,
		$timeout = 60,
		$useSslHostValidation = true,
		$useSslPeerValidation = false
	)
	{
		// Set HTTP Client default configuration parameters.
		$this->sendHeader = $sendHeader;
		$this->timeout = $timeout;
		$this->useSslHostValidation = $useSslHostValidation;
		$this->useSslPeerValidation = $useSslPeerValidation;
	}


	/**
	 * CURL API Request Helper.
	 *
	 * It makes HTTP Request using CURL Library.
	 *
	 * @param string $url Request URL.
	 * @param array|string $data Request Data.
	 * @param string $method HTTP Request Method {'GET'|'POST'|'PUT'|'PATCH'|'DELETE'|'OPTIONS'|'HEAD'}
	 *                                  (@default='POST').
	 * @param string $returnType Return Type (@default='data').
	 * @param array $httpHeaders Additional Request Headers.
	 * @param array $fileConfig Optional File Handler. In case of file download it should look like:
	 *                                  ['filePath' => '/PATH_TO_FILE/FILE_NAME', 'overwrite' => false]
	 *                                  If 'overwrite' is set to TRUE it will overwrite existing file, if found.
	 *                                  Otherwise, it will throw an Exception.
	 *
	 * @return Object                   Returns a Response.
	 * @throws Exception                Throws an Exception in case of an Error (i.e. if trying to overwrite an existing
	 *                                  file).
	 */
	public function request(
		$url,
		$data,
		$method = self::POST,
		$returnType = 'data',
		$httpHeaders = ['Accept: application/json'],
		array $fileConfig = []
	)
	{

		// Try to execute HTTP Request.
		try {
			// Check if File Path is provided.
			if (!empty($fileConfig) && isset($fileConfig['filePath'])) {

				$fileName = $fileConfig['filePath'];
				$overwrite = isset($fileConfig['overwrite']) && $fileConfig['overwrite'];

				if (file_exists($fileName) && $overwrite === false) {
					throw new Exception("File '{$fileName}' already exist!");
				}
				$fileHandler = fopen($fileName, 'w+');
			}

			// Initialize CURL Handler.
			$curlHandler = curl_init($url);

			// Check which HTTP Request Method should be used.
			switch ($method) {
				case 'GET':
					if ($data) {
						$url = sprintf("%s?%s", $url, http_build_query($data));
					}
					curl_setopt($curlHandler, CURLOPT_URL, $url);
					curl_setopt($curlHandler, CURLOPT_HTTPGET, true);
					break;

				case 'POST':
					curl_setopt($curlHandler, CURLOPT_POST, 1);
					if ($data) {
						curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $data);
					}
					break;

				case 'PUT':
					curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, 'PUT');
					if ($data) {
						curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $data);
					}
					break;

				case 'PATCH':
					curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, 'PATCH');
					if ($data) {
						curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $data);
					}
					break;

				case 'DELETE':
					curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, "DELETE");
					break;

				case 'OPTIONS':
					if ($data) {
						$url = sprintf("%s?%s", $url, http_build_query($data));
					}
					curl_setopt($curlHandler, CURLOPT_URL, $url);
					curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
					break;

				case 'HEAD':
					if ($data) {
						$url = sprintf("%s?%s", $url, http_build_query($data));
					}
					curl_setopt($curlHandler, CURLOPT_URL, $url);
					curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, 'HEAD');
					break;

				default:
					// Throw an Exception in case of unsupported Request Method.
					throw new Exception("Unsupported Request Method: {$method}");
					break;
			}

			curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);

			// Check if additional Request Headers need to be sent.
			if (!empty($httpHeaders)) {
				curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $httpHeaders);
			}

			// Set additional CURL Request Options.
			curl_setopt($curlHandler, CURLOPT_HEADER, $this->sendHeader);
			curl_setopt($curlHandler, CURLOPT_TIMEOUT, (int)$this->timeout);

			// Set SSL certificate validation.
			curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, ($this->useSslHostValidation === true ? 2 : 0));
			curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, ($this->useSslPeerValidation === true ? 1 : 0));

			if (isset($fileHandler)) {
				curl_setopt($curlHandler, CURLOPT_FILE, $fileHandler);
				/** @noinspection PhpUnusedParameterInspection */
				curl_setopt($curlHandler, CURLOPT_WRITEFUNCTION, function (&$curlHandler, $data) use ($fileHandler) {
					$responseLength = fwrite($fileHandler, $data);

					return $responseLength;
				});
			}

			// Execute HTTP Request.
			$result = curl_exec($curlHandler);

			// Check if File Handler is used.
			if (isset($fileHandler)) {
				// Close CURL Handler.
				curl_close($curlHandler);
				// Close File Handler!
				fclose($fileHandler);

				// Check if File was successfully saved.
				if (isset($fileName) && file_exists($fileName)) {
					return $fileName;
				} else {
					return false;
				}
			}

			// Validate if CURL Request was successful.
			if ($result === false) {
				throw new Exception('CURL ' . curl_error($curlHandler));
			}

			if ($returnType !== 'data') {
				$result = curl_getinfo($curlHandler, $returnType);
			}

			// Close CURL Handler.
			curl_close($curlHandler);

			// Try to parse Response as JSON.
			$result = json_decode($result);

			// Validate if parsed JSON Response is valid.
			if (!is_object($result)) {
				// Throw an Exception in case of invalid JSON Response.
				throw new Exception('Invalid JSON Object: ' . json_last_error_msg(), json_last_error());
			}

			// Return Response.
			return $result;

		} catch (Exception $exception) {
			// Throw an Exception in case of failed HTTP Request.
			throw new Exception(
				"Error while making CURL Request to '{$url}'. Error " . $exception->getCode() . ": " .
				$exception->getMessage()
			);
		}
	}
}