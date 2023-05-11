<?php
class MyAccountModel extends Model {
	public function getUserInfo( $nickName ) {
		$u = new UsersDb();
		$user = $u->getUserByName($nickName);
		return (isset($user[0])) ? $user[0] : '';
	}

	public function updateUserInfo($ui) {
		$u = new UsersDb();
		$u->updateUserByName($ui['nickName'], $ui['password'], $ui['email'], $ui['firstName'], $ui['lastName'], $ui['uid'] );
	}
}
