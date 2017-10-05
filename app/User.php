<?php

namespace App;

use App\Http\Controllers\OAuth2Controller;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	protected $dates = [
		'access_expires', 'created_at', 'updated_at'
	];

	public function updateUserWithTokens($tokens) {
		$this->access = $tokens->access_token;
		$this->refresh = $tokens->refresh_token;
		$this->access_expires = Carbon::now()->addSecond($tokens->expires_in);
		$this->scope = $tokens->scope;
		$this->save();
	}

	public static function codeToTokens($token) {
		$response = OAuth2Controller::postRequest('oauth/token', [
			'grant_type' => 'authorization_code',
			'code' => $token
		]);

		$tokens = json_decode($response->getBody());
		return($tokens);
	}

	public function hasValidAccessToken() {
		if (isset($this->access)) {
			if ($this->access_expires->lte(Carbon::now())) {
				$this->renewAccessToken();
			}
			return true;
		}
		return false;
	}

	public function renewAccessToken() {
		$response = OAuth2Controller::postRequest('oauth/token', [
			'grant_type' => 'refresh_token',
			'refresh_token' => $this->refresh
		], 'false');
		if($response->getStatusCode() == 200) {
			$this->updateUserWithTokens(json_decode($response->getBody()));
		} else {
			return redirect(OAuth2Controller::getAuthenticationRedirect());
		}
		return true;
	}

	public function getEvents($page) {
		if(!$this->hasValidAccessToken()) {
			return redirect(OAuth2Controller::getAuthenticationRedirect());
		}
		$take = 10;
		$skip = ($page - 1) * $take;
		$response = OAuth2Controller::authorizedGetRequest($this, 'api/jobseeker/v1/events?take=10&' . $skip);
		return json_decode($response->getBody()->getContents());
	}

	public function getEvent($id) {
		if(!$this->hasValidAccessToken()) {
			return redirect(OAuth2Controller::getAuthenticationRedirect());
		}
		$response = OAuth2Controller::authorizedGetRequest($this, 'api/jobseeker/v1/events/' . $id, false);
		switch ($response->getStatusCode()) {
			case 200:
				return json_decode($response->getBody()->getContents());
				break;
			case 404:
				abort(404, "CareerHub API reports: no event with id $id could be found for this user.");
				break;
			default:
				abort(500, "Unexpected response from CareerHub: $response->getReasonPhrase()");
				break;
		}
		return false;
	}
}
