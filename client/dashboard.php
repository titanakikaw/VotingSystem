<?php
    require 'main.php';
    $eletion_data = array();
    $hidden = '';
    if(isset($_SESSION['admin_id'])){
        $hidden = 'display:flex';
    }
    else{
        $hidden = 'display:none!important';
    }
?>

<div class="db-content">
    <h1>Dashboard</h1>
    <div class="list-filter" style="width: 420px;display:flex; align-items:center;">
        <!-- <p style="font-size:12px">Showing data of : </p> -->
        <select style="font-size:12px;font-weight: normal;width: 235px;border: none;background-color:transparent!important;margin:0px;" id="election_id" onchange="getcount_cards()"> 
            <?php
              

                $query_election = "SELECT * from election";
                $stmt_election = $conn->prepare($query_election);
                $stmt_election->execute();
                $xdata = $stmt_election->fetchAll();
                $eletion_data = $xdata;
                foreach ($xdata as $key => $value) {
                    echo '<option value='.$value['election_id'].'>'.$value['title'].' '.$value['SY'].'</option>';
                   
                }
             
            ?>
        </select>
    </div>
    <div class="db-card-collection" style=" margin-top:1rem; box-shadow: 0px 0px 15px -13px #000000;<?php echo $hidden ?>"  >
        <div class="db-card" style="background-color: #FF4d4d;">
            <div class="card-number">
                <h1 id="no_voter_voted">15</h1>
                <h5>Number of Voters Voted</h5>
            </div>
            <div class="card-image">
                <span class="material-icons md-max" style="color:#990000;">drive_file_rename_outline</span>
            </div>
        </div>
        <div class="db-card" style="background-color: #00b33c;">
            <div class="card-number">
                <h1 id="no_candidate">15</h1>
                <!-- <br> -->
                <h5>Number of Candidates</h5>
            </div>
            <div class="card-image">
                <span class="material-icons md-max" style="color:#006611;">account_circle</span>
            </div>
        </div>
        <div class="db-card" style="background-color: #FF9933;">
            <div class="card-number">
                <h1 id="no_voter">15</h1>
                <!-- <br> -->
                <h5>Number of Participants</h5>
            </div>
            <div class="card-image">
                <span class="material-icons md-max" style="color:#990000;">people_outline</span>
            </div>
        </div>
        <div class="db-card" style="background-color: #3377ff;">
            <div class="card-number">
                <h1 id="no_position">15</h1>
                <!-- <br> -->
                <h5>Number of<br>Positions</h5>
            </div>
            <div class="card-image">
                <span class="material-icons md-max" style="color:#0044cc;">supervised_user_circle</span>
            </div>
        </div>
    </div>
    <br>
    <div class="statistics-container" style=" background-color:white; padding: 1rem; box-shadow: 0px 0px 15px -13px #000000;">
        <h3>Statistics and Poll Results</h3>
        <div class="">
            <div class="stats" style="display:flex;overflow:hidden; padding: 1rem;">

            </div>
            <div class="election_info" style="padding: 2rem;display:flex;">
                <div class="info_date_card" style="padding: 1rem ;box-shadow: 0px 0px 15px -13px #000000;">
                    <h1>SSC sample</h1>
                    <p>School Year : 2020 - 2021</p>
                    <p>Start Date : </p>
                    <p>End Date : </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let stat_container = document.querySelector('.stats')
    let election_info_container = document.querySelector('.election_info')


    async function get_statistics(){
        let election_id = document.querySelector('#election_id').value;
        await fetch("back-end/clsDashboard.php", {
            method: 'POST',
            headers  : {
                'Content-type' : 'application/x-www-form-urlencoded'
            },
            body : `election_id=${election_id}&action=get_statistics`
        })
        .then(res => res.json())
        .then(res => {
            if(res[0] != '' && res[1] != '1'){
                let elements = res[0].split("|")
                elements.forEach(element => {
                    if(element != ''){
                        stat_container.append(htmlToElement(element))
                    }

                });
                eval(res[1])
            }
            else{
                stat_container.innerHTML = "<h1>No Statistics available</h1>"
            }
           
          
        })
    }    
    async function getcount_cards(){
        let election_id = document.querySelector('#election_id').value;
        let canididate_no = document.querySelector('#no_candidate');
        let voter_no = document.querySelector('#no_voter');
        let voter_vote_no = document.querySelector('#no_voter_voted');
        let position_no = document.querySelector('#no_position');
        await fetch("back-end/clsDashboard.php", {
            method: 'POST',
            headers : {
                'Content-type' : 'application/x-www-form-urlencoded'
            },
            body :`election_id=${election_id}&action=get_count_cards`
        })
        .then((res) => res.json())
        .then(res => {
            canididate_no.innerHTML = res['candidate']
            voter_no.innerHTML = res['voters']
            voter_vote_no.innerHTML = res['voter_voted']
            position_no.innerHTML = res['position']
        })
        destroyChart();
        get_statistics();
        get_election_info();
    }

    async function get_election_info(){
        let election_id = document.querySelector('#election_id').value;
        await fetch ("back-end/clsDashboard.php", {
            method : 'POST',
            headers : {
                'Content-type' : 'application/x-www-form-urlencoded'
            },
            body : `election_id=${election_id}&action=get_election_info`
        })
        .then(res => res.json())
        .then(res => {
            
        })
    }

    function destroyChart(){
        while(stat_container.hasChildNodes()){
            stat_container.removeChild(stat_container.firstChild);
        }
    }

    getcount_cards();
    // get_statistics();

</script>


<?php
    require 'footer.php'

?>