<?php

namespace app\controllers;

class ApiExampleController extends BaseController
{

	public function getUsers() {
		$users = [
			[ 'id' => 1, 'name' => 'Bob Jones', 'email' => 'bob@example.com' ],
			[ 'id' => 2, 'name' => 'Bob Smith', 'email' => 'bsmith@example.com' ],
			[ 'id' => 3, 'name' => 'Suzy Johnson', 'email' => 'suzy@example.com' ],
		];
		$this->json($users);
	}

	public function getUser($id) {
		$users = [
			[ 'id' => 1, 'name' => 'Bob Jones', 'email' => 'bob@example.com' ],
			[ 'id' => 2, 'name' => 'Bob Smith', 'email' => 'bsmith@example.com' ],
			[ 'id' => 3, 'name' => 'Suzy Johnson', 'email' => 'suzy@example.com' ],
		];
		$user = null;
		$users_filtered = array_filter($users, function($data) use ($id) {
			return $data['id'] === (int) $id;
		});
		if($users_filtered) {
			$user = array_pop($users_filtered);
		}
		$this->json($user, $user ? 200 : 404);
	}

	public function updateUser($id) {
		$this->json([ 'success' => true, 'id' => (int) $id ]);
	}
}