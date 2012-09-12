<?php require_once("includes/functions.php");   ?>
<?php require_once("includes/connection.php"); ?>
<?php find_selected_page();?>
<?php include("includes/header.php"); ?>
<table id="structure">

    <tr>
        <td id="navigation">

            <?php echo public_navigation($sel_subject, $sel_page_content); ?>
            
            <!--
            <br />
            <a href="new_subject.php">+ Add a new subject</a><br/>
            <a href="new_page.php">+ Add a new page</a>
            -->

        </td>
        <td id="page">              
                <?php
                if (isset($sel_subject["menu_name"])/*)
                {
                    echo "<h2>".$sel_subject["menu_name"]."</h2>";
                } elseif (*/ || isset($sel_page_content["menu_name"]))
                {        
                    echo "<h2>".$sel_page_content["menu_name"]."</h2>";
                    echo "<div class=\"page-content\">";
                    echo $sel_page_content["content"];
                    echo "</div>";
                }
                else
                {
                    echo "<h2>Welcome to Apparatus Corp. :D</h2>";
                }
                ?>
               <br/>
        </td>
    </tr>

</table>

<?php require("includes/footer.php"); ?>