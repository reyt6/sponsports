<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
<section id="recherche">
    <div class="container">
        <div class="row col-12">
            <div class="row col-lg-4">
                <div class="row col-lg-12">
                    <fieldset>
                        <legend>Votre objectif</legend>
                        <div class="col-12">
                            <input  type="radio" name="checkSponsor">
                            <label>Chercher un sponsor</label>
                        </div>
                        <div class="col-12">
                            <input  type="radio" name="checkSponsor">
                            <label>Chercher à sponsoriser<label>
                        </div>
                    </fieldset>
                </div>
                <div class="row col-lg-12">
                    <fieldset>
                        <legend>Localité</legend>
                        <select name="country"></select>
                        <br>
                        <input type="text" name="place" placeholder="Code Postal/Ville/Region">
                        <br>
                        <a class="btn btn-primary btn-xl text-uppercase" id="useGPS">Localisation via GPS</a>
                        <br><span class="error" id="localiteError"></span>
                    </fieldset>
                </div>
                    <div class="row col-lg-12">
                    <fieldset id="range">
                        <legend>Rayon de recherche</legend>
                        <input type="range" min="1" max="100" value="10" id="regionRange">
                        <span id="rangeValue"></span>
                    </fieldset>
                    </div>
            </div>
            <div id="mapSection" class="col-lg-8" ><div id="map"></div></div>
        </div>
    </div>
</section>