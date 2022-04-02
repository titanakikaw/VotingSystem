<?php
    require './clsConnection.php';
    require './includeFunctions.php';
    header( "Content-type: application/json" );

    if($_POST['action'] == 'get_count_cards'){
        $election_id = $_POST['election_id'];
        $xparams['voter_voted'] = '';
        
        $query = "SELECT count(*) as count from ballot where election_id =?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$election_id]);
        $data = $stmt->fetch();
        $xparams['voter_voted'] = $data[0];
        $xparams['position'] = standard_count('position',$conn);
        $xparams['candidate'] = count_candidate($conn, $election_id);
        $xparams['voters'] = standard_count('voter', $conn);

        echo json_encode($xparams);

    }


    if($_POST['action'] == 'get_statistics'){
        $election_id = $_POST['election_id'];
        $new_element = '';
        $new_script = '';
        $query_one = "SELECT position.position_id ,position from candidate INNER JOIN candidate_request as cdr on candidate.request_id = cdr.request_id INNER JOIN election INNER JOIN position on cdr.position_id = position.position_id where cdr.election_id = ? group by position";
        $xstmt_one = $conn->prepare($query_one);
        $xstmt_one->execute([$election_id]);
        $xdata_one = $xstmt_one->fetchAll();
        foreach ($xdata_one as $key => $value) {
            $position_id = $value['position_id'];
            $new_element .= "|<div style='margin : 0px 1rem ; width: 43%;padding: 1rem; box-shadow: 0px 0px 15px -13px #000000;'><canvas id='myChart_".$value['position']."'></canvas></div>";
            $names = '';
            $votes = '';
            $color = '';
            $query_two = "SELECT * from candidate INNER JOIN candidate_request as cdr on candidate.request_id = cdr.request_id
            INNER JOIN voter on cdr.student_id= voter.student_id where cdr.position_id = ? AND cdr.election_id =?";
            $xstmt_two = $conn->prepare($query_two);
            $xstmt_two->execute([ $position_id, $election_id]);
            $xdata_two = $xstmt_two->fetchAll();
            foreach ($xdata_two as $key => $value_two) {
                $candidate_id = $value_two['candidate_id'];
                $fullname = '`'.$value_two['lname'].' '.$value_two['fname'].'`';
                $query_three = "SELECT count(*) from voter_vote INNER JOIN ballot on voter_vote.ballot_id = ballot.ballot_id where candidate_id =? and election_id =?";
                $xstmt_three = $conn->prepare($query_three);
                $xstmt_three->execute([ $candidate_id, $election_id ]);
                $xdata_three = $xstmt_three->fetch();
                if($names != ''){
                    $names .= ',';
                }
                if($votes != ''){
                    $votes .=',';
                }
                if($color != ''){
                    $color .= ',';
                }
                $names .= "'".$fullname."'";
                $votes .= $xdata_three[0];

                $new_color = rand_color();
                $color .= "'".$new_color."'";
            }
            // data: [".$votes."],
            $new_script .= "
                const ctx".$value['position']." = document.getElementById('myChart_".$value['position']."').getContext('2d');
                const myChart".$value['position']." = new Chart(ctx".$value['position'].", {
                    type: 'bar',
                    data: {
                        labels: [".$names."],
                        datasets: [{
                            label: [".$names."],
                            data: [".$votes."],
                            backgroundColor : [".$color."],
                            borderColor: [".$color."],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid : { display : false},
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                beginAtZero: true,
                                grid : { display : false},
                                

                            },
                           
                        },
                        plugins : {
                            legend : {
                                display: false
                            },
                            title: {
                                display: true,
                                text: '".$value['position']."'
                            }
                        }
                      },
                });
            ";
            
        }
        
        $response_data = [$new_element, $new_script];
        // var_dump( $response_data );
        echo json_encode($response_data);
    }

    if($_POST['action'] == 'get_election_info'){
        $xparams['election_id'] = $_POST['election_id'];
        $xdata = readyQueryGetItem($xparams, 'election', $conn);
        echo json_encode($xdata);
    }
    
    if($_POST['action'] == 'daily_votes'){
        $election_id = $_POST['election_id'];
        $xparams['dates'] = [];
        $xparams['votes'] = [];
        $query = "SELECT count(date) as votes, `date` from ballot where election_id =? group by `date`  order by  `date` DESC ";
        $stmt = $conn->prepare($query);
        $stmt->execute([$election_id]);
        $xdata = $stmt->fetchAll();
        foreach ($xdata as $key => $value) {
            array_push($xparams['dates'], $value['date']);
            array_push($xparams['votes'], $value['votes']);
        }
        echo json_encode($xparams);
    }


    function rand_color() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
    function count_candidate($conn, $election_id){
        $query = "SELECT count(*) as count from candidate INNER JOIN candidate_request as cdr on candidate.request_id = cdr.request_id INNER JOIN election on cdr.election_id = election.election_id where election.election_id =?";
        // var_dump($query, $election_id);
        $stmt = $conn->prepare($query);
        $stmt->execute([$election_id]);
        $data = $stmt->fetch();
        return $data[0];
    }
    function standard_count($table,$conn){
        $query = "SELECT count(*) from $table";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data[0];
    }
?>