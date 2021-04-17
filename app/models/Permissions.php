<?php 

namespace App\Models;

use Delight\Auth\Auth;

class Permissions {

	private $auth;

	public function __construct(Auth $auth) {

		$this->auth = $auth;
	}

	public function can_create_users() {

	    return $this->auth->hasAnyRole(
	        \Delight\Auth\Role::ADMIN
	    );
	}

	public function is_admin() {
		 return $this->auth->hasRole(\Delight\Auth\Role::ADMIN);
	}

	public function is_self($id) {
		return $this->auth->getUserId() == $id;
	}

}