<?php
namespace phpgt\csrf;

use phpgt\csrf\exception\CSRFTokenInvalidException;
use phpgt\csrf\exception\CSRFTokenSpentException;

// NOTE that if this implementation is going to work across web requests, it
// must be stored on the session - it has no other way of remembering the
// tokens!
class ArrayTokenStore extends TokenStore {
	private $store = [ ];

	public function __construct() {
		parent::__construct();
	}

	public function saveToken(string $token) {
		$this->store[ $token ] = null;
	}

	public function verifyToken(string $token) : bool {
		if(!array_key_exists($token, $this->store)) {
			throw new CSRFTokenInvalidException($token);
		} elseif($this->store[ $token ] !== null) {
			throw new CSRFTokenSpentException($token, $this->store[ $token ]);
		} else {
			return true;
		}
	}

	public function consumeToken(string $token) {
		$this->store[ $token ] = time();
	}
}#