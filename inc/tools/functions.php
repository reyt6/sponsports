<?php if ( count( get_included_files() ) == 1) die( '--access denied--' );
function register($usrInfo){
    $usr = $usrInfo['username'];
    $pswd = password_hash($usrInfo['password'], 1);
    $email = $usrInfo['email'];
    $firstName = $usrInfo['firstname'];
    $lastName = $usrInfo['lastname'];
    $date = time();
    $user = loadTable("user");
    $count = 0;
    for ($i = 0; $i < sizeof($user); $i++) {
        if ($user[$i]["username"] == $usr || $user[$i]["email"] == $email) {
            $count++;
        }
    }
    if($count==0){
        dbQuery("INSERT INTO sponsports.user (username, password, email,firstname,lastname,dateofregistery) VALUES ('$usr','$pswd','$email','$firstName','$lastName','$date');");
        return true;
    }
    return false;
}

function insertIntoTable($table,$userInfo,$fk=null){
    $data = array();
    foreach ($userInfo as $value){
        array_push($data,$value);
    }
    if(isset($fk['fieldname'])){
        $sql="INSERT INTO sponsports.".$table." (".$fk['fieldname'].",".implode(" ,",array_keys($userInfo[0])).") values ('".$fk['value']."','".implode("' ,'",$data[0])."')";
    }
    else{
        $sql="INSERT INTO sponsports.".$table." (".implode(" ,",array_keys($userInfo[0])).") values ('".implode("' ,'",$data[0])."')";
    }
    dbQuery($sql);
}

function createPartner($partnerInfo){
    $dbTableAddress = loadTable('address');
    $sql = "SELECT idaddress, partnername FROM sponsports.partner JOIN sponsports.address USING (idaddress);";
    $dbTablePartner = dbQuery($sql);
    $housenumber = $partnerInfo['housenumber'];
    $boxnumber = $partnerInfo['boxnumber'];
    $street = $partnerInfo['street'];
    $zipcode = $partnerInfo['zipcode'];
    $count = 0;
    for ($i=0;$i<sizeof($dbTableAddress);$i++) {
        if (
            $dbTableAddress[$i]['idcity'] == $zipcode &&
            $dbTableAddress[$i]['street'] == $street &&
            $dbTableAddress[$i]['housenumber'] == $housenumber &&
            $dbTableAddress[$i]['boxnumber'] == $boxnumber
        ) $count++;
    }
    if($count == 0) {
        $data = array();
        array_push($data, ['idcity' => $zipcode, 'namecity' => $partnerInfo['city']]);
        $fk = ['fieldname' => 'countrycode', 'value' => $partnerInfo['country']];
        insertIntoTable('city', $data, $fk);
        $data = array();
        array_push($data,
            [
                'housenumber' => $housenumber, 'boxnumber' => $boxnumber,
                'street' => $street, 'phone' => $partnerInfo['phone']
            ]);
        $fk = ['fieldname' => 'idcity', 'value' => $zipcode];
        insertIntoTable('address', $data, $fk);
    }
    $count = 0;
    for ($i=0;$i<sizeof($dbTablePartner);$i++) {
        for ($j=0;$j<sizeof($dbTableAddress);$j++){
            if(
                $dbTableAddress[$j]['idcity'] == $zipcode &&
                $dbTableAddress[$j]['street'] == $street &&
                $dbTableAddress[$j]['housenumber'] == $housenumber &&
                $dbTableAddress[$j]['boxnumber'] == $boxnumber &&
                $dbTablePartner[$i]['partnername'] == $partnerInfo['partnername'] &&
                $dbTablePartner[$i]["idaddress"] == $dbTableAddress[$j]["idaddress"]
            )$count++;}
    }
    if($count == 0) {
        $data = array();
        array_push($data,
            [
                'partnername' => $partnerInfo['partnername'], 'issponsor' => $partnerInfo['issponsor'],
                'activity' => $partnerInfo['activity'], 'description' => $partnerInfo['description']
            ]);
        $sql = "SELECT idaddress AS id 
                FROM sponsports.address 
                WHERE street = '$street' AND boxnumber = '$boxnumber' AND housenumber = '$housenumber' AND  idcity = '$zipcode'";
        $fk = [
            'fieldname' => 'idaddress',
            'value' => dbQuery($sql)[0]['id']];
        insertIntoTable('partner', $data, $fk);
        $data = array();
        $sql = 'select max(idpartner) as id from sponsports.partner';
        array_push($data,
            [
                'idpartner' => dbQuery($sql)[0]['id'],
                'iduser' => $_SESSION['usr']
            ]);
        insertIntoTable('peopleincharge', $data);
        return true;
    }
    return false;
}
function login($usr,$pswd){
    $dbDataPersonne = loadTable("user");
    for ($i = 0; $i < sizeof($dbDataPersonne); $i++) {
        $dbUser = $dbDataPersonne[$i]["username"];
        $dbEmail = $dbDataPersonne[$i]["email"];
        if (($usr == $dbUser || $usr == $dbEmail)) {
            if(password_verify($pswd,$dbDataPersonne[$i]["password"])){
                return array(1=> $dbDataPersonne[$i]['iduser'],0=>true);
            }
        }
    }
    return array(0=>false);
}

function logoff(){
    $_SESSION = [];
    session_destroy();
}


function getAllAddresses($isSponsor = null){
    if(isset($isSponsor)){
        $where = " WHERE issponsor=$isSponsor;";
    }
    else $where = "";
    $sql = "SELECT iduser,idpartner as id,partnername, street, housenumber,boxnumber,namecity as city, idcity as zipcode, country FROM sponsports.user
	JOIN sponsports.peopleincharge USING (iduser)
    JOIN sponsports.partner USING (idpartner)
	JOIN sponsports.address USING (idaddress)
	JOIN sponsports.city USING (idcity)
	JOIN sponsports.country USING (countrycode)
	$where";
    $res = dbQuery($sql);
    if(isset($_SESSION['usr']))array_push($res,$_SESSION['usr']);
    return $res;
}
