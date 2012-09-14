<?php require_once("includes/session.php");   ?>
<?php require_once("includes/functions.php"); ?>
<?php 
    if (logged_in()) {
        redirect_to("staff.php");
    }
?>
<?php include("includes/header.php"); ?>
<table id="structure">

    <tr>
        <td id="navigation">
            <a href="index.php">"Return to public site"</a>
        </td>
        <td id="page">
            <h2>Staff Login</h2>
            <?php
            if (isset($_GET["logout"]) && $_GET["logout"]==1)
            {
                echo "<p>You are now logged out!</p>";
            }
            ?>
            <?php echo login(); ?>
        </td>
    </tr>

</table>

<?php include("includes/footer.php");
?>