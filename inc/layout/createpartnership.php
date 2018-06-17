<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
<section id="addPartner">
    <div class="container">
        <form id="partnerRegistration" method="post" action="createCompagny" onsubmit="preventFormSubmit(this)">
            <div class="intro-text row">
                <div class="col-lg-9">
                    <fieldset>
                        <label class="col-3">Nom de votre compagnie: </label><input class="col-5" type="text" name="partnername" required>
                        <br><label class="col-3">Vos attentes : </label>
                        <select name="issponsor">
                            <option value="1">Sponsoriser</option>
                            <option value="0">Trouver un sponsor</option>
                        </select>
                        <br><label class="col-3">Secteur d'activité : </label><input class="col-4" type="text" name="activity"><br>
                        <br><label class="col-3">Adresse : </label><input class="col-6" type="text" name="street" required>
                        <br><label class="col-3">Numéro : </label><input class="col-1" type="text" name="housenumber" required>
                        <label class="col-2">Boite  : </label><input class="col-1" type="text" name="boxnumber">
                        <br><label class="col-3">Ville : </label><input class="col-4" type="text" name="city" required>
                        <br><label class="col-3">Code Postal : </label><input class="col-2" type="text" name="zipcode" required><br>
                        <br><label class="col-2">Pays : </label><select required name="country"></select>
                        <br><label class="col-4">Numéro de téléphone : </label><input class="col-6" type="tel" name="phone">
                        <br><label>Description : </label>
                        <br><textarea name="description"></textarea>
                        <br><input class="col-6 btn btn-primary btn" type="submit" value="S'inscrire">
                    </fieldset>
                </div>
                <br>
                <fieldset class="col-lg-3" id="error"></fieldset>
            </div>
        </form>
    </div>
</section>
<script>
    appelAjax("loadCountries");
</script>
