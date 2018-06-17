<?php
if ( count( get_included_files() ) == 1) die( '--access denied--' );

function toSend($txt,$action){
    global $toSend;
    if(!isset($toSend[$action]))
        $toSend[$action] = $txt;
}
function gereRequete($rq)
{
    switch ($rq) {
        case "sendMessage" :
            $usr = $_SESSION['usr'];
            if(isset($_SESSION['idchat'])){
                $data = array();
                array_push($data, ['idchat' => $_SESSION['idchat'], 'sender' => $usr,
                    'receiver' => $_POST['data']['receiver'], 'message' => $_POST['data']['msg'], 'timesent' => time()]);
                insertIntoTable("livechatreply",$data);
            }
            break;
        case "getMessages" :
            $oldMsgLength = 0;
            $lengthChange = false;
            $usr = $_SESSION['usr'];
            if(isset($_SESSION['msgLength'])){
                $oldMsgLength = $_SESSION['msgLength'];
            }
            $sql = " 
                    SELECT idchat,firstname,lastname, iduser,sender, receiver, message,timesent 
                          FROM sponsports.livechat 
                                JOIN sponsports.livechatreply USING(idchat) 
                                JOIN sponsports.user ON (sender = iduser || receiver = iduser) 
                          WHERE ((sender = " . $usr . " or sender= " . $_POST['data'] . ") 
                                and (receiver = " . $usr . " or receiver = " . $_POST['data'] . ")) 
                                and iduser <> " . $usr . " 
                          ORDER BY timesent";
            $msg = dbQuery($sql);
            $_SESSION['msgLength'] = sizeof($msg);
            $_SESSION['idchat'] = $msg[0]['idchat'];
            if($oldMsgLength != $_SESSION['msgLength']){
                $lengthChange = true;
            }
            foreach (array_keys($msg) as $item){
                $msg[$item]['timesent'] = date("d/m/Y à H:i",$msg[$item]['timesent']);
            }
           toSend([
                "messages" => $msg,
                "lengthChange"=>$lengthChange
            ], 'displayMessages');
            break;
        case "message" :
            $message = array();
            $receiver = 0;
            if(isset($_SESSION['usr'])) {
                $usr = $_SESSION['usr'];
                if (isset($_POST['data'])) {
                    $oldtable = loadTable("livechatreply");
                    $count = 0;
                    $receiver = $_POST['data'];
                    foreach ($oldtable as $item) {
                        if (($item['sender'] == $receiver && $item['receiver']  == $usr
                            || ($item['sender'] == $usr && $item['receiver']  == $receiver)))
                            $count++;
                    }
                    if ($count == 0) {
                        $data = array();
                        $time = time();
                        array_push($data, ['timecreated' => $time]);
                        insertIntoTable("livechat", $data);
                        $data = array();
                        $idchat = dbQuery(
                            "SELECT idchat 
                        FROM sponsports.livechat 
                    WHERE timecreated = '$time'"
                        )[0]['idchat'];
                        array_push($data, [
                            'idchat' => $idchat, 'sender' => $usr, 'receiver' => $_POST['data'], 'message' => '', 'timesent' => $time
                        ]);
                        insertIntoTable("livechatreply", $data);
                    }
                }
                $sql = "
                          SELECT DISTINCT iduser,firstname,lastname 
                            FROM sponsports.livechat 
                              JOIN sponsports.livechatreply USING(idchat) 
                              JOIN sponsports.user ON (sender = iduser || receiver = iduser)
                          WHERE (sender = " . $usr . " or receiver = " . $usr . ") and iduser <> " . $usr . " 
                          Group BY iduser";
                $data = dbQuery($sql);
                foreach ($data as $item) {
                    $to = $item['firstname'] . " " . $item['lastname'];
                    $id = $item['iduser'];
                    array_push($message, ['to' => $to, 'id' => $id]);
                }
                toSend([
                    "template" => chargeTemplate("messages"),
                    "messages" => $message,
                    "receiver" => $receiver
                ], 'message');
            } else {
                toSend(false, 'message');
            }
            break;
        case "userRegistration" :
            if (register($_POST))
                toSend("Vous êtes maintenant inscrit et pouvez vous connecter", 'registration');
            else
                toSend("Le nom d'utilisateur ou adresse email est déjà utilisé", 'registration');
            break;
        case "createCompagny" :
            if (createPartner($_POST))
                toSend("Votre entreprise a bien été enregistrée", "createCompany");
            else toSend("L'entreprise que vous essayez d'enregistrer existe déjà", "createCompany");
            break;
        case "sponsors" :
            toSend(getAllAddresses(1), "getAddresses");
            break;
        case "sponsored" :
            toSend(getAllAddresses(0), "getAddresses");
            break;
        case "allPartners": toSend(getAllAddresses(), "getAddresses");
            break;
        case "loadCountries" :
            toSend(loadTable('country'), "loadCountries");
            break;
        case "loadMap" :
            toSend(["mapSearch" => chargeTemplate('mapofpartners')], 'message');
            break;
        case "loadProfile" :
            toSend(
                [
                    'info' => dbQuery(
                        "SELECT * 
                          FROM sponsports.user
                            WHERE iduser=" . $_SESSION['usr']
                    ),
                ], 'loadProfile');
            break;
        case "loadCompanies" :
            $sql = "SELECT * FROM sponsports.user
                            JOIN sponsports.peopleincharge USING (iduser)
                            JOIN sponsports.partner USING (idpartner)
                            JOIN sponsports.address USING (idaddress)
                            JOIN sponsports.city USING (idcity)
                            JOIN sponsports.country USING (countrycode)
                        WHERE iduser=" . $_SESSION['usr'];

            toSend(
                [
                    'info' => dbQuery($sql)
                ], 'loadCompanies');
            break;
        case "loadPartnership" :
            toSend(loadTable('partnership'), 'loadPartnership');
            break;
        case "checkIfLogged" :
            toSend($_SESSION, 'login');
            break;
        case "authentification" :
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $usr = $_POST['username'];
                $pswd = $_POST['password'];
                toSend(login($usr, $pswd), 'login');
                if (login($usr, $pswd)[0])
                    $_SESSION['usr'] = login($usr, $pswd)[1];
            }
            break;
        case "logoff" :
            unset($currentUser);
            logoff();
            break;
        default :
            $file = chargeTemplate($rq);
            if ($file) {
                toSend($file, 'display');
            } else {
                toSend("template non trouvé: $rq   ", 'error');
            }
            break;
    }
}

function chargeTemplate($name = 'null'){
    $name = strtolower($name);
    $fileName = '/'.$name.'.php';
    $file = __DIR__ . '/../layout'.$fileName;
    if(file_exists($file))
        return implode("\n",file($file));
    return false;
}
