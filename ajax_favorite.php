<?php

require( 'functions.php' );
startPageDisplay();

if ( ! empty( $_POST ) && ! empty( isLogin() ) ) {
	debug( 'お気に入りボタンがクリックされました' );
	try {
		$dbh       = dbConnect();
		$sql       = 'select count(*) from favorite_facilities where user_id = :user_id and facility_id = :facility_id';
		$data      = array(
			':user_id'     => $_SESSION['user_id'],
			':facility_id' => $_POST['facility_id']
		);
		$queryPost = queryPost( $dbh, $sql, $data );
		$result    = $queryPost->fetch();
		debug( print_r( $result, true ) );
		if ( ! empty( array_shift( $result ) ) ) {
			debug( 'お気に入り登録されています' );
			debug( 'お気に入り登録を削除します' );
			$sql = 'delete from favorite_facilities where user_id = :user_id and facility_id = :facility_id';
			if ( queryPost( $dbh, $sql, $data ) ) {
				echo json_encode( array(
					'msg'      => '削除しました',
					'favorite' => false
				) );
			}
		} else {
			debug( 'お気に入り登録されていません' );
			debug( 'お気に入り登録します' );
			$sql = 'insert into favorite_facilities (user_id, facility_id) values (:user_id, :facility_id)';
			if ( queryPost( $dbh, $sql, $data ) ) {
				echo json_encode( array(
					'msg'      => 'お気に入り登録しました',
					'favorite' => true

				) );
			}
		}
		exit();
	} catch ( Exception $e ) {
		exceptionHandler( $e );
	}
}

