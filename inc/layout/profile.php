<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
<section id="profile">
    <div class="container">
        <div class="intro-text text-center">
            <form name="ediUserInfo" action="" method="post" onsubmit="preventFormSubmit(this)">
                <div class="intro-text row text-center">
                    <div class="col-lg-6">
                        <div class="card-body">
                            <h4 class="card-title">
                                Informations Personnelles
                            </h4>
                            <div id="personalInfo">
                                <span id="username"></span>
                                <br><span id="lastname"></span>
                                <br><span id="firstname"></span>
                                <br><span id="email"></span>
                                <br><span id="phone"></span>
                                <br><span id="newMdp"><button onclick="showInputField('newMdp')">Changer Mot de Passe</button></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-body">
                            <h4 class="card-title">
                                Liste de vos entreprises
                            </h4>
                            <div id="compagnyList">
                                <select onchange="showInfo(this)"></select>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body">
                            <h4 class="card-title">
                                Partenaires
                            </h4>
                            <div id="partners"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <input class="btn btn-primary btn" type="reset" onclick="resetEdit()" value="Annuler"><input class="btn btn-primary btn" type="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>appelAjax("loadProfile");</script>