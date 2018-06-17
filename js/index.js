var options = [];
var id = 0;
var intervalId;
var msg = [];
var move;
var addresses;
var markers = []; var infoWindows = [];
var loadedMap, lat, lon;
var range = 0;
var searchRange;
var MyMap = {
    myMap: function myMap() {
        if (navigator.geolocation) {
            var map = $('#map')[0];
            var center = new google.maps.LatLng(lat, lon);
            var options = {
                center: center,
                zoom: 7,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            loadedMap = new google.maps.Map(map, options);
            searchRange = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: loadedMap});
        } else {
            console.log('Le navigateur ne supporte pas la géolocalisation');
        }


    }
};

$(document).ready(function () {
    var navBar = $('#navbarResponsive');
    appelAjax('checkIfLogged');
    $('#createPartnership').hide();
    $('#message').hide();
    $(document).scroll(function () {
       for(var i=0;i<$('section').length;i++){
           var id = '#'+$('section')[i].id;
           var e = $(id);
           if(($(document).scrollTop()+$('#mainNav').height())>= e.offset().top){
               navBar.find('a').removeClass('selected');
               navBar.find('a[href="'+id+'"]').addClass('selected');
           }
       }
    });
    navBar.find('a[href*="html"]').click(function (){
        event.preventDefault();
        clearInterval(intervalId);
        navBar.find('a').removeClass('selected');
        $(this).addClass('selected');
        $('#mainNav button').click();
        appelAjax(this);
        $('html, body').animate({scrollTop:$('html, body').offset().top},100);
        navBar.find('a[href^="#"]').hide();
    });
    $('a[href^="#"]').click(function (){
        event.preventDefault();
        var elem = this;
       setTimeout(function () {
            scrollToElem(elem);
        },300);
    });
    navBar.find('a[href^="#"]').click(function () {
        navBar.find('a').removeClass('selected');
        $(this).addClass('selected');
        if($(window).width()<1000)$('#mainNav button').click();

    });
    navBar.find('a:first').addClass('selected');
    if($('#recherche').length>0){
        appelAjax("loadCountries");
        $('#range').hide();
        getCoordinates("belgium",0);
        appelAjax('allPartners');
        $("input[name='checkSponsor']").click(function(){
            checkIfChecked()
        });
        $('input[name="place"]').change(function(){
            var val = $('select[name="country"]').val();
            getCoordinates(this.value + " " +$('select[name="country"] option[value="'+val+'"]').html(),2);
            $("#range").show();
            range = $('#regionRange').val() * 1000;

        });
        $('#useGPS').click(function () {
            navigator.geolocation.getCurrentPosition(function (p) {
                    lat = p.coords.latitude;
                    lon = p.coords.longitude;
                    var center = new google.maps.LatLng(lat, lon);
                    loadedMap.setCenter(center);
                    loadedMap.setZoom(10);
                    updateRange();
                    $("#localiteError").html("").hide();
                    $("#range").show();
                },function(){
                    $("#localiteError").html("Afin d'utiliser cette option, le site a besoin de votre autorisation d'accès<br> à votre localisation.").fadeIn(500);
                }
            );
        });
        $('#regionRange').change(function () {
            updateRange();
        });
    }
});

function postData(a,data) {
    var tab = [];
    if($('#'+data.id)[0]) {
        data = $('#' + data.id)[0];
        for (var i = 0; i < data.length; i++) {
            tab.push(data[i]);
        }
    }else {
        tab = {};
        tab['data'] = data;
    }
    var request = a.split('/')[a.split('/').length-1];
    $.post("/index.php?rq=" + request,tab, gereRetour);

}

function appelAjax(a) {
    if($(a).attr('href')){var request = $(a).attr('href').split('.html')[0];}
    else {
        request = a;
    }
    $.post("/index.php?rq=" + request,{}, gereRetour);
}
function testeJson(json) {
    var parsed;
    try {
        parsed = JSON.parse(json);
    }catch (e){
        parsed = {'jsonError' : {'error': e, 'json': json}};
    }
    return parsed;
}

function gereRetour(retour) {
    retour = testeJson(retour);
    for( action in retour){
        switch (action){
            case 'message' :
                if(!retour[action]){
                    $("#introLink").hide();
                    $("#rechercheLink").hide();
                    $("#servicesLink").hide();
                    $('html, body').animate({scrollTop:$('html, body').offset().top},100);
                    appelAjax('login');
                    }else {
                    var contactName = retour[action]['messages'];
                    var contactList = "";
                    $("#content").hide().html(retour[action]['template']).fadeIn(1000);
                    for (var i = 0; i < contactName.length; i++) {
                        contactList += "<li class='nav-link' onclick=getMessages(" + contactName[i]['id'] + ")>"
                            + contactName[i]['to'] + "</li>";
                    }
                    $("#sendMessage").hide().submit(function (e) {
                        event.preventDefault();
                        var message = $("#sendMessage").find("textarea")[0].value;
                        if(message.length>0)postData(e.target.action,{"receiver":id,"idchat":msg[0]['idchat'],"msg":message});
                        move=false;
                        $("#sendMessage").find("textarea")[0].value = "";
                    });
                    if (contactName.length == 0) {
                        $('#contactName').html("Vous n'avez pas encore de contacts");
                    }
                    $("#contacts ul").html(contactList);
                    $("#contacts li").click(function () {
                        $("#contacts li").removeClass('selected');
                        $(this).addClass('selected');
                    });
                }
                break;
            case 'displayMessages' :
                msg=retour[action]['messages'];
                var name = msg[0]['firstname'] + " " + msg[0]['lastname'];
                var conversation = "";
                for(var i = 1; i<msg.length;i++) {
                    if (msg[i]['iduser'] != msg[i]['sender']) {
                        conversation += "<div class='receiver col-12'>"
                            //+ "<img src='/SponSports/img/team/3.jpg' alt='Avatar' class='right' style='width:100%;'>"
                            + "<p id='receiver'>"+msg[i]['message']+"</p>"
                            + "<span class='time-left' id='timeSent'>"+msg[i]['timesent']+"</span>"
                            + "</div>"
                            +"<hr>";
                    }
                    if (msg[i]['iduser'] == msg[i]['sender']){
                        conversation += "<div class='col-12'>"
                            //+ "<img src='/SponSports/img/team/2.jpg' alt='Avatar' style='width:100%;'>"
                            + "<p id='sender'>"+msg[i]['message']+"</p>"
                            + "<span class='time-right' id='timeReceived'>"+msg[i]['timesent']+"</span>"
                            + "</div>"
                            + "<hr>";

                    }
                }
                $("#contactName").html(name);
                $("#conversation").html(conversation);
                break;
            case 'createCompany' :
                $('#partnerRegistration #error').html(retour[action]);
                break;
            case 'registration' :
                $('#inscription #error').html(retour[action]);
                break;
            case 'login' :
                if(retour[action][0])
                    location.reload();
                    if(retour[action]['usr'] > 0){
                    $('#createPartnership').show();
                    $('#message').show();
                    $("#introLink").hide();
                    $("#rechercheLink").hide();
                    $("#servicesLink").hide();
                    $('#profile').html("<a class='nav-link' href='profile.html'>Profil</a>")
                                    .find('a').click(function () {
                        event.preventDefault();
                        $('#navbarResponsive a').removeClass('selected');
                        $(this).addClass('selected');
                        appelAjax(this);
                    });
                    $('#login').html("<a class='nav-link' href='logoff' onclick=event.preventDefault(),appelAjax('logoff'),location.reload();>Déconnexion</a>");
                }
                if(retour[action].length > 0){
                    $('#error').html("Nom d'utilisateur ou mot de passe invalide")
                }
                break;
            case 'loadCountries' :
                options = retour[action];
                for (var i=0; i<options.length;i++){
                    $('select[name="country"]').append("<option value="+options[i]['countrycode']+">"+options[i]['country']+"</option>");
                }
                $('select[name="country"] option[value=BE]')[0].selected = true;
                break;
            case "getAddresses" :
                var boxnumber ="";
                addresses = [];
                for (i=0; i<retour[action].length;i++) {
                    addresses = retour[action];
                    addresses[i]["add"] = retour[action][i]['housenumber'] + " " + retour[action][i]['street']
                        + " " + retour[action][i]['zipcode'] + " " + retour[action][i]['city']
                        + " " + retour[action][i]['country'];
                    if (retour[action][i]['boxnumber'] != "") {
                        boxnumber = "/" + retour[action][i]['boxnumber'];
                    }
                    addresses[i]["info"] =
                        "<div id='infoWindow'>"
                        + retour[action][i]['partnername']
                        + "<br>" + retour[action][i]['street'] + " " + retour[action][i]['housenumber'] + boxnumber
                        + "<br>" + retour[action][i]['zipcode'] + " " + retour[action][i]['city']
                        + "<br>" + retour[action][i]['country'];
                    if (addresses[addresses.length - 1] != addresses[i]['iduser']) {

                        addresses[i]["info"] += "<br><a class='messageLinks' href='message' onclick='contactPartner(this," + addresses[i]['iduser'] + ")'>Contacter</a>";
                    }
                    addresses[i]["info"]+="</div>";
                    getCoordinates(addresses[i]['add'],1,addresses[i]['info']);
                }
                clearMarkers(markers);
                markers = [];
                break;
            case  "loadProfile" :
                options = retour[action]["info"];
                $('#username').html(options[0]['username'] + "<span class='glyphicon glyphicon-trash'>");
                $('#lastname').html(options[0]['lastname']);
                $('#firstname').html(options[0]['firstname']);
                $('#email').html(options[0]['email']);
                if(options[0]['phone'] == "" || options[0]['phone'] == null){
                    $('#phone').html("<button onclick=showInputField('phone')>Ajouter un numéro de téléphone</button>");
                }
                else
                    $('#phone').html(options[0]['phone']);
                appelAjax('loadCompanies');
                appelAjax('loadPartnership');
                break;
            case 'loadCompanies' :
                var info = $('#compagnyList');
                options = retour[action]["info"];
                if(options.length==0){
                    info.html("Vous n'avez pas encore enregistré d'entreprise");
                }else {
                    for (i=0; i<options.length;i++){
                        info.find('select').append("<option value="+options[i]['idpartner']+">"+options[i]['partnername']+" "+options[i]['namecity']+","+options[i]['countrycode']+"</option>");
                    }
                    showInfo(info.find('select')[0])
                }
                break;
            case 'loadPartnership' :
                var partnership = retour[action];
                if(partnership.length == 0)
                    $('#partners').html("Vous n'avez pas encore de partenaires");
                else
                    $('#partners').html("<button>list</button>");
                break;
            case 'display' :
                $("#content").hide().html(retour[action]).fadeIn(1000);
                break;
            case 'error' :
                $("#content").hide();
               console.log(retour[action]);
                break;
            case 'jsonError' :
                $("#content").hide();
                console.log("<b>Error :</b><br>"+retour[action]["error"] + "<hr><b>Json : </b><br>"+retour[action]["json"]);
                break;
            default :
                console.log("action inconnue : " + action
                    + "\n" + retour[action]);
                    return;
        }
    }
}
function contactPartner(a,id) {
    event.preventDefault();
    $("#introLink").hide();
    $("#rechercheLink").hide();
    $("#servicesLink").hide();
    postData(a.href, id);
}
function getMessages(receiver) {
    var el = $("#conversation")[0];
    id = receiver;
    if( id != 0) {
        $("#sendMessage").show();
    }
    postData('getMessages',id);
    intervalId = setInterval(function () {
        postData('getMessages',id);
        $('#conversation').scroll(function () {
            move = true;
        });
        if(!move){
            el.scrollTop = el.scrollHeight;
        }
    },200);
}
function showInputField(name) {
    var id = $('#' + name);

    switch (name) {
        case 'phone' :
            id.html("<label>Numéro de téléphone</label><br></label><input type=number name=" + name + " >");
            break;
        case 'description' :
            id.html("<textarea name=" + name + " >");
        break;

        case 'newMdp' :
            id.html(
                "<label>Mot de passe : </label><br><input type=password name=password required>" +
                "<br><label>Confirmez votre mot de passe : </label><br><input type=password name=passwordCheck required><br>"
            );
            break;
        default :
            $('#' + name).html("<input type=text name=" + name + " >");
    }
}

function resetEdit() {
    $('#phone').html("<button onclick=showInputField('phone')>Ajouter un numéro de téléphone</button>");
    $('#description').html("<span id=description><button onclick=showInputField('description')>Ajouter une description</button></span>");
    $('#activity').html("<span id=activity><button onclick=showInputField('activity')>Ajouter une activité</button></span>");
    $('#newMdp').html("<button onclick=showInputField('newMdp')>Changer Mot de Passe</button>")
}
function showInfo(e) {
    var sponsor;
    var content;
    var boxnumber = "";
    for (var i=0; i<options.length;i++){
        if(options[i]['idpartner']== e.value)
            sponsor = options[i];
    }
    if(sponsor['boxnumber'] != ""){
        boxnumber = "/"+sponsor['boxnumber'];
    }
    content = "<h5>" + sponsor['partnername'] + "</h5>"
                + "<br><span>" +sponsor['housenumber'] +boxnumber+ " " + sponsor['street']
                    + "<br>" + sponsor['idcity']+ " " + sponsor['namecity']
                    + "<br>" + sponsor['country']
                +"</span>";
    if(sponsor['description']== "" ||sponsor['description']== null )
        content +="<br><span id=description><button onclick=showInputField('description')>Ajouter une description</button></span>";
    else
        content +="<br><span>"+sponsor['description']+"</span>";

    if(sponsor['activity']== "" ||sponsor['activity']== null )
        content += "<br><span id=activity><button onclick=showInputField('activity')>Ajouter une activité</button></span>";
    else
        content += "<br><span>" + sponsor['activity']+"</span>";

    $("#compagnyList div").hide().html(content).fadeIn(1000);

}
function clearMarkers(markers) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }

}
function setLatLon(location,info) {
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(location['lat'], location['lng']),
        map:loadedMap
    });
    var markerInfo = new google.maps.InfoWindow({
        content: info
    });
    marker.addListener('click', function () {
        for(var i = 0;i<infoWindows.length;i++){
            infoWindows[i].close(loadedMap,markers[i]);
        }
        markerInfo.open(loadedMap, marker);

    });
    infoWindows.push(markerInfo);
    markers.push(marker);
}
function getCoordinates(address,i,info) {
    var result = "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyALDpjfBI4-FBNwAVAp65Rxucfg4b_ichg";
    result = result.split(' ').join('+');
    $.get(result).done(function (j) {
        if(typeof j['results'][0] !== "undefined") {
            var coordinates = j['results'][0]['geometry']['location'];
            lat = coordinates['lat'];
            lon = coordinates['lng'];
            $("#localiteError").html("");
            switch (i) {
                case 0:
                    MyMap.myMap();
                    break;
                case 1:
                    setLatLon(coordinates, info);
                    break;
                case 2:
                    loadedMap.setCenter(coordinates);
                    loadedMap.setZoom(10);
                    range = $('#regionRange').val() * 1000;
                    $('#rangeValue').html($('#regionRange').val() + " km");
                    searchRange.setCenter(loadedMap.getCenter());
                    searchRange.setRadius(range);
                    break;
            }
        }else {
            $("#localiteError").hide().html("Le lieu que vous avez introduit est introuvable.").fadeIn(1000);
        }
    });

}
function preventFormSubmit(form) {
    var id = $('#' + form.id);
    var action = form.action;
    var count = 0;
    for (var i = 0; i < id.find('input').length - 1; i++) {
        if (id.find('input')[i].value == "" && id.find('input')[i].required) {
            count++;
        }
    }
    if ((id.find('input[name="passwordCheck"]').val() != undefined) &&
        (id.find('input[name="password"]').val() != id.find('input[name="passwordCheck"]').val())) {
        event.preventDefault();
        $('html, body').animate({scrollTop: $('error').offset().top}, 100);
        id.find('#error').hide().html('Les mots de passes ne sont pas identique').fadeIn(1000);
    } else if(!verifyPassword(id.find('input[name="password"]').val())){
        event.preventDefault();
    } else {
        event.preventDefault();
        postData(action,form);
    }
}
function scrollToElem(elem) {
     var splittedStr = elem.href.split('#');
     var href = splittedStr[splittedStr.length-1];
     $('html, body').animate({scrollTop:$("#"+href).offset().top},500);
}
function checkIfChecked() {
    if ($('input[name="checkSponsor"]')[1].checked == true) {
        appelAjax('sponsors');
    }
    if ($('input[name="checkSponsor"]')[0].checked == true) {
        appelAjax('sponsored');
    }
}
function updateRange() {
    range = $('#regionRange').val() * 1000;
    $('#rangeValue').hide().html($('#regionRange').val() + " km").fadeIn(1000);
    searchRange.setCenter(loadedMap.getCenter());
    searchRange.setRadius(range);
}

function verifyPassword(pswd) {
    var count = 0;
    $('#passwordRequirements').hide();
    if(typeof pswd !== "undefined") {
        $('#passwordRequirements').fadeIn(1000);
        if (pswd.match(/[a-z]/)) {
            $('#lowercase').fadeOut(500);
            count++;
        }
        else $('#lowercase').fadeIn(500);

        if (pswd.match(/[A-Z]/)) {
            $('#uppercase').fadeOut(500);
            count++;
        }
        else $('#uppercase').fadeIn(500);

        if (pswd.length >= 8) {
            $('#length').fadeOut(500);
            count++;
        }
        else  $('#length').fadeIn(500);

        if (pswd.match(/\d/)) {
            $('#number').fadeOut(500);
            count++;
        }
        else  $('#number').fadeIn(500);
        if (count == 4){
            $('#passwordRequirements').hide();
            return true;
        }

        return false;
    }
    return true;

}