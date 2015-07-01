
<?php

include("ajax_table.class.php");
$obj = new ajax_table();
$records = $obj->getRecords();
$conn=$obj->dbconnect();
//echo phpinfo();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <style type="text/css">

 body{
            font-size:1.2em;

        }
        th,td{
            text-align:center;
        }
    </style>
    <title>Change the Informations</title>
    <script>
        // Column names must be identical to the actual column names in the database, if you dont want to reveal the column names, you can map them with the different names at the server side.
        var columns = new Array("division","district","thana","sub_office","post_code","outside_of_dhaka");
        var placeholder = new Array("Enter Division","Enter District","Enter Thana","Enter Sub_Office","Enter Post_Code","Enter Outside_Of_Dhaka");
        var inputType = new Array("text","text","text","text","text","text");
        var table = "table";
        // var selectOpt = new Array("Pune","Karad","Kolhapur","Satara","Sangli");;


        // Set button class names
        var savebutton = "ajaxSave";
        var deletebutton = "ajaxDelete";
        var editbutton = "ajaxEdit";
        var updatebutton = "ajaxUpdate";
        var cancelbutton = "cancel";

        var saveImage = ""
        var editImage = ""
        var deleteImage = ""
        var cancelImage = ""
        var updateImage = ""

        // Set highlight animation delay (higher the value longer will be the animation)
        var saveAnimationDelay = 3000;
        var deleteAnimationDelay = 1000;

        // 2 effects available available 1) slide 2) flash
        var effect = "flash";

    </script>



    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/script.js"></script>

</head>
<body>
<div class="container">
    <div class="row">
        <h2 style="text-align: center">District Information</h2>
<div class="table-responsive">
<table border="0" class=" table table-striped table-hover table-bordered table-condensed" >

<tr>
   <th>ID</th>
    <th>DIVISION</th>
    <th>DISTRICT</th>
    <th>THANA</th>
    <th>SUB_OFFICE</th>
    <th>POST_CODE</th>
    <th>OUTSIDE_OF_DHAKA</th>
    <th>ACTION</th>
</tr>

    <?php
if(count($records)){
        $i = 1;
        $eachRecord= 0;
        foreach($records as $key=>$eachRecord){
            ?>


            <tr id="<?=$eachRecord['id'];?>">
                <td><?=$eachRecord['id'];?></td>
                <td class="division"><?=$eachRecord['division'];?></td>
                <td class="district"><?=$eachRecord['district'];?></td>
                <td class="thana"><?=$eachRecord['thana'];?></td>
                <td class="sub_office"><?=$eachRecord['sub_office'];?></td>
                <td class="post_code"><?=$eachRecord['post_code'];?></td>
                <td class="outside_of_dhaka"><?=$eachRecord['outside_of_dhaka'];?></td>
                <td>
                    <a href="javascript:;" id="<?=$eachRecord['id'];?>" class="ajaxEdit"><img src="" class="eimage"> <span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="javascript:;" id="<?=$eachRecord['id'];?>" class="ajaxDelete"><img src="" class="dimage">  <span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
        <?php }
    }

    ?>


</table>

</div>

<?php
echo "<br>";
$res1=mysqli_query($conn,"select * from locations");
$count=mysqli_num_rows($res1);
$res1=ceil($count/10);

if(!isset($_GET["page"])&& empty($_GET["page"])){
    $currentpage=1;

}
else{
$currentpage=$_GET['page'];}

if($currentpage==0){
    $currentpage=1;
}

$prev=$currentpage-1;
if($prev<=1){
    $prev=1;

}?>
        <?php

        $next=$currentpage+1;
        if($next>=$res1){
            $next=$res1;

        }?>

<!--        <a href="index.php?page=1" ><span class="glyphicon glyphicon-home"></span></a>-->
<!--<php echo " ";?>-->
<!--<a href="index.php?page=--><?php //echo $prev ?><!--"  ><span class="glyphicon glyphicon-chevron-left"></span></a>-->




<!--    <a href="index.php?page=--><?php //echo $b;?><!--" style="text-decoration: none" > --><?php //if($currentpage==$b){echo "<big><b>[$b]</b></big>";} else echo $b." ";?><!--</a>-->
        <div class="row" align="center">



    <nav>
        <ul class="pagination">
            <li>
                <a href="index.php?page=1" aria-label="Previous">
                    <span aria-hidden="true">First</span>
                </a>
            </li>
            <li>
                <a href="index.php?page=<?php echo $prev ?>" aria-label="Previous">
                    <span aria-hidden="true">Previous</span>
                </a>
            </li>
            <?php

            for($b =$currentpage-4;$b <= $currentpage+5 && $b <= $res1; $b++ ){?>
                <?php if($b<=0){ $b=1;}?>

            <li <?php if($currentpage==$b){echo "class=active";}?>><a href="index.php?page=<?php echo $b;?>"> <?php  echo $b; ?></a></li>

            <?php }  ?>

            <li>
                <a href="index.php?page=<?php echo $next;?>" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>
            <li>
                <a href="index.php?page=<?php echo $res1;?> "  aria-label="Next">
                    <span aria-hidden="true">Last</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
</div>
</div>

<!--<a href="index.php?page=--><?php //echo $next;?><!--"  ><span class="glyphicon glyphicon-chevron-right"></span></a>-->
<!--<a href="index.php?page=--><?php //echo $res1;?><!--"> <span class="glyphicon glyphicon-fast-forward"></a>-->

</body>
</html>
