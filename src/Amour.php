<?php

namespace Amour;

class Amour {

	static function useMethod($method, $url, $params = array(), &$curl = NULL) {
		$curl = new \Curl();
		$curl->$method(\Katu\Utils\URL::make($url, $params));

		$response = $curl->response;
		if (is_array($response)) {
			$response = \Katu\Utils\JSON::encode($response);
		}

		// Success.
		if ($curl->http_status_code == 200) {
			return \Katu\Utils\JSON::decodeAsArray($response);
		}

		// Error.
		$array = \Katu\Utils\JSON::decodeAsArray($response);
		if (isset($array['error']['message'])) {
			throw new AmourException($array['error']['message']);
		} else {
			throw new AmourException($curl->error_message, $curl->error_code);
		}

		return FALSE;
	}

	static function get($url, $params = array(), &$curl = NULL) {
		return static::useMethod('get', $url, $params, $curl);
	}

	static function post($url, $params = array(), &$curl = NULL) {
		return static::useMethod('post', $url, $params, $curl);
	}

}
