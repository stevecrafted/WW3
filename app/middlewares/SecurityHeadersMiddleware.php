<?php
declare(strict_types=1);

namespace app\middlewares;

use flight\Engine;
use Tracy\Debugger;

class SecurityHeadersMiddleware
{
	protected Engine $app;

	public function __construct(Engine $app)
	{
		$this->app = $app;
	}

	public function before(array $params): void
	{
		$nonce = $this->app->get('csp_nonce');
		$this->app->set('csp_nonce', $nonce);

		// Pour Tracy (debug bar)
		$tracyCssBypass = "'nonce-{$nonce}'";
		if (Debugger::$showBar === true) {
			$tracyCssBypass = "'unsafe-inline'";
		}

		$csp = "default-src 'self' https://cdn.tiny.cloud; " .
			"script-src 'self' 'nonce-{$nonce}' https://sp.tinymce.com https://cdn.tiny.cloud; " .
			"style-src 'self' {$tracyCssBypass} https://cdn.tiny.cloud; " .
			"font-src 'self' https://cdn.tiny.cloud https://fonts.gstatic.com; " .
			"connect-src 'self' https://cdn.tiny.cloud https://sp.tinymce.com https://api.uploadcare.com; " .
			"img-src 'self' data: https://cdn.tiny.cloud https://ucarecdn.com;";

		$this->app->response()->header('X-Frame-Options', 'SAMEORIGIN');
		$this->app->response()->header("Content-Security-Policy", $csp);
		$this->app->response()->header('X-XSS-Protection', '1; mode=block');
		$this->app->response()->header('X-Content-Type-Options', 'nosniff');
		$this->app->response()->header('Referrer-Policy', 'no-referrer-when-downgrade');
		$this->app->response()->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
		$this->app->response()->header('Permissions-Policy', 'geolocation=()');
	}
}