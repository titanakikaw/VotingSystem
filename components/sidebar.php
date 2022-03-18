<div class="side-bar">
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