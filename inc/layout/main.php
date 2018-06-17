<?php if ( count( get_included_files() ) == 1) die( '--access denied--' );
if(isset($_SESSION['usr'])) {
    if (isset($_POST['email'])) {
        echo "Vous êtes maintenant inscrit
            <br>Vous pouvez vous connecter";
    } else {
        $currentUser = dbQuery("select firstname from sponsports.user where iduser =" . $_SESSION['usr'])[0]['firstname'];
        require_once 'startSearch.php';
    }
}else {
    require_once 'intro.php';
    require_once 'services.php';
}
