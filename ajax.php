<?php
require_once("ajax_table.class.php");
$obj = new ajax_table();
$conn=$obj->dbconnect();
function array_map_callback($a)
{
    global $conn;
    return mysqli_real_escape_string($conn, $a);
}
if(isset($_POST) && count($_POST)){

    // whats the action ??

    $action = $_POST['action'];
    unset($_POST['action']);

    if($action == "save"){
        // remove 'action' key from array, we no longer need it

        // Never ever believe on end user, he could be a evil minded
        $escapedPost = array_map('array_map_callback', $_POST);
        $escapedPost = array_map('htmlentities', $escapedPost);

        $res = $obj->save($escapedPost);

        if($res){
            $escapedPost["success"] = "1";
            $escapedPost["id"] = $res;
            echo json_encode($escapedPost);
        }
        else
            echo $obj->error("save");
    }else if($action == "del"){
        $id = $_POST['rid'];
        $res = $obj->delete_record($id);
        if($res)
            echo json_encode(array("success" => "1","id" => $id));
        else
            echo $obj->error("delete");
    }
    else if($action == "update"){

        $escapedPost = array_map('array_map_callback', $_POST);
        $escapedPost = array_map('htmlentities', $escapedPost);

        $id = $obj->update_record($escapedPost);
        if($id)
            echo json_encode(array_merge(array("success" => "1","id" => $id),$escapedPost));
        else
            echo $obj->error("update");
    }
    else if($action == "updatetd"){

        $escapedPost = array_map('array_map_callback', $_POST);
        $escapedPost = array_map('htmlentities', $escapedPost);

        $id = $obj->update_column($escapedPost);
        if($id)
            echo json_encode(array_merge(array("success" => "1","id" => $id),$escapedPost));
        else
            echo $obj->error("updatetd");
    }
}
?>