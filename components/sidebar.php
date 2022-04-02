<?php

    // var_dump($_SESSION);
    $user_image =  '../client'.ltrim($_SESSION['user_information']['image'],'.');
    // var_dump($_SESSION['user_information']['image']);
    $lname = $_SESSION['user_information']['lname'];
    $fname =  $_SESSION['user_information']['fname'];
?>

<div class="side-bar">
    <div class="sidebar_item" style=' display:flex;align-items:center;flex-direction:column; background-color:#0c2d48;padding: 1rem 0;'>
        <!-- <div class="sb-title">
            <p>User Information</p>
        </div> -->
        <div class="" style="display: flex; align-items:center; justify-content:space-between">
            <div class="" style='border-radius: 50%; width: 50px; height:50px; overflow:hidden;margin-top:5px; border : 3px solid #0c2d48'>
                <img src="<?php echo $user_image ?>" alt='user_image' width="100%">
            </div>
            <div class="" style="margin-left:5px;">
                <h4 style="color:white; text-transform:uppercase; letter-spacing:1.1px"><?php echo  $lname?></h4>
                <h5 style="color:white; text-transform:uppercase; letter-spacing:1.1px"><?php echo  $fname?></h5>
            </div>
        
        </div>
    </div>
    <?php
        $uri = str_replace('/VotingSys/client/','', $_SERVER['REQUEST_URI']);
        $auth_level = $_SESSION['auth_level']; 
        $query ="SELECT parent from menus where auth_level=? group by parent";
        $xstmt =$conn->prepare($query);
        $xstmt->execute([$auth_level]);
        $xdata = $xstmt->fetchAll();
        foreach ($xdata as $key => $value) {
            echo '  <div class="sidebar_item">
                        <div class="sb-title">
                            <p>'.$value['parent'].'</p>
                        </div>
                        <div class="sb-items">
                            <ul>';
                            $query_item = "SELECT * from menus where auth_level=? and parent=? ORDER BY name ASC";
                            $xstmt_item = $conn->prepare($query_item);
                            $xstmt_item->execute([ $auth_level,$value['parent']]);
                            $xdata_item = $xstmt_item->fetchAll();
                            foreach ($xdata_item as $key_item => $value_item) {
                                if($uri == $value_item['link']){
                                    echo'<li class="active">';
                                }
                                else{
                                    echo'<li>';
                                }
                                echo    '<span class="material-icons">'.$value_item['icon'].'</span><a href="'.$value_item['link'].'">'.$value_item['name'].'</a></li>';
                            }
            echo '          </ul>
                        </div>
                    </div>';
        }
    ?>
</div>