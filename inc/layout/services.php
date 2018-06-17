<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
    <section id="services">
    <div class="container text-center">
        <div class="row">
            <div class="col-12">
                <h3 class="section-heading text-center navbar-brand"><span class="text-uppercase">Bienvenu sur </span><span class="brandTitle">SponSports</span></h3>
            </div>
            <div class="col-12">
                <p class="text-muted text-center">
                    Un site qui a pour but de faciliter votre recherche de partenariat
                </p>
            </div>
        </div>
        <?php
            if(!isset($currentUser)) {
            ?>
        <div id="servicesList" class="row col-12">
            <div class="row col-6">
                <div class='col-lg-6'>
                    <span class='fa-stack fa-3x'>
                        <i class='fa fa-circle fa-stack-2x text-primary'></i>
                        <i class='fa fa-home fa-stack-1x fa-inverse'></i>
                    </span>
                    <h4 class='service-heading'>Espace<br> Personnel</h4>
                    <p class='text-muted'>Planifiez vos projets et consultez vos messages</p>
                </div>
                <div class='col-lg-6'>
                    <span class='fa-stack fa-3x'>
                        <i class='fa fa-circle fa-stack-2x text-primary'></i>
                        <i class='fa fa-location-arrow fa-stack-1x fa-inverse'></i>
                    </span>
                    <h4 class='service-heading'>Localisation<br> des Partenaires</h4>
                    <p class='text-muted'>Repérez les partenaires potentiels dans vos environs</p>
                </div>
            </div>
            <div class="row col-6">
                <div class='col-lg-6'>
                    <span class='fa-stack fa-3x'>
                      <i class='fa fa-circle fa-stack-2x text-primary'></i>
                      <i class='fa fa-envelope-o fa-stack-1x fa-inverse'></i>
                    </span>
                    <h4 class='service-heading'>Messagerie<br> Instantanée</h4>
                    <p class='text-muted'>Communiquez rapidement</p>
                </div>
                <div class='col-lg-6'>
                    <span class='fa-stack fa-3x'>
                        <i class='fa fa-circle fa-stack-2x text-primary'></i>
                        <i class='fa fa-handshake-o fa-stack-1x fa-inverse'></i>
                    </span>
                    <h4 class='service-heading'>Partenariat<br> Rapide</h4>
                    <p class='text-muted'>Trouvez directement ce qui correspond à vos attentes </p>
                </div>
            </div>
        </div>
      <div class="col-lg-12 text-center">
        <a class="btn btn-primary btn-xl text-uppercase" href="#recherche" id="mapSearch">Commencer la recherche</a>
      </div>
    </div>
</section>
        <?php
            }
            require_once 'startSearch.php';
        ?>