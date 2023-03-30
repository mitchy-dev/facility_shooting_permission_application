<?php

require( 'functions.php' );
startPageDisplay();
if ( ! empty( $_POST ) ) {
	debug( '登録済みの海岸名か確認します。' );
	try {
		$dbh    = dbConnect();
		$sql    = 'select count(*) from facilities where facility_name = :facility_name and is_deleted = false';
		$data   = array(
			':facility_name' => $_POST['facility_name'],
		);
		$sth    = queryPost( $dbh, $sql, $data );
		$result = $sth->fetch();
		if ( empty( array_shift( $result ) ) ) {
			echo json_encode( array(
				'isRegistered' => false,
				'msg'          => '',
			), JSON_UNESCAPED_UNICODE );
		} else { //ここにセッションに保存した元の海岸名を除外する条件を入れる
			echo json_encode( array(
				'isRegistered' => true,
				'msg'          => '既に登録されています',
			), JSON_UNESCAPED_UNICODE );
		}
	} catch ( Exception $e ) {
		exceptionHandler( $e );
	}
}
endPageDisplay();
