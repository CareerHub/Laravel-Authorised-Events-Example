<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Requests;

class OAuth2Controller extends Controller {
	public static function getAuthenticationRedirect() {
		return
            config("oauth.baseURL") .
            config("oauth.baseURI") .
			'/oauth/auth'.
			'?redirect_uri='.
				route("callback") .
			'&response_type='.
				'code'.
			'&client_id=' .
                config("oauth.clientID") .
			'&scope=' .
                config("oauth.scope");
	}

	public static function postRequest($endpoint, $data, $showHttpFails = 'true') {
		$client = new Client([
			'base_uri' => config("oauth.baseURL"),
			'timeout' => 2.0,
		]);
		$data = array_add($data, 'client_id', config("oauth.clientID"));
		$data = array_add($data, 'client_secret', config("oauth.secret"));
		$data = array_add($data, 'redirect_uri', route("callback"));
		$requestData = [
			'form_params' => $data,
			'http_errors' => $showHttpFails
		];
		try {
			return $client->request('POST', config("oauth.baseURI") . $endpoint, $requestData);
		} catch (\Exception $e) {
			abort(500, "Error: " . $e->getMessage());
			return false;
		}
	}

	public static function authorizedGetRequest($user, $endpoint, $showHttpFails = true) {
		$client = new Client([
			'base_uri' => config("oauth.baseURL"),
			'timeout' => 2.0,
		]);
		try {
			$response =  $client->request('GET', config("oauth.baseURI") . $endpoint, [
				'headers' => [
					'Authorization' => 'Bearer ' . $user->access,
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Accept' => 'application/json'
				],
				'http_errors' => $showHttpFails
			]);
			return $response;
		} catch (\Exception $e) {
			abort(500, "Error with request: " . $e->getMessage());
			return false;
		}
	}
}
