<?php

$DB_HOST = "122.116.159.53";
$DB_NAME = "ctm_rankbar";
$DB_USER = "rankbar";
$DB_PASS = "rankbar@#18";

// connect to the db
try {

	$db = new PDO("mysql:host=".$DB_HOST.";

                dbname=".$DB_NAME, $DB_USER, $DB_PASS,

                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

                //PDO::MYSQL_ATTR_INIT_COMMAND 設定編碼

	// echo '連線成功';

	$_SESSION['link'] = $db;

	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); //錯誤訊息提醒

}catch (PDOException $e){

	printf("DatabaseError: %s ", $e->getMessage());

}

// $PDO_RESULT_TYPE = PDO::FETCH_ASSOC;

$PDO_RESULT_TYPE = PDO::FETCH_OBJ;

$m_id = '2';
$username = 'yenyen';

// $sql = "SELECT * FROM member WHERE m_id = ? AND username = ?;";

// $query = $db->prepare($sql);

// $query->execute(array($m_id,$username));

// $result = $query->fetch($PDO_RESULT_TYPE);

// print_r($result->username);

// $db = null;

function select_db($columns,$table,$where,$data,$others='')
{	
	$db = $_SESSION['link'];

	$sql = "SELECT {$columns}
	        FROM {$table}
	        WHERE  {$where}
	        {$others};"; 

	$query = $db->prepare($sql);

	$query->execute($data);

	$result = $query->fetch(PDO::FETCH_OBJ);

	return $result;
}

$columns = '*';

$table = 'member';

$where = 'm_id = ? AND username = ?';

$data = array($m_id, $username);

$result = select_db($columns,$table,$where,$data,$others='');

print_r($result->username);

?>