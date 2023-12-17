<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    function subscribe(Request $request) {
		$request->validate([
			'endpoint'    => 'required',
			'keys.auth'   => 'required',
			'keys.p256dh' => 'required'
		]);

		// id|user_id|endpoint|public_key|auth_token|created_at|updated_at
		// `endpoint`, `public_key`, `auth_token`, `content_encoding`, `subscribable_id`, `subscribable_type`,

		$endpoint = $request->endpoint;
		$token = $request->keys['auth'];
		$key = $request->keys['p256dh'];
		$user = $request->user();
		$user->updatePushSubscription($endpoint, $key, $token);

		return response()->json(['success' => true],200);
	}
}
