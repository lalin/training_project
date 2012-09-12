<?php require_once("includes/session.php");   ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php include("includes/header.php"); ?>
<table id="structure">

    <tr>
        <td id="navigation">

            <?php //echo navigation($sel_subject, $sel_page_content); ?>
            <!--
            <a href="content.php">Cancel</a>
            -->

        </td>
        <td id="page">              
            <h2>Add User</h2>
            <form action="create_user.php" method="POST">
                <p>Username: <input type="text" name="username" value="" id="username"/></p>
                <p>Password: <input type="password" name="password" value="" id="password"/></p>
                <input type="submit" value="Add User" />
            </form>
        
            <br/>
            <a href="staff.php">Cancel</a>
        </td>
    </tr>

</table>

<?php require("includes/footer.php"); ?>