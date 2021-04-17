<?php 

namespace App\Models;

use App\Models\QueryBuilder;


class Users {

		private $queryBuilder;
		private $table = 'users';
		private $avatars_storage_path = "avatars/";

		public function __construct(QueryBuilder $queryBuilder) {

			$this->queryBuilder = $queryBuilder;
			
		}

		public function get_avatars_storage_path() {
			return $this->avatars_storage_path;
		}

		public function edit($user_id, $data) {

			$this->queryBuilder->update($this->table, $data, "id = $user_id");		
		}

		public function email_exists($email) {
			// var_dump($email); die;
			$email_exists = $this->queryBuilder->getOne($this->table, ['*'], "email = '$email'");
			// var_dump($email_exists); die;
			if($email_exists) {
				return true;
			}
			return false;
		}

		public function edit_info($user_id, Array $data) {

			$this->queryBuilder->update($this->table, $data, "id = $user_id");
		}

		public function edit_social_links($user_id, Array $data) {

			$this->queryBuilder->update($this->table, $data, "id = $user_id");
		}		
	

		public function edit_email($user_id, $email) {
			
			$this->queryBuilder->update($this->table, ['email'=>$email], "id = $user_id");
		}

		public function edit_password($user_id, $password) {
			
			$this->queryBuilder->update($this->table, ['password'=>$password], "id = $user_id");
		}


		public function edit_status($user_id, $status) {

			if($status==="Онлайн") {
				$status = "status status-success mr-3";
			} elseif($status === "Отошел") {
				$status = "status status-warning mr-3";
			} elseif ($status === "Не беспокоить") {
				$status = "status status-danger mr-3";
			} else {
				$status = "status status-danger mr-3";
			}
			// var_dump($status);die;
			$this->queryBuilder->update($this->table, ['user_status'=>$status], "id = $user_id");
		}

		public function edit_avatar($user_id, $avatar) {

			$this->queryBuilder->update($this->table, ['avatar'=>$avatar], "id = $user_id");
		}

		public function delete_avatar($user_id) {
	
			$avatar = $this->getAttrs($user_id, ['avatar']); 
			// var_dump($avatar); die;
			
			if (isset($avatar) && file_exists($avatar)) {		
				unlink($avatar);
			}

			$this->queryBuilder->update($this->table, ['avatar'=> 'NULL'], "id = $user_id");
		}

		public function getAttrs($user_id, Array $attributes) {
			return $this->queryBuilder->getOne($this->table, $attributes, "id = $user_id");
		}

		public function getAll($offset=null) {
			return $this->queryBuilder->getAll($this->table, ['*'], null, $offset);
		}
}