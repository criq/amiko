<?php

namespace Amiko;

class Amiko {

	static function useMethod($method, $url, $params = array(), &$curl = null) {
		$curl = new \Curl\Curl();
		$curl->$method(\Katu\Types\TUrl::make($url, $params));

		$response = $curl->response;
		if (is_array($response)) {
			$response = \Katu\Utils\JSON::encode($response);
		}

		// Success.
		if ($curl->httpStatusCode == 200) {
			return \Katu\Utils\JSON::decodeAsArray($response);
		}

		// Error.
		$array = \Katu\Utils\JSON::decodeAsArray($response);
		if (isset($array['error']['message'])) {
			throw new AmikoException($array['error']['message']);
		} else {
			throw new AmikoException($curl->errorMessage, $curl->errorCode);
		}

		return false;
	}

	static function get($url, $params = array(), &$curl = null) {
		return static::useMethod('get', $url, $params, $curl);
	}

	static function post($url, $params = array(), &$curl = null) {
		return static::useMethod('post', $url, $params, $curl);
	}

}
