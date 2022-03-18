<?php
    require '../back-end/clsConnection.php';
    require './includeFunctions.php';
    header( "Content-type: application/json" );

    if($_POST['action'] == 'add_candidate'){
        $xparams['request_id'] = $_POST['req_id'];
        $xparams['status'] = 1;
        $validation = check_candidate($xparams, $conn);
        if(!$validation){
            $xdata = readyQueryAdd($xparams,'candidate', $conn);
            echo json_encode($xdata);
        }
        else{
            echo json_encode("Candidate already Exist's");
        }
      
    }
    if($_POST['action'] == "get_data_inner_list_candidates"){
        $xparams['data_request'] = "voter.fname,voter.lname,voter.mname,voter.course,voter.year,elec.SY,elec.title,pos.position";
        $xparams['inner_join'] = "  INNER JOIN candidate_request as cdr ON candidate.request_id = cdr.request_id";
        $xparams['inner_join'] .= " INNER JOIN voter ON cdr.student_id = voter.student_id";
        $xparams['inner_join'] .= " INNER JOIN election as elec ON cdr.election_id = elec.election_id";
        $xparams['inner_join'] .= " INNER JOIN position as pos ON cdr.position_id = pos.position_id";
        $xparams['table_alias'] = "candidate";
        $xdata = readyQueryDisplayList_Custom($xparams, $conn);
        echo json_encode($xdata);
        // $xparams['inner_join'] += " INNER JOIN election ON cdr.election_id = election.election_id";
    }

    if($_POST['action'] == 'get_data_inner_list'){
        
        $xparams['data_request'] = 'cq.request_id, voter.student_id, voter.fname,voter.lname, voter.course, voter.year,position.position, election.title, election.SY, cq.reason, cq.status';
        $xparams['inner_join'] = "INNER JOIN voter ON cq.student_id = voter.student_id INNER JOIN election on cq.election_id = election.election_id INNER JOIN position on cq.position_id = position.position_id where cq.election_id = '".$_POST['election_id']."'";
        $xparams['table_alias'] = "candidate_request as cq ";
        $xdata = readyQueryDisplayList_Custom($xparams, $conn);
        echo json_encode($xdata);
    }

    if($_POST['action'] == 'get_single_item'){
        $xparams['where'] = ' where request_id =?';
        $xparams['filter'] = $_POST['id'];
        $xparams['data_request'] = 'cq.request_id, voter.student_id, voter.fname,voter.lname, voter.course, voter.year,position.position, election.title, election.SY, cq.reason, cq.status';
        $xparams['inner_join'] = 'INNER JOIN voter ON cq.student_id = voter.student_id INNER JOIN election on cq.election_id = election.election_id INNER JOIN position on cq.position_id = position.position_id';
        $xparams['table_alias'] = "candidate_request as cq";
        $xdata=readyQueryGetItem_Custom($xparams,$conn);
        echo json_encode($xdata);
    }


    function readyQueryGetItem_Custom($xparams, $conn){
        $data_request = $xparams['data_request'];
        $inner_join = $xparams['inner_join'];
        $table = $xparams['table_alias'];
        $where = $xparams['where'];
        $filter =   $xparams['filter'];
        $query = "SELECT $data_request from $table  $inner_join $where";
        $stmt = $conn->prepare($query);
        $stmt->execute([$filter]);
        $xdata = $stmt->fetch();
        return $xdata;
    }  

    function readyQueryDisplayList_Custom($xparams, $conn){
        $data_request = $xparams['data_request'];
        $inner_join = $xparams['inner_join'];
        $table = $xparams['table_alias'];
        $query = "SELECT $data_request from $table  $inner_join";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $xdata = $stmt->fetchAll();
        return $xdata;
    }

    function check_candidate($xparams, $conn){
        $query = "SELECT count(*) from candidate where request_id = '".$xparams['request_id']."'";
        $xstmt = $conn->prepare($query);
        $xstmt->execute();
        $xdata = $xstmt->fetch();
        // var_dump($xdata);
        if($xdata[0] > 0){
            return true;
        }
        else{
            return false;
        }
    }
?>