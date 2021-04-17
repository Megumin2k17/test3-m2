<?php

namespace App\Controllers;

use Delight\Auth\Auth;
use League\Plates\Engine;
use PDO;

class AuthController {

	private $auth,$templates;

	public function __construct(Engine $templates, Auth $auth) {

		$this->auth = $auth;
		// d($auth); die;

		$this->templates = $templates;
		// $this->templates->addFolder('layout', '../app/views/layout');
	}

	public function register() {

		try {

		    $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
		        // echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
		        flash()->success("<a href='http://php3-m2-test/verification?selector=${selector}&token=${token}' >Подтвердить регистрацию</a>");
		    });

		    // echo 'We have signed up a new user with the ID ' . $userId;
		    flash()->success('We have signed up a new user with the ID ' . $userId);
		    
		    echo $this->templates->render('users');

		}
		catch (\Delight\Auth\InvalidEmailException $e) {

			flash()->error('Invalid email address');
			echo $this->templates->render('page_register');
		    // die('Invalid email address');
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {

			flash()->error('Invalid password');
			echo $this->templates->render('page_register');
		    // die('Invalid password');
		}
		catch (\Delight\Auth\UserAlreadyExistsException $e) {

			flash()->error('User already exists333');
			echo $this->templates->render('page_register');
		    // die('User already exists');
		}
		catch (\Delight\Auth\TooManyRequestsException $e) {

			flash()->error('Too many requests');
			echo $this->templates->render('page_register');
		    // die('Too many requests');
		}
	}

	public function login() {

		try {
			
			if ($_POST['remember'] == true) {
			    // keep logged in for one year
			    $rememberDuration = (int) (60 * 60 * 24 * 365.25);
			}
			else {
			    // do not keep logged in after session ends
			    $rememberDuration = null;
			}

		    $this->auth->login($_POST['email'], $_POST['password'],  $rememberDuration);

		    // echo 'User is logged in';
		    
			flash()->success('You are successfully logged in.');
			echo $this->templates->render('users');
		}
		catch (\Delight\Auth\InvalidEmailException $e) {
		    // die('Wrong email address');
			flash()->error('Wrong email address');
		    echo $this->templates->render('page_login');
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {
		    // die('Wrong password');
			flash()->error('Wrong password');			
		    echo $this->templates->render('page_login');
		}
		catch (\Delight\Auth\EmailNotVerifiedException $e) {
		    // die('Email not verified');
			flash()->error('Email not verified');
		    echo $this->templates->render('page_login');
		}
		catch (\Delight\Auth\TooManyRequestsException $e) {
		    // die('Too many requests');
			flash()->error('Too many requests');
		    echo $this->templates->render('page_login');
		}
	}


	public function email_verification() {

		try {

		    $this->auth->confirmEmail($_GET['selector'], $_GET['token']);

		  
		    flash()->success('Email address has been verified.');
			echo $this->templates->render('users');
		}
		catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {

			flash()->error('Invalid token');
		    echo $this->templates->render('page_login');
		}
		catch (\Delight\Auth\TokenExpiredException $e) {

			flash()->error('Token expired');
		    echo $this->templates->render('page_login');
		}
		catch (\Delight\Auth\UserAlreadyExistsException $e) {

			flash()->error('Email address already exists');
		    echo $this->templates->render('page_login');
		}
		catch (\Delight\Auth\TooManyRequestsException $e) {

			flash()->error('Too many requests');
		    echo $this->templates->render('page_login');
		}
	}


	public function logout() {

		try {
		    $this->auth->logOutEverywhere();

			$this->auth->destroySession();

			flash()->warning('You have exited from your account.');
			echo $this->templates->render('users');
		}
		catch (\Delight\Auth\NotLoggedInException $e) {

		    flash()->error('Not logged in');
		    echo $this->templates->render('page_login');
		}
	}
}
