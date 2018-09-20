<?php
namespace Controllers;

use Controller;

class AuthController extends Controller {
	public function getRegister() {}

	public function postRegister() {}

	public function getLogin() {
		view('auth\login');
	}

	public function postLogin() {}

	public function getLogout() {}

	public function getReset() {}

	public function postEmailPassword() {}

	public function getPasswordResetToken() {}

	public function postReset() {}
}