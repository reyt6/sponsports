<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
    <section id="intro">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h4 class="section-heading text-center">
                    A la recherche de sponsors ?
                    <br><br>De personnes ou d'entreprises<br> à sponsoriser ?
                </h4>
                <h1 class="section-heading text-center">Vous êtes au bon endroit !</h1>
                <a class='btn btn-primary btn-xl text-uppercase js-scroll-trigger' href='#services'>En Savoir Plus</a>
            </div>
        </div>
    </div>
</section>

<?php
    require_once 'services.php';
?>