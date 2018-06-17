<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
<header class="masthead">
    <div class="container">
        <div class="intro-text text-center">
            <form method="post" action="authentification" id="form" onsubmit="preventFormSubmit(this)">
                <label class="col-4">Nom d'utilisateur : </label><input type="text" name="username" required>
                <br><label class="col-4">Mot de passe : </label><input type="password" name="password" required>
                <br><input class="btn btn-primary btn" type="submit" value="connexion" name="login">
            </form>
        <div id="error"></div>
        </div>
    </div>
</header>