<?php 

namespace App\Controllers;

use Delight\Auth\Auth;
use League\Plates\Engine;
use PDO;
use App\Models\Permissions;
use App\Models\Users;
use App\Models\Image;

class UserController {

	private $auth, $templates, $permissions, $user;
	

	public function __construct(Engine $templates, Auth $auth, Permissions $permissions, Users $user) {

		$this->auth = $auth;		
		
		$this->templates = $templates;

		$this->permissions = $permissions;

		if( !$this->auth->isLoggedIn() ) {

			flash()->error('To watch this page you have to be login.');
		    
		    echo $this->templates->render('users');
		}

		$this->user = $user;
	}

	public function createShow() {

		

		echo $this->templates->render('create_user');
	}

	public function create($email, $password) {

		// var_dump($_POST); die;
		// var_dump($_FILES); die;

		if(!$this->permissions->can_create_users()) {

			flash()->error('You have no enough permissions to watch this page.');
		    
		    header("Location: /users"); exit;
		}

		try {

		    $user_id = $this->auth->admin()->createUser($_POST['email'], $_POST['password']);

		    if($user_id) {

		    	$this->user->edit_info($user_id, $_POST['info']);	

		    	$avatar_name = Image::create_uniq_file_name($_FILES['avatar']['name']);
			    $avatar_storage_path = Image::set_path($this->user->get_avatars_storage_path(), $avatar_name);

			    if($_FILES['avatar']['name'] && !Image::is_image($avatar_storage_path)) {

			    	flash()->error('Avatar must be an image!');
			    
			    	// echo $this->templates->render('/create_user');
			    	 header("Location: /users"); exit;
			    } else if(Image::is_image($avatar_storage_path)) {

			    	$this->user->edit_avatar($user_id, $avatar_storage_path);
			    	Image::upload($_FILES['avatar'], $avatar_storage_path);

			    } 

				$this->user->edit_status($user_id, $_POST['status']);

				$this->user->edit_social_links($user_id, $_POST['social']);

		    } else {

		    	flash()->error('Avatar must be an image!');
			    
			    header("Location: /create"); exit;
		    } 
		    

		    flash()->success('We have signed up a new user with the ID ' . $userId);
		    
		    // echo $this->templates->render('users');
		    header("Location: /users");
		}
		catch (\Delight\Auth\InvalidEmailException $e) {

			flash()->error('Invalid email address');
			header("Location: /create"); exit;
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {
			flash()->error('Invalid password');			    
			header("Location: /create"); exit;
		}
		catch (\Delight\Auth\UserAlreadyExistsException $e) {
			flash()->error('User already exists');			    
			header("Location: /create"); exit;
		}		
		
	}

	public function editShow($id) {

		// if(!$this->permissions->is_self($id) && !$this->permissions->is_admin() ) {

		// 	flash()->error('You have no enough permissions to watch this page.');
		    
		//     echo $this->templates->render('users');
		// }

		$user = $this->user->getAttrs($id, ['*'] );

		if (!$user) {
			flash()->error('User was not found');			    
			header("Location: /users"); exit;
		}		

	

		echo $this->templates->render('edit', ['user' => $user]);
		
	}

	public function edit() {	
		
		$this->user->edit_info($_GET['id'], $_POST);

		flash()->success("Вы внесли изменения в информацию пользователя" . $_GET['id']);

		header("Location: /users"); exit;
		
	}

	

	public function securityShow($id) {

		$user = $this->user->getAttrs($id, ['*'] );

		if (!$user) {
			flash()->error('User was not found');			    
			header("Location: /users"); exit;
		}	


		echo $this->templates->render('security', ['user' => $user]);
		
	}
	
	public function security() {

		$user = $this->user->getAttrs($_GET['id'], ['*']); 

		

		if($this->user->email_exists($_POST['email']) && ($user['email'] !== $_POST['email']) ) {
			flash()->error('Этот эмейл уже занят.');
			$id = $user['id'];
			header("Location: /security/$id");
			exit;			
		}

		$this->user->edit($user['id'], $_POST);

		flash()->success('Вы внесли изменения в информацию пользователя' . $user['id']);
		header("Location: /users"); exit;		
	}

	public function statusShow($id) {

		$user = $this->user->getAttrs($id, ['*'] );

		if (!$user) {
			flash()->error('User was not found');			    
			header("Location: /users"); exit;
		}	


		echo $this->templates->render('status', ['user' => $user]); 
		
	}

	public function status() {

		$this->user->edit_status($_GET['id'], $_POST['status']);

		flash()->success("Вы внесли изменения в информацию пользователя" . $_GET['id']);

		header("Location: /users"); exit;
		
	}


	public function mediaShow($id) {

		$user = $this->user->getAttrs($id, ['*'] );

		if (!$user) {
			flash()->error('User was not found');			    
			header("Location: /users"); exit;
		}

		echo $this->templates->render('media', ['user' => $user]);		
		
	}

	public function media() {

		$user_id = $_GET['id'];
		$avatar_name = Image::create_uniq_file_name($_FILES['avatar']['name']);
	    $avatar_storage_path = Image::set_path($this->user->get_avatars_storage_path(), $avatar_name);

	    if($_FILES['avatar']['name'] && !Image::is_image($avatar_storage_path)) {

	    	flash()->error('Avatar must be an image!');
	    
	    	// echo $this->templates->render('/create_user');
	    	 header("Location: /users"); exit;
	    } else if(Image::is_image($avatar_storage_path)) {

	    	$this->user->edit_avatar($user_id, $avatar_storage_path);
	    	Image::upload($_FILES['avatar'], $avatar_storage_path);

	    	flash()->success("Аватар пользователя " . $_GET['id'] . " был обновлён.");

			header("Location: /users"); exit;
	    } 
	   
		
	}


	public function delete() {
		
		try {

		    $this->user->delete_avatar($_GET['id']);
		    $this->auth->admin()->deleteUserById($_GET['id']);

		    flash()->warning("Пользователь " . $_GET['id'] . " был удалён.");

			header("Location: /users"); exit;
		}
		catch (\Delight\Auth\UnknownIdException $e) {
		    flash()->error('Unknown ID');
			header("Location: /users"); exit;
		}	
		
	}
}