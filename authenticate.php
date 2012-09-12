<?php require_once("includes/session.php");   ?>
<?php require_once("includes/functions.php");   ?>
<?php require_once("includes/connection.php"); ?>
<?php
    $errors = array();

    //Form validation
    $required_fields = array("username", "password");
    foreach ($required_fields as $fieldname)
    {
        if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname]))
        {
            $errors[] = $fieldname;
        }
    }

    $fields_with_lengths = array("username" => 30, "password" => 30);
    foreach ($fields_with_lengths as $fieldname => $maxlength)
    {
        if (strlen(trim(mysql_prep($_POST["$fieldname"]))) > $maxlength)
        {
            $errors[] = $fieldname;
        }
    }

    if (!empty($errors))
    {
        redirect_to("login.php");
    }
?>
<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    $username = mysql_prep(htmlentities($_POST["username"]));
    $password = mysql_prep(htmlentities($_POST["password"]));

?>

<?php

    $hashed_password = sha1($password);
    
    if (authenticate_user($username, $hashed_password))
    {
        //Success!
        $_SESSION['username'] = $username;
        redirect_to("staff.php");        
    }
    
    //Display error message.
    echo "<p>User/password combination invalid!</p>";  

?>
<?php
    mysql_close($connection);
?>
