<?php

namespace App\Http\Controllers;

use App\Credential;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class EventApiController extends Controller {

	public function index(Request $request) {
		$user = Auth::user();
		if(!$user->hasValidAccessToken()) {
			return redirect(OAuth2Controller::getAuthenticationRedirect());
		}
		$page = 1;
		if ($request->has('page')) {
			$page = (int)$request->input('page');
		}
		$user = Auth::user();
		$events = $user->getEvents($page);
		return view('events.list', ['events' => $events]);
	}

	public function detail($id) {
		$user = Auth::user();
		$event = $user->getEvent($id);
		return view('events.detail', ['event' => $event]);
	}

	public function callback(Request $request) {
		$user = Auth::user();
		$input = $request->all();
		if (!empty($input['code'])) {
			$user->updateUserWithTokens(User::codeToTokens($input['code']));
			return redirect('events');
		}
		abort(500, "No data was returned in the callback.");
		return false;
	}
}
