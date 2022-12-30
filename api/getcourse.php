<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';
require __DIR__.'/AuthMiddleware.php';


$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();

$query ="SELECT a.fullname,a.shortname,a.summary,b.name as category,a.newsitems,a.startdate,a.enddate,a.originalcourseid,a.showreports ,(SELECT COUNT(id)FROM mdl_enrol WHERE courseid=a.id )as user_count FROM mdl_course as a Left JOIN mdl_course_categories as b ON a.category = b.id ORDER BY 'id' DESC LIMIT 6"  ;


   $stmt = $conn->query($query);
   $course = $stmt->fetchAll(PDO::FETCH_ASSOC);
// foreach ($conn->query($query) as $row) {
// 	$course[] = $row;

// }

echo json_encode($course);
?>