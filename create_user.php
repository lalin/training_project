<?php require_once("includes/session.php");   ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
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
        redirect_to("new_user.php");
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
    
    if (!check_user_exist($username))
    {
        $query = "INSERT INTO users (
                username, hashed_password
                ) values (
                '{$username}', '{$hashed_password}'
                )";

        if (mysql_query($query, $connection))
        {
            //Success!
            redirect_to("content.php");
        }
        else
        {
            //Display error message.
            echo "<p>User creation failed.</p>";
            echo "<p>" . mysql_error() . "</p>";
        }
    }
    else
    {
        //Display error message.
        echo "<p>User already exist.</p>";     
    }

?>
<?php
    mysql_close($connection);
?>
