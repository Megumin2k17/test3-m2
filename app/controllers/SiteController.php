<?php 

namespace App\Controllers;

use Delight\Auth\Auth;
use League\Plates\Engine;
use App\Models\Users;
use PDO;
use JasonGrimes\Paginator;

class SiteController {

	private $auth,$templates, $users;

	public function __construct(Engine $templates, Auth $auth, Users $users) {

		$this->auth = $auth;
		
		$this->templates = $templates;
		$this->users = $users;
	}

	public function users() {

		$users = $this->users->getAll(); 



		$totalItems = count($users);
		$itemsPerPage = 3;
		$currentPage = $_GET['page'] ?? 1;
		$urlPattern = '?page=(:num)';

		$paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

		$users = $this->users->getAll(3);

		echo $this->templates->render('users', ['users' => $users, 'paginator' => $paginator]);
		
	}

	public function login() {

		$data = []; 


		echo $this->templates->render('page_login', $data);
		
	}

	public function register() {

		$data = []; 


		echo $this->templates->render('page_register', $data);
		
	}

	public function profile($id) {
		// var_dump($id); die;
		$user = $this->users->getAttrs($id, ['*'] );

		if (!$user) {
			flash()->error('User was not found');			    
			header("Location: /users"); exit;
		}

		echo $this->templates->render('page_profile', ['user' => $user]);
	}




	

	
}
