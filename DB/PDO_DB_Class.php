<?php

/*

$db->select($columns,$table,$where,$others='',$data,$format,$fetchALL,$debug=0)

$db->update($table,$columns,$where,$others='',$data,$format,$debug=0)

$db->insert($table,$columns,$values,$data,$format,$debug=0)

$db->delete($table,$where,$others='',$data,$format,$debug=0)

$db->select_num_rows($table,$where,$others='',$data,$format)

$columns = '*';

$table = 'member';

$where = 'cs_id = ?';

$data = array(4);

$format = array($i);

$fetchALL = 1;

*/

$DB_HOST = "122.116.159.53";
$DB_NAME = "ctm_rankbar";
$DB_USER = "rankbar";
$DB_PASS = "rankbar@#18";

$s = PDO::PARAM_STR;
$i = PDO::PARAM_INT;


if ( !class_exists( 'DB' ) ) {

	class DB {

		// protected $PDO_RESULT_TYPE = PDO::FETCH_OBJ;
		protected $PDO_RESULT_TYPE = PDO::FETCH_ASSOC;
		

		public function __construct($DB_USER,$DB_PASS,$DB_NAME,$DB_HOST) {
			$this->DB_USER = $DB_USER;
			$this->DB_PASS = $DB_PASS;
			$this->DB_NAME = $DB_NAME;
			$this->DB_HOST = $DB_HOST;
			
		}

		public function connect() {
			
			try {

				$db = new PDO("mysql:host=".$this->DB_HOST.";

			                dbname=".$this->DB_NAME, $this->DB_USER, $this->DB_PASS,

			                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

			                //PDO::MYSQL_ATTR_INIT_COMMAND 設定編碼

				// echo '連線成功！<br>';

				// $_SESSION['link'] = $db;

				$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); //錯誤訊息提醒

				return $db;

			}catch (PDOException $e){

				printf("資料庫連結失敗: %s <br><br>", $e->getMessage());

			}
		}

		public function select($columns,$table,$where,$others='',$data,$format,$fetchALL,$debug=0){	

			$db = $this->connect();

			$sql = "SELECT {$columns}
			        FROM {$table}
			        WHERE  {$where}
			        {$others};";

			if($debug){

				echo $sql.'<br>';

				print_r($data);

				echo '<br>';

			}

			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

			$query = $db->prepare($sql);


			foreach ($data as $key => $value) {

				$query->bindParam( $key+1, $data[$key], $format[$key] );

			}

			if ($query->execute()) {

				if ($fetchALL == 1) {

					$result = $query->fetchAll(PDO::FETCH_ASSOC);

				}else{

					$result = $query->fetch(PDO::FETCH_ASSOC);
				}

				
				return $result;

			}else{

				echo $table." 資料查詢失敗,請聯絡管理員！";

				

				return FALSE;
			}

			
		}

		public function update($table,$columns,$where,$others='',$data,$format,$debug=0){

			$db = $this->connect();

			$sql = "UPDATE {$table}
				    SET {$columns}
				    WHERE {$where}
				    {$others};";

			if($debug){

				echo $sql.'<br>';

				print_r($data);

				echo '<br>';

			}

			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

			$query = $db->prepare($sql);

			foreach ($data as $key => $value) {

				$query->bindParam( $key+1, $data[$key], $format[$key] );

			}

			if ($query->execute()) {

				return TRUE;

			}else{

				echo $table." 資料更新失敗,請聯絡管理員！";


				return FALSE;
			}

		}

		public function insert($table,$columns,$values,$data,$format,$debug=0){

			$db = $this->connect();

			$sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values});";

			if($debug){

				echo $sql.'<br>';

				print_r($data);

				echo '<br>';

			}

			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

			$query = $db->prepare($sql);

			foreach ($data as $key => $value) {

				$query->bindParam( $key+1, $data[$key], $format[$key] );

			}

			if ($query->execute()) {

				return $db->lastInsertId();

			}else{

				echo $table." 資料新增失敗,請聯絡管理員！";


				return FALSE;
			}


		}



		public function delete($table,$where,$others='',$data,$format,$debug=0){

			$db = $this->connect();

			$sql = "DELETE FROM {$table} WHERE {$where};";

			if($debug){

				echo $sql.'<br>';
				
				print_r($data);

				echo '<br>';

			}

			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

			$query = $db->prepare($sql);

			foreach ($data as $key => $value) {

				$query->bindParam( $key+1, $data[$key], $format[$key] );

			}

			if ($query->execute()) {

				return TRUE;

			}else{

				echo $table." 資料刪除失敗,請聯絡管理員！";


				return FALSE;
			}


		}

		public function select_num_rows($table,$where,$others='',$data,$format)
		{
			$db = $this->connect();

			$sql = "SELECT COUNT(*)
			        FROM {$table}
			        WHERE  {$where}
			        {$others};";


			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

			$query = $db->prepare($sql);


			foreach ($data as $key => $value) {

				$query->bindParam( $key+1, $data[$key], $format[$key] );

			}

			if ($query->execute()) {

				$num_rows = $query->fetchColumn();

				
				return $num_rows;


			}else{

				echo $table." 查詢筆數失敗,請聯絡管理員！";
				

				return FALSE;
			}
		}



		



		



		

	}
}




// $db = new DB($DB_USER,$DB_PASS,$DB_NAME,$DB_HOST);

// $columns = '*';

// $table = 'member';

// $where = 'cs_id = ?';

// $data = array(4);

// $format = array($i);

// $result = $db->select($columns,$table,$where,$others='',$data,$format,$debug=0);


// foreach ($result as $data)
// {
//     echo $data['m_id'] . "<br>";
// }

// echo $result[0]['m_id'] . "<br>";	// 第一筆








// $db = new DB($DB_USER,$DB_PASS,$DB_NAME,$DB_HOST);



// $table = 'member';

// // $columns = 'usernadme,password';

// $where = 'm_id = ?';

// // $values = '?,?';

// $data = array($m_id = 197);

// $format = array($i);

// $db->delete($table,$where,$others='',$data,$format,$debug=1);






?>