<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ . '/classes/Database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

function msg($success, $status, $message, $extra = [])
{
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}

// DATA FORM REQUEST
// $data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (

   !isset($_POST["email"])
   ||!isset($_POST["password"])
   ||!isset($_POST["firstname"])
   ||!isset($_POST["lastname"])
   || empty(trim($_POST["email"]))
   || empty(trim($_POST["password"]))
) :
// $email = $_post['email'];
// $password = $_post['password'];
// $firstname = $_post['firstname'];
// $lastname = $_post['lastname'];

    $fields = ['fields' => ['firstname','lastname', 'email', 'password']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :

    // $firstname = trim($data->firstname);
    // $lastname = trim($data->lastname);
    // $username = trim($data->email);
    // $email = trim($data->email);
     $plaintext_password = $_POST['password'];
    $hash = password_hash($plaintext_password, 
    PASSWORD_DEFAULT);

    
      $email = trim($_POST['email']);
      $password = trim($hash);
      $firstname = trim($_POST['firstname']);
      $lastname = trim($_POST['lastname']);
      $username = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
        $returnData = msg(0, 422, 'Invalid Email Address!');

    elseif (strlen($password) < 8) :
        $returnData = msg(0, 422, 'Your password must be at least 8 characters long!');

    elseif (strlen($firstname) < 3) :
        $returnData = msg(0, 422, 'Your firstname must be at least 3 characters long!');
        

    else :
        try {

            $check_email = "SELECT `email` FROM `mdl_user` WHERE `email`=:email";
            $check_email_stmt = $conn->prepare($check_email);
            $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $check_email_stmt->execute();

            if ($check_email_stmt->rowCount()) :
                $returnData = msg(0, 422, 'This E-mail already in use!');

            else :

                $insert_query = "INSERT INTO `mdl_user`  (`id`, `auth`, `confirmed`, `policyagreed`, `deleted`, `suspended`,
                                                        `mnethostid`, `username`, `password`, `idnumber`, `firstname`, `lastname`, `email`, `emailstop`, `phone1`, 
                                                         `phone2`, `institution`, `department`, `address`, `city`, `country`, `lang`, `calendartype`, `theme`, `timezone`,
                                                         `firstaccess`, `lastaccess`, `lastlogin`, `currentlogin`, `lastip`, `secret`, `picture`, `description`,
                                                         `descriptionformat`, `mailformat`, `maildigest`, `maildisplay`, `autosubscribe`, `trackforums`, `timecreated`,
                                                         `timemodified`, `trustbitmask`, `imagealt`, `lastnamephonetic`, `firstnamephonetic`, `middlename`, `alternatename`, `moodlenetprofile`) 
                                                           VALUES (null,'API', '0', '0', '0', '0', '0', '$username', '$password', '', '$firstname', '$lastname',
                                                            '$email', '0', '', '', '', '', '', '', '', 'en', 'gregorian', '', '99', '0', '0', '0', '0', '', '', 
                                                            '0', NULL, '1', '1', '0', '2', '1', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL)";
                //    echo $insert_query;
                //    die();
                $insert_stmt = $conn->prepare($insert_query);



                $insert_stmt->execute();

                $returnData = msg(1, 201, 'You have successfully registered.');

            endif;
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;
endif;


echo json_encode($returnData);