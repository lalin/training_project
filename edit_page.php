<?php require_once("includes/session.php");   ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("includes/connection.php"); ?>
<?php //find_selected_page();?>
<?php

    if (intval($_GET["page"]) == 0)
    {
        redirect_to("content.php");
    }
    
    if (isset($_POST["submit"]))
    {
        $errors = array();
         
        //Form validation
        $required_fields = array("menu_name", "subject", "position", "visible", "content");
        foreach ($required_fields as $fieldname)
        {
            if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && !is_numeric($_POST[$fieldname])))
            {
                $errors[] = $fieldname;
            }
        }
        
        $fields_with_lengths = array("menu_name" => 30, "content" => 80);
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
            $id = mysql_prep($_GET["page"]);
            $menu_name = mysql_prep($_POST["menu_name"]);
            $subject_id = mysql_prep($_POST["subject"]);
            $position = mysql_prep($_POST["position"]);
            $visible = mysql_prep($_POST["visible"]);
            $page_content = mysql_prep($_POST["content"]);
            
            $query = "UPDATE pages SET
                    menu_name='{$menu_name}',
                    subject_id = {$subject_id},
                    position = {$position},
                    visible = {$visible},
                    content='{$page_content}'
                    WHERE id = {$id}";
            $result = mysql_query($query, $connection);
            if (mysql_affected_rows() == 1)
            {
                // Success
                $message = "The page was successfully updated";
            }
            else
            {
                // Failed
                $message = "The page update failed.";
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
            <h2>Edit Page: <?php echo $sel_page_content["menu_name"]; ?></h2>
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
            <form action="edit_page.php?page=<?php echo urlencode($sel_page_content["id"]); ?>" method="POST">
                <p>Page name: <input type="text" name="menu_name" value="<?php
//                    if (isset($_POST["menu_name"]))
//                    {
//                        echo $_POST["menu_name"];
//                    }
//                    else
//                    {
                        echo $sel_page_content["menu_name"];
//                    }
                ?>" id="menu_name"/></p>
                
                <p>Subject: 
                    <select name="subject">
                        <?php
//                            $subject_set = get_subject_by_id($sel_page_content["subject_id"]);
//                            $subject_count = mysql_num_rows($subject_set);
//
//                            for ($count = 0; $count < $subject_count; $count++)
//                            {
//                                $subject_record = mysql_fetch_array($subject_set);
//                                $subj_id = intval($subject_record["id"]);
//                                echo  "<option value=\"{$subject_record["id"]}\">{$subject_record["menu_name"]}</option>";
//                            }
                            
                            
                            $subject_set = get_all_subjects();
                            $subject_count = mysql_num_rows($subject_set);

                            for ($count = 0; $count < $subject_count; $count++)
                            {
                                $subject_record = mysql_fetch_array($subject_set);
                                $subj_id = intval($subject_record["id"]);
                                echo  "<option ";
                                if ($sel_page_content["subject_id"] == $subj_id)
                                {
                                    echo "selected=\"selected\" ";
                                }
                                echo "value=\"{$subject_record["id"]}\">{$subject_record["menu_name"]}</option>";
                            }
                        ?>
                    </select>
                </p>
                <p>Position: 
                    <select name="position" >
                        <?php                            
                            $page_set = get_pages_for_subject($sel_page_content["subject_id"]);
                            $page_count = mysql_num_rows($page_set);
                            // $subject_count + 1 because we are adding something
                            for ($count = 1; $count <= $page_count + 1; $count++)
                            {
                                echo "<option ";
                                if ($sel_page_content["position"] == $count)
                                {
                                    echo "selected=\"selected\" ";
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
                    else*/if ($sel_page_content["visible"] == 0)
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
                    else*/if ($sel_page_content["visible"] == 1)
                    {
                        echo "checked=\"checked\" ";
                    }                     
                    ?>/> Yes
                </p>
                
                <p>Content:</p>
                <textarea name="content" rows="4" cols="20">
                <?php echo $sel_page_content["content"]; ?>
                </textarea>
                <br/>
                <br/>
                <br/>                
                
                <input type="submit" name="submit" value="Edit Page" />
                &nbsp;&nbsp;
                <a href="delete_page.php?page=<?php echo urlencode($sel_page_content["id"]); ?>" onclick="return confirm('Are you sure?');">Delete Page</a>
            </form>
        
            <br/>
            <a href="content.php">Cancel</a>
        </td>
    </tr>

</table>

<?php require("includes/footer.php"); ?>