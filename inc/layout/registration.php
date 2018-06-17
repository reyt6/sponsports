<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
<section id="registration">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                Profitez de toutes les fonctionnalités que <span class="brandTitle">SponSports</span> vous offre.<br>
                <span class="text-uppercase">Inscrivez vous!</span>
            </div>
            <form id="inscription" method="post" action="userRegistration" onsubmit="preventFormSubmit(this)">
                <div class="intro-text row">
                    <div class="col-lg-12">
                        <fieldset>
                            <label class="col-4">Nom d'utilisateur : </label><input class="col-8" type="text" name="username" required>
                            <br><label class="col-4">Email : </label><input class="col-8" type="email" name="email" required><br>
                            <br><label class="col-4">Nom : </label><input class="col-8" type="text" name="lastname" required>
                            <br><label class="col-4">Prenom : </label><input class="col-8" type="text" name="firstname" required><br>
                            <br><label class="col-4">Mot de passe : </label><input class="col-8" type="password" name="password" required>
                            <div id="passwordRequirements">
                                <ul>
                                    <li id="lowercase" class="selected">au moins une lettre minuscule</li>
                                    <li id="uppercase" class="selected">au moins une lettre majuscule</li>
                                    <li id="number" class="selected">au moins un chiffre</li>
                                    <li id="length" class="selected">au moins 8 caractères</li>
                                </ul>
                            </div>
                            <br><label class="col-4">Confirmez votre mot de passe : </label><input class="col-8" type="password" name="passwordCheck" required><br>
                        </fieldset>
                        <br><input class="col-6 btn btn-primary btn" type="submit" value="S'inscrire">
                    </div>
                    <fieldset class="col-lg-12" id="error"></fieldset>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    $("input[name='password']").keyup(function () {
        verifyPassword(this.value);

        console.log(this.value);
    });
</script>