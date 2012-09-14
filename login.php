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
            <a href="index.php">Retornar al lugar público</a>
        </td>
        <td id="page">
            <h2>Staff Login</h2>
            <?php
            if (isset($_GET["logout"]) && $_GET["logout"]==1)
            {
                echo "<p>Pa'fuera mi'jo/'ja.</p>";
            }
            ?>
            <?php echo login(); ?>
        </td>
    </tr>

</table>

<?php include("includes/footer.php");
?>