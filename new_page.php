<?php require_once("includes/session.php");   ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("includes/connection.php"); ?>
<?php find_selected_page();?>
<?php include("includes/header.php"); ?>
<table id="structure">

    <tr>
        <td id="navigation">

            <?php echo navigation($sel_subject, $sel_page_content); ?>
            

        </td>
        <td id="page">              
            <h2>Add Page</h2>
            <form action="create_page.php" method="POST">
                <p>Page name: <input type="text" name="menu_name" value="" id="menu_name"/></p>
                <p>Subject: 
                    <select name="subject">
                        <?php
                            $subject_set = get_all_subjects();
                            $subject_count = mysql_num_rows($subject_set);

                            for ($count = 0; $count < $subject_count; $count++)
                            {
                                $subject_record = mysql_fetch_array($subject_set);
                                $subj_id = intval($subject_record["id"]);
                                echo  "<option value=\"{$subject_record["id"]}\">{$subject_record["menu_name"]}</option>";
                            }
                        ?>
                    </select>
                </p>
                <p>Position: 
                    <select name="position">
                        <?php
                            //$page_set = get_pages_for_subject($sel_page_content[0]);
                            $page_set = get_pages_for_subject(1);    //TODO: Change 0 for selected subject_id, need javascript???
                            $page_count = mysql_num_rows($page_set);
                            // $subject_count + 1 because we are adding something
                            for ($count = 1; $count <= $page_count + 1; $count++)
                            {
                                echo  "<option value=\"{$count}\">{$count}</option>";
                            }
                        ?>
                    </select>
                </p>
                <p>Visible:
                    <input type="radio" name="visible" value="0" /> No
                    &nbsp;
                    <input type="radio" name="visible" value="1" /> Yes
                </p>
                <p>Content:</p>
                <textarea name="content" rows="4" cols="20">
                </textarea>
                <br/>
                <br/>
                <br/>
                <input type="submit" value="Add Page" />
            </form>
        
            <br/>
            <a href="content.php">Cancel</a>
        </td>
    </tr>

</table>

<?php require("includes/footer.php"); ?>