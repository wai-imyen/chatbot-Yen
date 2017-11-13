<?php

include_once 'DB/PDO_DB_Class.php';

	// $quick_replies =  [];

	$db = new DB($DB_USER,$DB_PASS,$DB_NAME,$DB_HOST);

	// $columns = '*';

	// $table = 'project';

	// $where = 'm_id = ?';

	// $data = array(2);

	// $format = array($i);

	// $result = $db->select($columns,$table,$where,$others='',$data,$format,$debug=0);

	// $pj_array = array();
	
	// foreach ($result as $data)
	// {
	    

	// 	array_push($pj_array, $data['pj_name']);
	// }
	
	$columns = 'MIN(wait_nums)';

	$table = 'server';

	$where = 's_status != ?';

	$data = array('blocked');

	$format = array($s);

	$fetchALL = 0;

	$min_wait_nums = $db->select($columns,$table,$where,$others='',$data,$format,$fetchALL,$debug=0)['MIN(wait_nums)'];



	$columns = 's_id';

	$table = 'server';

	$where = 'wait_nums = ? AND s_status != ?';

	$others = 'ORDER BY query_nums';

	$data = array($min_wait_nums, "blocked");

	$format = array($i,$s);

	$fetchALL = 0;

	$s_id = $db->select($columns,$table,$where,$others='',$data,$format,$fetchALL,$debug=0)['s_id'];


?>