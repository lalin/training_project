<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// This file is to store all the basic functions 
function mysql_prep($value)
{
    $magic_quotes_active = get_magic_quotes_gpc();
    // i.e. PHP >= v4.3.0
    $new_enough_php = function_exists("mysql_real_escape_string");
    
    if ($new_enough_php)
    {
        if ($magic_quotes_active)
        {
            $value = stripslashes($value);
        }
        $value = mysql_real_escape_string($value);
    }
    else
    {
        if (!$magic_quotes_active)
        {
            $value = addslashes($value);
        }
    }
    
    return $value;
}

function redirect_to($location = null)
{
    if ($location != null)
    {
        header("Location: {$location}");
        exit;
    }
}

function confirm_query($result_set) {
    if (!$result_set) {
        die("Database query failed: " . mysql_error());
    }
}

function get_all_subjects($public = false) {
    global $connection;
    $query = "
                    SELECT * 
                    FROM subjects ";
    if ($public)
    {
        $query .= "WHERE visible=1 ";
    }
    $query .= "ORDER BY position ASC";
    $subject_set = mysql_query($query, $connection);

    confirm_query($subject_set);

    return $subject_set;
}

function get_pages_for_subject($subject_id, $public = false) {
    global $connection;
    $query = "
                        SELECT * 
                        FROM pages 
                        WHERE subject_id={$subject_id} ";
    if ($public)
    {
        $query .= "AND visible=1 ";
    }
    $query .= "ORDER BY position ASC";
    $page_set = mysql_query($query, $connection);

    confirm_query($page_set);

    return $page_set;
}

function get_subject_by_id($subject_id, $public = false)
{
    global $connection;
    
    $query = "SELECT * ";
    $query .= "FROM subjects ";
    $query .= "WHERE id=" . $subject_id . " ";
    if ($public)
    {
        $query .= "AND visible=1 ";
    }
    $query .= "LIMIT 1";

    
    $result_set = mysql_query($query, $connection);
    confirm_query($result_set);
    
    // If no rows are returned, fetch_array will return false
    if ($subject = mysql_fetch_array($result_set))
    {
        return $subject;
    }
    else
    {
        return NULL;
    }
}

function get_page_by_id($page_id, $public = false)
{
    global $connection;
    
    $query = "SELECT * ";
    $query .= "FROM pages ";
    $query .= "WHERE id=" . $page_id . " ";
    if ($public)
    {
        $query .= "AND visible=1 ";
    }
    $query .= "LIMIT 1";

    
    $result_set = mysql_query($query, $connection);
    confirm_query($result_set);
    
    // If no rows are returned, fetch_array will return false
    if ($page = mysql_fetch_array($result_set))
    {
        return $page;
    }
    else
    {
        return NULL;
    }
}

function get_default_page($subject_id)
{
    $page_set = get_pages_for_subject($subject_id, true);
    if ($first_page = mysql_fetch_array($page_set))
    {
        return $first_page;
    }
    else
    {
        return null;
    }
}

function find_selected_page()
{
    global $sel_subject;
    global $sel_page_content;
    
    if (isset($_GET['subj']))
    {
        $sel_subject = get_subject_by_id($_GET['subj']);
        //$sel_page_content = null;
        $sel_page_content = get_default_page($sel_subject["id"]);
    } elseif (isset($_GET['page']))
    {
        $sel_subject = null;
        $sel_page_content = get_page_by_id($_GET['page']);
    } else
    {
        $sel_subject = null;
        $sel_page_content = null;
    }
}

function navigation($sel_subject, $sel_page_content)
{
        $output = "<ul class=\"Subjects\">";

        //3 - Perform database query
        $subject_set = get_all_subjects();

        //4 - Use returned data
        while ($subject = mysql_fetch_array($subject_set)) {
            if (isset($sel_subject) && $sel_subject["id"] == $subject["id"])
            {
                $output .= "<li class=\"selected\"><a href=\"edit_subject.php?subj=" . urlencode($subject["id"]) . 
                        "\">{$subject["menu_name"]}</a></li>";
            } 
            else
            {
                $output .= "<li><a href=\"edit_subject.php?subj=" . urlencode($subject["id"]) . 
                        "\">{$subject["menu_name"]}</a></li>";

            }


            //3 - Perform database query... again
            $page_set = get_pages_for_subject($subject["id"]);

            $output .= "<ul class=\"pages\">";
            //4 - Use returned data
            while ($page = mysql_fetch_array($page_set)) {
                if (isset($sel_page_content) && $sel_page_content["id"] == $page["id"])
                {
                    $output .= "<li class=\"selected\"><a href=\"edit_page.php?page=" . urlencode($page["id"]) . 
                            "\">{$page["menu_name"]}</a></li>";
                }
                else
                {
                    $output .= "<li><a href=\"edit_page.php?page=" . urlencode($page["id"]) . 
                            "\">{$page["menu_name"]}</a></li>";
                }
            }
            $output .= "</ul>";
        }

        $output .= "</ul>";
        
        return $output;
}


function public_navigation($sel_subject, $sel_page_content)
{
        $output = "<ul class=\"Subjects\">";    // BEGIN: Subjects

        //3 - Perform database query
        $subject_set = get_all_subjects(true);

        //4 - Use returned data
        while ($subject = mysql_fetch_array($subject_set)) {
            if (isset($sel_subject) && $sel_subject["id"] == $subject["id"])
            {
                $output .= "<li class=\"selected\"><a href=\"index.php?subj=" . urlencode($subject["id"]) . 
                        "\">{$subject["menu_name"]}</a></li>";
                        
                        
                        
                //3 - Perform database query... again
                $page_set = get_pages_for_subject($subject["id"], true);

                $output .= "<ul class=\"pages\">";      // BEGIN: Pages
                //4 - Use returned data
                while ($page = mysql_fetch_array($page_set)) {
                    if (isset($sel_page_content) && $sel_page_content["id"] == $page["id"])
                    {
                        $output .= "<li class=\"selected\"><a href=\"index.php?page=" . urlencode($page["id"]) . //"&subj=" . urlencode($subject["id"]) . 
                                "\">{$page["menu_name"]}</a></li>";
                    }
                    else
                    {
                        $output .= "<li><a href=\"index.php?page=" . urlencode($page["id"]) . //"&subj=" . urlencode($subject["id"]) . 
                                "\">{$page["menu_name"]}</a></li>";
                    }
                }
                $output .= "</ul>"; //END: Pages
                
                
            } 
            else
            {
                $output .= "<li><a href=\"index.php?subj=" . urlencode($subject["id"]) . 
                        "\">{$subject["menu_name"]}</a></li>";
                   
                if ($subject["id"] == $sel_page_content["subject_id"])
                {
                    //3 - Perform database query... again
                    $page_set = get_pages_for_subject($subject["id"], true);

                    $output .= "<ul class=\"pages\">";      // BEGIN: Pages
                    //4 - Use returned data
                    while ($page = mysql_fetch_array($page_set)) {
                        if (isset($sel_page_content) && $sel_page_content["id"] == $page["id"])
                        {
                            $output .= "<li class=\"selected\"><a href=\"index.php?page=" . urlencode($page["id"]) . 
                                    "\">{$page["menu_name"]}</a></li>";
                        }
                        else
                        {
                            $output .= "<li><a href=\"index.php?page=" . urlencode($page["id"]) . 
                                    "\">{$page["menu_name"]}</a></li>";
                        }
                    }
                    $output .= "</ul>"; //END: Pages
                }

            }


            
//            //3 - Perform database query... again
//            $page_set = get_pages_for_subject($subject["id"]);
//
//            $output .= "<ul class=\"pages\">";      // BEGIN: Pages
//            //4 - Use returned data
//            while ($page = mysql_fetch_array($page_set)) {
//                if (isset($sel_page_content) && $sel_page_content["id"] == $page["id"])
//                {
//                    $output .= "<li class=\"selected\"><a href=\"index.php?page=" . urlencode($page["id"]) . 
//                            "\">{$page["menu_name"]}</a></li>";
//                }
//                else
//                {
//                    $output .= "<li><a href=\"index.php?page=" . urlencode($page["id"]) . 
//                            "\">{$page["menu_name"]}</a></li>";
//                }
//            }
//            $output .= "</ul>"; //END: Pages
        }

        $output .= "</ul>"; // END: Subjects
        
        return $output;
}

function check_user_exist($username)
{
    global $connection;
    
    $query = "SELECT * ";
    $query .= "FROM users ";
    $query .= "WHERE username='" . $username . "'";

    
    $result_set = mysql_query($query, $connection);
    confirm_query($result_set);
    
    // If no rows are returned, fetch_array will return false
    if ($user_record = mysql_fetch_array($result_set))
    {
        return true;
    }
    else
    {
        return false;
    } 
}

function authenticate_user($username, $hashed_password)
{
    global $connection;
    
    //$query = "SELECT * ";
    $query = "SELECT id, username ";    //Just fetch id and username, is MORE secure
    $query .= "FROM users ";
    $query .= "WHERE username='" . $username . "' AND hashed_password='" . $hashed_password . "'";

    
    $result_set = mysql_query($query, $connection);
    confirm_query($result_set);
    
    // If no rows are returned, fetch_array will return false
    if ($user_record = mysql_fetch_array($result_set))
    {
        return true;
    }
    else
    {
        return false;
    }  
}

function login()
{
    $output = "<form name=\"login\" action=\"authenticate.php\" method=\"POST\">";
    $output .= "<p>Username: &nbsp;&nbsp;";
    $output .= "<input type=\"text\" name=\"username\" value=\"\" size=\"30\" /></p>";
    $output .= "<p>Password: &nbsp;&nbsp;";
    $output .= "<input type=\"password\" name=\"password\" value=\"\" size=\"30\" /></p>";
    $output .= "<input type=\"submit\" value=\"Login\" />";
    $output .= "</form>";
    
    return $output;
}
?>
