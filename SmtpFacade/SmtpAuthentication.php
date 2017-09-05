<?php
namespace SmtpFacade;

class SmtpAuthentication {
	protected $settings = [];
	protected $sessionField = '';
	protected $sessionCreationCallback = null;
	protected $sessionDestroyCallback = null;
	protected $sessionGetCallback = null;

	const DEFAULT_SESSION_FIELD = 'SMTP_AUTH_SESSION_LOGIN';

	public function __construct($sessionField = self::DEFAULT_SESSION_FIELD, $settings = null, $disableSessionInit = false) {
		if (!$disableSessionInit) {
			if (session_status() !== PHP_SESSION_ACTIVE) {
				if (!headers_sent()) {
					session_start();
				}
			}
		}
		if (!$settings) {
			$settings = json_decode(file_get_contents(__DIR__.'/smtp-facade-conf/smtp.json'));
		} else if (is_string($settings)) {
			$settings = json_decode(file_get_contents($settings));
		}
		$this->sessionField = $sessionField;
		$this->settings = $settings;
	}

	public function registerCreateSessionCallback($callback) {
		$this->sessionCreationCallback = $callback;
	}

	public function registerDestroySessionCallback($callback) {
		$this->sessionDestroyCallback = $callback;
	}

	public function registerGetSessionCallback($callback) {
		$this->sessionGetCallback = $callback;
	}

	public function authenticate($login, $password) {
		$mail = new \PHPMailer();
		$mail->isSMTP();
		$mail->Host = $this->settings->host ?? 'localhost';
		$mail->Port = $this->settings->port ?? '465';
		$mail->SMTPAuth = $this->settings->auth ?? false;
		$mail->SMTPSecure = $this->settings->secure ?? '';
		$mail->Username = $login;
		$mail->Password = $password;
		$loggedOn = false;
		if ($mail->smtpConnect()) {
			$this->createSession($login);
			$loggedOn = true;
			$mail->smtpClose();
		}
		return $loggedOn;
	}

	public function logoff() {
		$this->destroySession();
	}

	protected function createSession($login) {
		if ($this->sessionCreationCallback != null) {
			$this->sessionCreationCallback($login);
		} else {
			$_SESSION[$this->sessionField] = $login;
		}
	}

	protected function destroySession() {
		if ($this->sessionDestroyCallback != null) {
			$this->sessionDestroyCallback();
		} else {
			unset($_SESSION[$this->sessionField]);
		}
	}

	public function getSessionLogin() {
		if ($this->sessionGetCallback != null) {
			return $this->sessionGetCallback();
		} else {
			return $_SESSION[$this->sessionField];
		}
	}
}
