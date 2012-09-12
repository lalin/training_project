<?php require_once("includes/session.php");   ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("includes/connection.php"); ?>
<?php //find_selected_page();?>
<?php

    if (intval($_GET["subj"]) == 0)
    {
        redirect_to("content.php");
    }
    
    if (isset($_POST["submit"]))
    {
        $errors = array();
         
        //Form validation
        $required_fields = array("menu_name", "position", "visible");
        foreach ($required_fields as $fieldname)
        {
            if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && !is_numeric($_POST[$fieldname])))
            {
                $errors[] = $fieldname;
            }
        }
        
        $fields_with_lengths = array("menu_name" => 30);
        foreach ($fields_with_lengths as $fieldname => $maxlength)
        {
            if (strlen(trim(mysql_prep($_POST["$fieldname"]))) > $maxlength)
            {
                $errors[] = $fieldname;
            }
        }
        
        if (empty($errors))
        {
            //redirect_to("new_subject.php");
            //Perform update                    
            $id = mysql_prep($_GET["subj"]);
            $menu_name = mysql_prep($_POST["menu_name"]);
            $position = mysql_prep($_POST["position"]);
            $visible = mysql_prep($_POST["visible"]);
            
            $query = "UPDATE subjects SET
                    menu_name='{$menu_name}',
                    position = {$position},
                    visible = {$visible}
                    WHERE id = {$id}";
            $result = mysql_query($query, $connection);
            if (mysql_affected_rows() == 1)
            {
                // Success
                $message = "The subject was successfully updated";
            }
            else
            {
                // Failed
                $message = "The subject update failed.";
                $message .= "<br/>" . mysql_error();
                
            }
        }
        else
        {
            //Errors occurred
                $message = "There were " . count($errors) . " errors in the form.";            
        }

    }   //End: if (isset($_POST["submit"]))
?>
<?php find_selected_page();?> //This moved to here because can't be fetched before update
<?php include("includes/header.php"); ?>
<table id="structure">

    <tr>
        <td id="navigation">

            <?php echo navigation($sel_subject, $sel_page_content); ?>
            

        </td>
        <td id="page">              
            <h2>Edit Subject: <?php echo $sel_subject["menu_name"]; ?></h2>
            <?php
                if (!empty($message))
                {
                    echo "<p class=\"message\">" . $message . "</p>";
                }
            ?>
            <?php
                //Output a list of the fields that had errors
                if (!empty($errors))
                {
                    echo "<p class=\"errors\">";
                    echo "Please review the following fields:<br/>";
                    foreach($errors as $error)
                    {
                        echo " - " . $error . "<br/>";
                    }
                    echo "</p>";
                }
            ?>
            <form action="edit_subject.php?subj=<?php echo urlencode($sel_subject["id"]); ?>" method="POST">
                <p>Subject name: <input type="text" name="menu_name" value="<?php
//                    if (isset($_POST["menu_name"]))
//                    {
//                        echo $_POST["menu_name"];
//                    }
//                    else
//                    {
                        echo $sel_subject["menu_name"];
//                    }
                ?>" id="menu_name"/></p>
                <p>Position: 
                    <select name="position" >
                        <?php
                            $subject_set = get_all_subjects();
                            $subject_count = mysql_num_rows($subject_set);
                            // $subject_count + 1 because we are adding something
                            for ($count = 1; $count <= $subject_count + 1; $count++)
                            {
                                echo  "<option ";
                                /*if (isset($_POST["position"]) && $count == $_POST["position"])
                                {
                                    echo  "selected=\"selected\" ";
                                }
                                else*/if ($sel_subject["position"] == $count)
                                {
                                    echo  "selected=\"selected\" ";
                                }
                                echo "value=\"{$count}\">{$count}</option>";
                            }
                        ?>
                       
                    </select>
                </p>
                <p>Visible:
                    <input type="radio" name="visible" value="0" <?php
                    /*if (isset($_POST["visible"]) && $_POST["visible"] == 0)
                    {
                        echo "checked=\"checked\" ";
                    } 
                    else*/if ($sel_subject["visible"] == 0)
                    {
                        echo "checked=\"checked\" ";
                    }                   
                    ?>/> No
                    &nbsp;
                    <input type="radio" name="visible" value="1" <?php
                    /*if (isset($_POST["visible"]) && $_POST["visible"] == 1)
                    {
                        echo "checked=\"checked\" ";
                    }  
                    else*/if ($sel_subject["visible"] == 1)
                    {
                        echo "checked=\"checked\" ";
                    }                     
                    ?>/> Yes
                </p>
                <input type="submit" name="submit" value="Edit Subject" />
                &nbsp;&nbsp;
                <a href="delete_subject.php?subj=<?php echo urlencode($sel_subject["id"]); ?>" onclick="return confirm('Are you sure?');">Delete Subject</a>
            </form>
        
            <br/>
            <a href="content.php">Cancel</a>
        </td>
    </tr>

</table>

<?php require("includes/footer.php"); ?>