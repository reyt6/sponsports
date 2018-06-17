<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
<nav id="contacts">
    <div>
        <h3 class="intro-heading">Contacts</h3>
        <ul class="navbar-nav"></ul>
    </div>
</nav>
<div id="chat">
    <div class="text-center">
        <h3 id="contactName">Sélectionnez un contact</h3>
        <hr>
    </div>
    <div id="conversation" class="col-12"></div>
    <div id="sendMessage" class="col-12">
        <form action='sendMessage' method='post'>
            <textarea class='col-12' name='message'></textarea>
            <br><input class='col-12 btn btn-primary btn' type='submit' value="Envoyer">
        </form>
    </div>
</div>