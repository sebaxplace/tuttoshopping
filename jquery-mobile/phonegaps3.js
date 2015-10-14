var utente;

function onBodyLoad(){
    document.addEventListener("deviceready", onDeviceReady, false);
    initPushwoosh();
}

function onDeviceReady(){
   
    utente = window.localStorage.getItem("utente");
 
    if(utente == null){
        utente = null;
    }else{
       $.mobile.changePage("#index");
    }
}

document.addEventListener("resume", iniciar, false);

function iniciar(){
$('div.ui-loader').show(); 
$.mobile.changePage("#index");
    window.setTimeout(function(){
      
    $('.swiper-slide .underline').removeClass('underline');
    

//banner 1

var url3 = 'http://www.tuttoshopping.com/app/seba/banner-lista.php?jsoncallback=?';
    
   var cont1 = $('#banner1');
    $.ajax({
        url: url3 ,
        jsonp: 'jsoncallback',

        success: function(data, status){
            
            
            $.each(data, function(i,itemb){
                
                var bann1 = '<a href="#" id="count" rel="external"><img src="http://www.tuttoshopping.com/' + itemb.Immagine + '" width="100%" style="border-bottom:2px solid #FFFFFF;"/></a>';
                cont1.html(bann1);
                //counter click banner
                $(document).on('vclick', '#count', function(){
                    var counterclick1 = 'http://www.tuttoshopping.com/app/seba/agg-click-banner.php';
                    var postData = 'tabella=banner_offerte&id='+itemb.id;
                    $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: counterclick1,
                            data: postData
                            
                    });
                    window.open(itemb.Link, '_system');
                });

            });
        }
    });



//close banner
$(document).on('vclick', '.close-banner', function(){
    $('#cont1').html('');
});
       
       
  
          
            var url = 'http://www.tuttoshopping.com/app/seba/index.php';
            var outputs = $('#output');

            $.ajax({
             type: "GET",
              dataType: "json",
              url: url,
              beforeSend: function() {
                    // This callback function will trigger before data is sent
                    $('div.ui-loader').show(); // This will show ajax spinner
                },
                complete: function() {
                    // This callback function will trigger on data sent/received complete
                    $('div.ui-loader').hide(); // This will hide ajax spinner
                },
              success: function(data) {
                $('div.ui-loader').hide();
                outputs.html('');
                var count = 0;
                if (jQuery.isEmptyObject(data)){
                    $.mobile.loading('hide');
                    var noresult = '<div data-role="content" data-theme="c" class="top20">Non ci sono Risultati</div>';
                    outputs.append(noresult);
                }else{
                $.each(data, function(i,item){

                    count++;
                    if(count == 1){var espacio = '';}else{var espacio = 'top20';}

                    if(item.sospesa == 1){var suspendido = 'deactive';}else{var suspendido = '';}
                    if(item.esaurita == 1){var suspendido2 = 'deactive';}else{var suspendido2 = '';}
                    var landmarks = '<div data-role="content" data-theme="c" class="' + espacio + '" id="buscayborra"><div class="bgwhite bold p20 noshadow '+ suspendido + suspendido2 + '" data-role="content">' + item.nome + '</div><a href="#" id="links" data-uid="' + item.id + '"  data-role="button" rel="external"><div class="bgwhite '+ suspendido + suspendido2 + '"><img src="' + item.foto_1 + '" width="100%" /></div></a><div class="bgblack cien"><ul id="inline"><li class="orange noshadow"><img src="img/dett_01.jpg" /><strong><div class="price px30">€ ' + item.prezzo_al_pubblico + '</div></strong></li><li class="white noshadow  right '+ suspendido + suspendido2 + '"><div class="px15 top15 right20 tachado">€ ' + item.prezzo_di_listino + '</div></li></ul></div><div class="bgwhite bold noshadow '+ suspendido + suspendido2 + '"><div class="ui-grid-a"><div class="ui-block-a"><div class="top20 left20 px14">Già ' + item.stampati +' stampati</div></div><div class="ui-block-b"><a href="#" id="links" data-uid="' + item.id + '" class="ui-btn noright" data-role="button" rel="external">Dettaglio</a></div></div></div>';
                


                    outputs.append(landmarks);
                });
                
                $('html, body').animate({
                    scrollTop: 0
                }, 0);
               
                }
                        
              },
              error: function (request,error) {
                $("#output").html('<p>Conessione internet assente, riprova più tardi</p>');
                    navigator.notification.alert('Conessione internet assente, riprova più tardi');
                }
            });    
    
}, 300); 
}


function erroDB(err){
    mkLog("Error "+err.code);
    navigator.notification.alert();
}

function initPushwoosh()
{
    var pushNotification = cordova.require("com.pushwoosh.plugins.pushwoosh.PushNotification");
 
    //set push notifications handler
    document.addEventListener('push-notification', function(event) {
        var title = event.notification.title;
        var userData = event.notification.userdata;
                                 
        if(typeof(userData) != "undefined") {
            console.warn('user data: ' + JSON.stringify(userData));
        }
                                     
        //alert(title);
    });
 
    //initialize Pushwoosh with projectid: "GOOGLE_PROJECT_ID", pw_appid : "PUSHWOOSH_APP_ID". This will trigger all pending push notifications on start.
    pushNotification.onDeviceReady({ projectid: "399126531964", pw_appid : "3F6CC-60910" });
                                                
 
    //register for pushes
    pushNotification.registerDevice(
        function(status) {
            var pushToken = status;
            console.warn('push token: ' + pushToken);
        },
        function(status) {
            console.warn(JSON.stringify(['failed to register ', status]));
        }
    );
}

document.addEventListener('push-notification', function(event) {
    //event.notification is a JSON push notifications payload
    var title = event.notification.title;
 
    //example of obtaining custom data from push notification
    var userData = event.notification.userdata;
 
    console.warn('user data: ' + JSON.stringify(userData));
 
    //we might want to display an alert with push notifications title
    //alert(title);
});



   $(document).on('click', '#facebooka', function(){
       var fbLoginSuccess = function (userData) {
            JSON.stringify(userData);
            facebookConnectPlugin.getAccessToken(function(token) {
                //alert("Token: " + token);
               
                var faceurl = "https://graph.facebook.com/me?access_token="+token;
                $.ajax({
                    type: 'GET',
                    url: faceurl,
                    contentType: "application/json; charset=UTF-8",
                    dataType: "JSON",
                    success: function (data, status) {
                        JSON.stringify(data);
                        var email = data.email;
                        
                        var last = data.last_name;
                        var first = data.first_name;
                        //alert(email);
                        
                            var urlface = 'http://www.tuttoshopping.com/app/seba/facebook.php?email='+email+'&nome='+first+'&cognome='+last;
                            
                            
                            $.ajax({
                                url: urlface ,
                                jsonp: 'jsoncallback',

                                success: function(data, status){
                                    
                                }
                            });


                        window.localStorage.setItem('utente', email);
                  
                      $.mobile.changePage("#index");
                    }
                });



            }, function(err) {
                navigator.notification.alert("Could not get access token: " + err);
            });
        }

        facebookConnectPlugin.login(["email", "public_profile"],
            fbLoginSuccess,
            function (error) { navigator.notification.alert("tipo error:" + error); }
        );
        
    });

$(document).on('pageinit', '#login', function(){

           
    $(document).on('vclick', '#botonLogin', function(){

        // recolecta los valores que inserto el usuario
            var datosUsuario = $("#nombredeusuario").val();
            var datosPassword = $("#clave").val();
            
            archivoValidacion = "http://www.tuttoshopping.com/app/seba/login.php?jsoncallback=?";

            $.getJSON( archivoValidacion, { usuario:datosUsuario ,password:datosPassword})
            .done(function(respuestaServer) {
                
                //alert(respuestaServer.mensaje + "\nGenerado en: " + respuestaServer.hora + "\n" +respuestaServer.generador);
                
                if(respuestaServer.validacion == "ok"){
                    var usuario = $('#nombredeusuario').val();
                    window.localStorage.setItem('utente', usuario);
                  
                      $.mobile.changePage("#index");
                }else{
                  navigator.notification.alert('i dati non corrispondono');
                  /// ejecutar una conducta cuando la validacion falla
                }
          
            })
            return false;
    });

});

$(document).on('pageinit', '#registrati', function(){
var urlprov = 'http://www.tuttoshopping.com/app/seba/province_all.php';
    
   var selectorp = $('#provincias');
    $.ajax({
        url: urlprov ,
        jsonp: 'jsoncallback',

        success: function(data, status){
            
            
            $.each(data, function(i,itemp){
                
                var ciudadesp = '<option value="' + itemp.id + '">' + itemp.nome + '</option>';
                selectorp.append(ciudadesp);


            });
        }
    });
    $(document).on('vclick', '#botonRegistra', function(){
        // recolecta los valores que inserto el usuario
            
            var nombre = $("#nombre").val();
            var apellido = $("#apellido").val();
            var provincias = $("#provincias").val();
            var emails = $("#emails").val();
            var password1 = $("#password1").val();
            var password2 = $("#password2").val();
            var privacye = $("input[name='privacy']:checked").is(':checked');
            var privy = $("#privacy").val();
            var offertes = $("#offertes").val();
            var nuovis = $("#nuovis").val();
            
            if(nombre == "" ){
                 navigator.notification.alert("Scrivi il tuo nome");
               
                return false;
            }else

            if(apellido == "" ){
                 navigator.notification.alert("Scrivi il tuo cognome");
               
                return false;
            }else

            if(provincias == "" ){
                 navigator.notification.alert("Scegli una provincia");
              
                return false;
            }else

            if(emails == "" ){
                 navigator.notification.alert("Scrivi la tua email");
               
                return false;
            }else

            if(password1 == "" ){
                 navigator.notification.alert("Scrivi la tua password");
               
                return false;
            }else

            if(password2 == "" ){
                 navigator.notification.alert("Conferma la tua password");
                
                return false;
            }else

            if(password1 != password2 ){
                 navigator.notification.alert("Conferma della tua password errata");
              
                return false;
            }else

            if(privacye == false ){
                 navigator.notification.alert("Conferma la privacy policy");
              
                return false;
            }

           
            


            archivoValidacion2 = "http://www.tuttoshopping.com/app/seba/registrazione.php?jsoncallback=?"

            $.getJSON( archivoValidacion2, { nome:encodeURIComponent(nombre) ,cognome:encodeURIComponent(apellido), email:emails, provincia:provincias, password:encodeURIComponent(password1), confpassword:encodeURIComponent(password2), privacy:privy, newsletter:offertes, newslettercommerciale:nuovis})
            .done(function(respuestaServer2) {
                
                //alert(respuestaServer.mensaje + "\nGenerado en: " + respuestaServer.hora + "\n" +respuestaServer.generador);
                
                if(respuestaServer2.status == "ok"){
                    
                      $.mobile.changePage("#index");
                }else{
                  navigator.notification.alert('i dati non corrispondono');
                  /// ejecutar una conducta cuando la validacion falla
                }
          
            });
            return false;
    });



});

$(document).on('pageloadfailed', function(){
    navigator.notification.alert('Ha fallito il caricamento della pagina, riprova più tardi.');
});

  

$(document).on('pageinit', '#index', function(){

//geo
var swiper = new Swiper('.swiper-container', {
        scrollbar: '.swiper-scrollbar',
        scrollbarHide: true,
        slidesPerView: 'auto',
        centeredSlides: true,
        spaceBetween: 0,
        grabCursor: true,
        width: 150
    });




var watch = navigator.geolocation.watchPosition(onInfo, onError, {timeout: 30000});


function onInfo(info){
   var coordinadas = info.coords.latitude + '/'+info.coords.longitude;
   $('#opt').attr("value",coordinadas);
}

function onError(error){
    $('#coords').append('Codice errore: ' + error.code + 'messaggio:' + error.message);
}

 
//banner 1

var url3 = 'http://www.tuttoshopping.com/app/seba/banner-lista.php?jsoncallback=?';
    
   var cont1 = $('#banner1');
    $.ajax({
        url: url3 ,
        jsonp: 'jsoncallback',

        success: function(data, status){
            
            
            $.each(data, function(i,itemb){
                
                var bann1 = '<a href="#" id="count" rel="external"><img src="http://www.tuttoshopping.com/' + itemb.Immagine + '" width="100%" style="border-bottom:2px solid #FFFFFF;"/></a>';
                cont1.html(bann1);
                //counter click banner
                $(document).on('vclick', '#count', function(){
                    var counterclick1 = 'http://www.tuttoshopping.com/app/seba/agg-click-banner.php';
                    var postData = 'tabella=banner_offerte&id='+itemb.id;
                    $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: counterclick1,
                            data: postData
                            
                    });
                    window.open(itemb.Link, '_system');
                });

            });
        }
    });



//close banner
$(document).on('vclick', '.close-banner', function(){
    $('#cont1').html('');
});


 //ciudades

 var url2 = 'http://www.tuttoshopping.com/app/seba/province.php?jsoncallback=?';
    
   var selector = $('#selector');
    $.ajax({
        url: url2 ,
        jsonp: 'jsoncallback',

        success: function(data, status){
            
            
            $.each(data, function(i,itemss){
                
                var ciudades = '<option value="' + itemss.id + '">' + itemss.nome + '</option>';
                selector.append(ciudades);


            });
        }
    });





//articulos
    var url = 'http://www.tuttoshopping.com/app/seba/index.php?jsoncallback=?';
    var output = $('#output');
    var outputs = $('#outputs');
    window.setTimeout(function(){

  $('div.ui-loader').hide();
}, 300); 
    $.ajax({
        url: url ,
        jsonp: 'jsoncallback',
        beforeSend: function() {
                    // This callback function will trigger before data is sent
                    $('div.ui-loader').show(); // This will show ajax spinner
                },
        complete: function() {
                    // This callback function will trigger on data sent/received complete
                    $('div.ui-loader').hide(); // This will hide ajax spinner
                },
        success: function(data, status){
             var count = 0;
            $.each(data, function(i,item){
                count++;
                if(count == 1){var espacio = '';}else{var espacio = 'top20';}

                if(item.sospesa == 1){var suspendido = 'deactive';}else{var suspendido = '';}
                if(item.esaurita == 1){var suspendido2 = 'deactive';}else{var suspendido2 = '';}
                var landmark = '<div data-role="content" data-theme="c" class="' + espacio + ' nopadding"><div class="bgwhite bold p20 noshadow '+ suspendido + suspendido2 + '" data-role="content">' + item.nome + '</div><a href="#" id="links" data-uid="' + item.id + '"  data-role="button" rel="external"><div class="bgwhite '+ suspendido + suspendido2 + '"><img src="' + item.foto_1 + '" width="100%" /></div></a><div class="bgblack cien"><ul id="inline"><li class="orange noshadow"><img src="img/dett_01.jpg" /><strong><div class="price px30">€ ' + item.prezzo_al_pubblico + '</div></strong></li><li class="white noshadow  right '+ suspendido + suspendido2 + '"><div class="px15 top15 right20 tachado">€ ' + item.prezzo_di_listino + '</div></li></ul></div><div class="bgwhite bold noshadow '+ suspendido + suspendido2 + '"><div class="ui-grid-a"><div class="ui-block-a"><div class="top20 px14 left20">Già ' + item.stampati +' stampati</div></div><div class="ui-block-b"><a href="#" id="links" data-uid="' + item.id + '" class="ui-btn noright" data-role="button" rel="external">Dettaglio</a></div></div></div>';
                 
                output.append(landmark);


            });
            
           
            
        },
         error: function (request,error) {
                $("#output").html('<p>Conessione internet assente, riprova più tardi</p>');
                    navigator.notification.alert('Conessione internet assente, riprova più tardi');
                }

        
});




$('#selector').change(function() {
 
  var provincia = $('#selector').val();
  var categoria = $('.underline').attr("data-value");
  var coordinadas = $('#opt').val();
  if(provincia == 'latlong'){envio = coordinadas;}else{envio = provincia;}
  var url = 'http://www.tuttoshopping.com/app/seba/index.php?q='+categoria+'&p='+envio;
  var outputs = $('#output');
 
            $.ajax({
             type: "GET",
              dataType: "json",
              url: url,
              beforeSend: function() {
                    // This callback function will trigger before data is sent
                    $('div.ui-loader').show(); // This will show ajax spinner
                },
                complete: function() {
                    // This callback function will trigger on data sent/received complete
                    $('div.ui-loader').hide(); // This will hide ajax spinner
                },
              success: function(data) {
                
                outputs.html('');
                var count = 0;
                if (jQuery.isEmptyObject(data)){
                    $.mobile.loading('hide');
                    var noresult = '<div data-role="content" data-theme="c" class="top20">Non ci sono Risultati</div>';
                    outputs.append(noresult);
                }else{
                $.each(data, function(i,item){

                    count++;
                    if(count == 1){var espacio = '';}else{var espacio = 'top20';}

                    if(item.sospesa == 1){var suspendido = 'deactive';}else{var suspendido = '';}
                    if(item.esaurita == 1){var suspendido2 = 'deactive';}else{var suspendido2 = '';}
                    var landmarks = '<div data-role="content" data-theme="c" class="' + espacio + '"><div class="bgwhite bold p20 noshadow '+ suspendido + suspendido2 + '" data-role="content">' + item.nome + '</div><div class="bgwhite '+ suspendido + suspendido2 + '"><img src="' + item.foto_1 + '" width="100%" /></div><div class="bgblack cien"><ul id="inline"><li class="orange noshadow"><img src="img/dett_01.jpg" /><strong><div class="price px30">€ ' + item.prezzo_al_pubblico + '</div></strong></li><li class="white noshadow  right '+ suspendido + suspendido2 + '"><div class="px15 top20 right20 tachado">€ ' + item.prezzo_di_listino + '</div></li></ul></div><div class="bgwhite bold noshadow '+ suspendido + suspendido2 + '"><div class="ui-grid-a"><div class="ui-block-a"><div class="top20 left20">Già ' + item.stampati +' stampati</div></div><div class="ui-block-b"><a href="#" id="links" data-uid="' + item.id + '" class="ui-btn noright" data-role="button" rel="external">Dettaglio</a></div></div></div>';
                


                    outputs.append(landmarks);
                });
               
                }
                $('html, body').animate({
                    scrollTop: 0
                }, 0);
                            
              },
              error: function (request,error) {
                    navigator.notification.alert('Conessione internet assente, riprova più tardi');
                }
            });
});

$(document).on('click', '#city-search-btn', function(){
       
       $('div.ui-loader').show();
    var provincia = $('#selector').val();
    var coordinadas = $('#opt').val();
    if(provincia == 'latlong'){envio = coordinadas;}else{envio = provincia;}
    $('.swiper-slide .underline').removeClass('underline');
    $(this).addClass('underline');
    
        
        var categoria = $(this).attr("data-value");
        if(categoria.length > 0) {
          
            var url = 'http://www.tuttoshopping.com/app/seba/index.php?q='+categoria+'&p='+envio;
            var outputs = $('#output');

            $.ajax({
             type: "GET",
              dataType: "json",
              url: url,
              beforeSend: function() {
                    // This callback function will trigger before data is sent
                    $('div.ui-loader').show(); // This will show ajax spinner
                },
                complete: function() {
                    // This callback function will trigger on data sent/received complete
                    $('div.ui-loader').hide(); // This will hide ajax spinner
                },
              success: function(data) {
                $('div.ui-loader').hide();
                outputs.html('');
                var count = 0;
                if (jQuery.isEmptyObject(data)){
                    $.mobile.loading('hide');
                    var noresult = '<div data-role="content" data-theme="c" class="top20">Non ci sono Risultati</div>';
                    outputs.append(noresult);
                }else{
                $.each(data, function(i,item){

                    count++;
                    if(count == 1){var espacio = '';}else{var espacio = 'top20';}

                    if(item.sospesa == 1){var suspendido = 'deactive';}else{var suspendido = '';}
                    if(item.esaurita == 1){var suspendido2 = 'deactive';}else{var suspendido2 = '';}
                    var landmarks = '<div data-role="content" data-theme="c" class="' + espacio + '"><div class="bgwhite bold p20 noshadow '+ suspendido + suspendido2 + '" data-role="content">' + item.nome + '</div><a href="#" id="links" data-uid="' + item.id + '"  data-role="button" rel="external"><div class="bgwhite '+ suspendido + suspendido2 + '"><img src="' + item.foto_1 + '" width="100%" /></div></a><div class="bgblack cien"><ul id="inline"><li class="orange noshadow"><img src="img/dett_01.jpg" /><strong><div class="price px30">€ ' + item.prezzo_al_pubblico + '</div></strong></li><li class="white noshadow  right '+ suspendido + suspendido2 + '"><div class="px15 top15 right20 tachado">€ ' + item.prezzo_di_listino + '</div></li></ul></div><div class="bgwhite bold noshadow '+ suspendido + suspendido2 + '"><div class="ui-grid-a"><div class="ui-block-a"><div class="top20 left20 px14">Già ' + item.stampati +' stampati</div></div><div class="ui-block-b"><a href="#" id="links" data-uid="' + item.id + '" class="ui-btn noright" data-role="button" rel="external">Dettaglio</a></div></div></div>';
                


                    outputs.append(landmarks);
                });
                $('html, body').animate({
                    scrollTop: 0
                }, 0);
               
                }
                            
              },
              error: function (request,error) {
                    navigator.notification.alert('Conessione internet assente, riprova più tardi');
                }
            });       
        } else {
            navigator.notification.alert('Conessione internet assente, riprova più tardi');
        }       
    });        
});
 
function showIndicator(){
$.mobile.loading( 'show', {
    text: 'Loading...',
    textVisible: true,
    theme: 'b',
    html: ""
   });
}
 
function hideIndicator(){
$.mobile.loading( 'hide');
}


$(document).on('pagebeforeshow', '#detalle', function(data, status){

    $('#img').attr("src",'');
    $('#titulo').html('');
    $('#title').html('');
    $('#sociale').html('');
    $('#prezzo').html('');
    $('#listino').html('');
    $('#stampa').html('');
    $('#caratteristiche').html('');
    $('#specifico').html('');
    $('#scheda').html('');
    $('#via1').html('');
    $('#via2').html('');
    $('#via3').html('');
    $('#ciudad1').html('');
    $('#ciudad2').html('');
    $('#ciudad3').html('');
    $('#contacto1').html('');
    $('#contacto2').html('');
    $('#contacto3').html('');
    
    $('#contacto4').html('');
    $('#2contacto4').html('');
    $('#3contacto4').html('');

    $('#b_contacto4').html('');
    $('#b_2contacto4').html('');
    $('#b_3contacto4').html('');

    $('#c_contacto4').html('');
    $('#c_2contacto4').html('');
    $('#c_3contacto4').html('');

    $('#foto').attr("src",'');
    $('#webs').html('');
    $('#web_btn').html('');
    var sito = '';

});

$(document).on('pageshow', '#detalle', function(data, status){
 window.setTimeout(function(){
  $('div.ui-loader').hide();
}, 300); 


    $.getJSON('http://www.tuttoshopping.com/app/seba/offerta.php?idOfferta=' + ids, null, function(data) {
        
         $.each(data, function(p, post) {
            
                var str = post.nome;
                var res = str.substring(0, 20) + '...';
                var direccion = post.logo;
                var fineofferta = post.utilizzabile_entro;
                var sito = post.sito_web;
                $('#titulo').html(res);
                $('#title').html(post.nome);
                $('#sociale').html('<strong>'+post.nome_esercente+'</strong>');
                $('#img').attr("src",post.foto);
                $('#prezzo').html('€ '+post.prezzo_al_pubblico);
                $('#listino').html('€ '+post.prezzo_di_listino);
                $('#stampa').html(post.coupon_stampati);
                $('#caratteristiche').html(post.riepilogo);
                $('#specifico').html(post.descrizione);
                $('#scheda').html(post.scheda_tecnica);
                $('#contacto1').html(post.nome_esercente);
                
                $('#via1').html('&#8226; '+post.indirizzo1);
                $('#ciudad1').html('&nbsp; &nbsp;'+post.citta1);
                if(post.telefono != ''){$('#contacto4').html('Tel: <a href="tel:'+post.telefono2+'">'+post.telefono+'</a>');}
                if(post.telefono_2 != '' && post.telefono_2 != null){$('#2contacto4').html('Tel: <a href="tel:'+post.telefono2_2+'">'+post.telefono_2+'</a>');}
                if(post.telefono_3 != '' && post.telefono_3 != null){$('#3contacto4').html('Tel: <a href="tel:'+post.telefono2_3+'">'+post.telefono_3+'</a>');}
                
                if(post.indirizzo2 != ''){$('#via2').html('<br>&#8226; '+post.indirizzo2);}
                if(post.citta2 != ''){$('#ciudad2').html('&nbsp; &nbsp;'+post.citta2);}
                if(post.b_telefono != ''){$('#b_contacto4').html('Tel: <a href="tel:'+post.b_telefono2+'">'+post.b_telefono+'</a>');}
                if(post.b_telefono_2 != '' && post.b_telefono_2 != null){$('#b_2contacto4').html('Tel: <a href="tel:'+post.b_telefono2_2+'">'+post.b_telefono_2+'</a>');}
                if(post.b_telefono_3 != '' && post.b_telefono_3 != null){$('#b_3contacto4').html('Tel: <a href="tel:'+post.b_telefono2_3+'">'+post.b_telefono_3+'</a>');}
                
                if(post.indirizzo3 != ''){$('#via3').html('<br>&#8226; '+post.indirizzo3);}
                if(post.citta3 != ''){$('#ciudad3').html('&nbsp; &nbsp;'+post.citta3);}
                if(post.c_telefono != ''){$('#c_contacto4').html('Tel: <a href="tel:'+post.c_telefono2+'">'+post.c_telefono+'</a>');}
                if(post.c_telefono_2 != '' && post.c_telefono_2 != null){$('#c_2contacto4').html('Tel: <a href="tel:'+post.c_telefono2_2+'">'+post.c_telefono_2+'</a>');}
                if(post.c_telefono_3 != '' && post.c_telefono_3 != null){$('#c_3contacto4').html('Tel: <a href="tel:'+post.c_telefono2_3+'">'+post.c_telefono_3+'</a>');}
                
                if(post.sito_web != ''){$('#webs').html('<a onclick="window.open(this.href,\'_system\',\'location=no\');return false;" href="'+sito+'" id="web_btn">Sito Web</a>');}
                $('#foto').attr("src",post.logo);
                $('#share').attr("onclick", "window.plugins.socialsharing.share(null, null, null, 'http://www.tuttoshopping.com/offerte/"+post.id+"/')");
                $('#linksavecoupon').attr('data-uid', post.id);
               
                if(sito != ''){
                    $(document).on('vclick', '#web_btn', function(){
                        if ($(this).attr('target') === '_blank') {
                            window.open($(this).attr('href'),'_system','location=no');
                            e.preventDefault();
                        }
                    });
                }


                var sospesa = post.sospesa;
                var esaurita = post.esaurita;

                var latitud = post.latitudine1;
                var longitud = post.longitudine1;

                if(latitud != null){var latitudine = latitud;}else{var latitudine = '0.000000';}
                if(longitud != null){var longitudine = longitud;}else{var longitudine = '0.000000';}
                
                $('#lat').attr("value", latitudine);
                $('#long').attr("value", longitudine);
                
                $('#google').html('<a onclick="window.open(this.href,\'_system\',\'location=no\');return false;" class="ui-btn" href="http://maps.google.com/?q='+latitudine+','+longitudine+'" id="geos">Visualizza itinerario</a>');

                $(document).on('vclick', '#geos', function(){
                        if ($(this).attr('target') === '_blank') {
                            window.open($(this).attr('href'),'_system','location=no');
                            e.preventDefault();
                        }
                    });


                if(sospesa == 0 && esaurita == 0){
                    $('#abilita').html('<img src="img/icon18_check.png" class="inlineb"/><div class="  inlineb px15">L\'offerta è attiva</div>');
                }else{
                    $('#abilita').html('<img src="img/offerta-disattiva.png" class="inlineb"/><div class="inlineb px12">L\'offerta non è attiva</div>');
                }
                
               $('#my-timer').countdown(fineofferta, function(event) {
                   var totalHours = event.offset.totalDays * 24 + event.offset.hours;
                   $(this).html(event.strftime(totalHours + ' ore %M min %S sec'));
                 });
               setTimeout(function() {
               var myLatlng = new google.maps.LatLng(latitudine,longitudine);
                var mapOptions = {
                  zoom: 10,
                  center: myLatlng,
                  draggable: false
                }
                var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

                // To add the marker to the map, use the 'map' property
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map
                });
                }, 500);
               function handleSocialShare()
                {
                    $('#select-choice-share option:selected').each(function()
                    {
                        text = "Flash vs HTML5 Trendanalyse";
                        //url ="http://www.sebastianviereck.de/flash-html5-trendanalyse/#.ULTEkYb9n2A";

                        shareService = $(this).val();
                        switch (shareService) {
                            case "facebook":
                                shareFacebookLike(url);
                                break;
                            case "twitter":
                                shareTwitter(url, text);
                                break;
                            case "email":
                                shareEmail(url, text);
                                break;
                            default:

                        }
                    });
                }
                function shareFacebookLike(url)
                {
                    window.location="http://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url);
                }
                function shareTwitter(url, text)
                {
                    window.location = "https://twitter.com/intent/tweet?text=" + encodeURIComponent(text) + "&url=" + encodeURIComponent(url);
                }
                function shareEmail(subject, body)
                {
                    window.location = "mailto:&subject=" + subject + "&body=" + body;
                }



                
        });


     });
   
//banner 2

var url4 = 'http://www.tuttoshopping.com/app/seba/banner-coupon.php';
    
   var cont4 = $('#banner2');
    $.ajax({
        url: url4 ,
        jsonp: 'jsoncallback',

        success: function(data, status){
            
            
            $.each(data, function(is,itemb2){
                
                var bann2 = '<a href="' + itemb2.Link + '" id="count" rel="external"><img src="' + itemb2.Immagine_Banner_Laterale + '" width="100%" style="border-bottom:2px solid #FFFFFF;"/></a>';
                cont4.html(bann2);
                $(document).on('vclick', '#count', function(){
                    var counterclick1 = 'http://www.tuttoshopping.com/app/seba/agg-click-banner.php';
                    var postData2 = 'tabella=banner_offerte&id='+itemb2.id;
                    $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: counterclick1,
                            data: postData2
                            
                    });

                    window.open(itemb2.Link, '_system');    
                });

            });
        }
    }); 

 

//close banner2
$(document).on('vclick', '.close-banner2', function(){
    $('#cont2').html('');
});    


});


$(document).on('vclick', '#links', function(){
        ids  = $(this).attr('data-uid');
        setTimeout(function () {
            $.mobile.changePage("#detalle",{transition:"none"})
   
}, 300);
         //$.mobile.pageContainer.pagecontainer( "change", "#detalle", { role: "slide" } );
         $('div.ui-loader').show();

});

$(document).on('vclick', '#links2', function(){
        ids2  = $(this).attr('data-uid');
        idsopt  = $(this).attr('data-opt');
        $.mobile.changePage("#detallecoupon",{transition:"none"});
         //$.mobile.pageContainer.pagecontainer( "change", "#detalle", { role: "slide" } );
});



$(document).on('vclick', '#linksavecoupon', function(data, status){
       idsavecoupon  = $(this).attr('data-uid');
        $.mobile.changePage("#detallecouponsalva");
     
         //$.mobile.pageContainer.pagecontainer( "change", "#detalle", { role: "slide" } );
});

$(document).on('vclick', '#links5', function(data, status){
        idopcion  = $(this).attr('data-uid');
        ideofferta  = $(this).attr('data-off');
        $.mobile.changePage("#salvarcoupon");
     
         //$.mobile.pageContainer.pagecontainer( "change", "#detalle", { role: "slide" } );
});


$(document).on('pagebeforeshow', '#salvarcoupon', function(data, status){

var utenteop = window.localStorage.getItem("utente");
var validasaveop = 'http://www.tuttoshopping.com/app/seba/check_coupon.php?jsoncallback=?';
        

        $.getJSON( validasaveop, { email:utenteop } )
                .done(function(respuestaServer4) {
                    
                    //alert(respuestaServer.mensaje + "\nGenerado en: " + respuestaServer.hora + "\n" +respuestaServer.generador);
                    
                    if(respuestaServer4.status != "ok"){
                        $.mobile.changePage("#spiacente");
                    }
              
                });
                return false;

});

$(document).on('pageinit', '#salvarcoupon', function(data, status){


    $(document).on('vclick', '#salvar', function(data, status){
        var utente = window.localStorage.getItem("utente");
        var url_coupon5 = 'http://www.tuttoshopping.com/app/seba/salva-coupon.php?jsoncallback=?';
        var cupones6 = $('#cupones6');
        $.getJSON( url_coupon5, { email:utente, idOfferta:ideofferta, idOpzione:idopcion } )
                    
        $.mobile.changePage("#salvado");
    });            
 
});    

//CONTROL E VISTA SALVA COUPONS PRIMO PASSO
$(document).on('pagebeforeshow', '#detallecouponsalva', function(data, status){
//geo

 var url_coupon2a = 'http://www.tuttoshopping.com/app/seba/opzioni.php?idOfferta='+idsavecoupon;
                        var cupones2a = $('#cupones2a');
                        $('#cupones2a').html('');
                        $.ajax({
                            url: url_coupon2a ,
                            jsonp: 'jsoncallback',
                            beforeSend: function() {
                                        // This callback function will trigger before data is sent
                                        $('div.ui-loader').show(); // This will show ajax spinner
                                    },
                            complete: function() {
                                        // This callback function will trigger on data sent/received complete
                                        $('div.ui-loader').hide(); // This will hide ajax spinner
                                    },
                            success: function(data, status){
                                 
                                $.each(data, function(i,itevc){
                                   var contenidocoupons2a = '<li><a href="#" id="links5" data-uid="'+itevc.idOpzione+'" data-off="'+itevc.idOfferta+'"><h2>'+itevc.Titolo+'</h2><div class="ui-grid-a"><div class="ui-block-a"><div class="ui-bar nopadding"><p>Sconto: '+itevc.Sconto+'%</p></div></div><div class="ui-block-b"><div class="ui-bar nopadding dx orange"><h2>€ '+itevc.Prezzo+' </h2></div></div></div><div class="ui-grid-a"><div class="ui-block-a"><div class="ui-bar nopadding"></div></div><div class="ui-block-b"><div class="ui-bar nopadding dx"></div></div></div></a></li>';
                                   //var contenidocoupons = '<li>hola</li>';  
                                    cupones2a.append(contenidocoupons2a);


                                });
                            $('#cupones2a').listview('refresh');

                            },

                            error: function (request,error) {
                                        navigator.notification.alert('Conessione internet assente, riprova più tardi');

                                    }
                        });

});


//MY COUPONS
$(document).on('pagebeforeshow', '#mycoupons', function(){
//geo
var utente = window.localStorage.getItem("utente");
var url_coupon = 'http://www.tuttoshopping.com/app/seba/my_coupon.php?email='+utente;
var cupones = $('#cupones');

cupones.html('');
    $.ajax({
        url: url_coupon ,
        jsonp: 'jsoncallback',
        beforeSend: function() {
                    // This callback function will trigger before data is sent
                    $('div.ui-loader').show(); // This will show ajax spinner
                },
                complete: function() {
                    // This callback function will trigger on data sent/received complete
                    $('div.ui-loader').hide(); // This will hide ajax spinner
                },
        success: function(data, status){
             if(data != null){
                $.each(data, function(i,itemc){
                    var contenidocoupons = '<li><a href="#" id="links2" data-uid="'+itemc.idOfferta+'" data-opt="'+itemc.opzione+'"><strong>'+itemc.nome+'</strong><div class="ui-grid-a"><div class="ui-block-a"><div class="ui-bar nopadding"><p>Sconto: '+itemc.sconto+'%</p></div></div><div class="ui-grid-b"><div class="ui-bar nopadding dx orange"><h2>€ '+itemc.prezzo_al_pubblico+'</h2></div></div></div></div></a></li>'; 
                    cupones.append(contenidocoupons);


                });
                $('#cupones').listview('refresh');
            }else{
                $('div.ui-loader').hide();
                cupones.append('<li style="background:none;border:none;color:#000;text-shadow:none;">Non hai nessun coupon salvato.</li>');
                $('#cupones').listview('refresh');}
        },

        error: function (request,error) {
                    navigator.notification.alert('Conessione internet assente, riprova più tardi');
                }
    });

});


$(document).on('pagebeforeshow', '#detallecoupon', function(data, status){
var utente = window.localStorage.getItem("utente");

    $.getJSON('http://www.tuttoshopping.com/app/seba/coupon-salvato.php?email='+utente+'&idOfferta=' + ids2+'&idOpzione='+idsopt, null, function(data) {
        

         $.each(data, function(pc, postci) {
                
                
                $('#titulo2').html('<strong>'+postci.nome_azienda+'</strong>');
                $('#title2').html('<strong>'+postci.nome_azienda+'</strong>');
                $('#img2').attr("src",postci.immagine);
                $('#descoupon1').html('<strong>'+postci.nome_opzione+'</strong>');
                $('#sconto_coupon').html(postci.sconto+'% Sconto - Risparmi €'+postci.risparmio);
                $('#des_v_coupon').html(postci.descrizione_breve);
                $('#des_v_coupon2').html(postci.descrizione_lunga);
                $('#prezzo2').html(postci.codice);
                $('#prezzo1').html('€ '+postci.prezzo);
                $('#scad').html(postci.scadenza);
                $('#nomeaz').html('<strong>'+postci.nome_azienda+'</strong>');
                
                if(postci.indirizzo1 != '' && postci.indirizzo1 != null){$('#via_az').html(postci.indirizzo1)}else{$('#via_az').html('')};
                if(postci.citta1 != '' && postci.citta1 != null){$('#citta').html(postci.citta1)}else{$('#citta').html('')};
                if(postci.telefono != '' && postci.telefono != null){$('#tel_az').html('Tel: <a href="tel:'+postci.telefonos+'">'+postci.telefono+'</a>')}else{$('#tel_az').html('')};
                if(postci.telefono_2 != '' && postci.telefono_2 != null){$('#tel_az2').html('Tel: <a href="tel:'+postci.telefono2_2+'">'+postci.telefono_2+'</a>')}else{$('#tel_az2').html('')};
                if(postci.telefono2_3 != '' && postci.telefono2_3 != null){$('#tel_az3').html('Tel: <a href="tel:'+postci.telefono2_3+'">'+postci.telefono_3+'</a>')}else{$('#tel_az3').html('')};
                
                if(postci.indirizzo2 != ''){$('#via_az2').html(postci.indirizzo2)}else{$('#via_az2').html('')};
                if(postci.citta2 != '' && postci.citta2 != null){$('#b_citta').html(postci.citta2)}else{$('#b_citta').html('')};
                if(postci.b_telefono != '' && postci.b_telefono != null){$('#b_tel_az').html('Tel: <a href="tel:'+postci.b_telefono2+'">'+postci.b_telefono+'</a>')}else{$('#b_tel_az').html('')};
                if(postci.b_telefono2_2 != '' && postci.b_telefono2_2 != null){$('#b_tel_az2').html('Tel: <a href="tel:'+postci.b_telefono2_2+'">'+postci.b_telefono_2+'</a>')}else{$('#b_tel_az2').html('')};
                if(postci.b_telefono2_3 != '' && postci.b_telefono2_3 != null){$('#b_tel_az3').html('Tel: <a href="tel:'+postci.b_telefono2_3+'">'+postci.b_telefono_3+'</a>')}else{$('#b_tel_az3').html('')};
                
                if(postci.indirizzo3 != '' ){$('#via_az3').html(postci.indirizzo3)}else{$('#via_az3').html('')};
                if(postci.citta3 != '' && postci.citta3 != null){$('#c_citta').html(postci.citta3)}else{$('#c_citta').html('')};
                if(postci.c_telefono != '' && postci.c_telefono != null){$('#c_tel_az').html('Tel: <a href="tel:'+postci.c_telefono2+'">'+postci.c_telefono+'</a>')}else{$('#c_tel_az').html('')};
                if(postci.c_telefono2_2 != '' && postci.c_telefono2_2 != null){$('#c_tel_az2').html('Tel: <a href="tel:'+postci.c_telefono2_2+'">'+postci.c_telefono_2+'</a>')}else{$('#c_tel_az2').html('')};
                if(postci.c_telefono2_3 != '' && postci.c_telefono2_3 != null){$('#c_tel_az3').html('Tel: <a href="tel:'+postci.c_telefono2_3+'">'+postci.c_telefono_3+'</a>')}else{$('#c_tel_az3').html('')};
                
                var latitud3 = postci.latitudine;
                var longitud3 = postci.longitudine;

                if(latitud3 != null){var latitudine3 = latitud3;}else{var latitudine3 = '0.000000';}
                if(longitud3 != null){var longitudine3 = longitud3;}else{var longitudine3 = '0.000000';}
                
                $('#lat3').attr("value", latitudine3);
                $('#long3').attr("value", longitudine3);
                
                

                 $('#googles').html('<a onclick="window.open(this.href,\'_system\',\'location=no\');return false;" class="ui-btn" href="http://maps.google.com/?q='+latitudine3+','+longitudine3+'" id="geos2">Visualizza itinerario</a>');

                $(document).on('vclick', '#geos2', function(){
                        if ($(this).attr('target') === '_blank') {
                            window.open($(this).attr('href'),'_system','location=no');
                            e.preventDefault();
                        }
                    });

                var addressLongLat = latitudine3+','+longitudine3;

                $(document).on('vclick', '#web_btn', function(){
                    
                     window.open("http://maps.apple.com/?q="+addressLongLat, '_system');
                });
               

setTimeout(function() {
               var myLatlng = new google.maps.LatLng(latitudine3,longitudine3);
                var mapOptions = {
                  zoom: 10,
                  center: myLatlng,
                  draggable: false
                }
                var map = new google.maps.Map(document.getElementById("map-canvas3"), mapOptions);

                // To add the marker to the map, use the 'map' property
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map
                });
                }, 500); 
                
              
            });
  

        $(document).on('vclick', '#amico', function(){
            idamico  = $(this).attr('data-uid');
            $.mobile.changePage("#invia",{transition:"slide"});
        });


     });
}); 


$(document).on('pagebeforeshow', '#invia', function(data, status){

            $('#id_offerta').attr("value", idamico);
            $('#id_utente').attr("value", window.localStorage.getItem("utente"));

            $(document).on('vclick', '#inviaAmico', function(){
                var email_amicos = $("#email_amico").val();
                var id_offertas = $("#id_offerta").val();
                var id_utentes = $("#id_utente").val();
                var messaggios = $("#messaggio").val();
               
                
                if(email_amico == "" ){
                     navigator.notification.alert("Scrivi il tuo nome");
                   
                    return false;
                }else

                if(messaggio == "" ){
                     navigator.notification.alert("Scrivi il tuo cognome");
                   
                    return false;
                }

               
                


                validaamigo = "http://www.tuttoshopping.com/app/seba/invia-amico.php?jsoncallback=?"

                $.getJSON( validaamigo, { email_amico:email_amicos, id_utente:id_utentes, id_offerta:id_offertas, messaggio:encodeURIComponent(messaggios)})
                .done(function(respuestaServeramigo) {
                    
                    //alert(respuestaServer.mensaje + "\nGenerado en: " + respuestaServer.hora + "\n" +respuestaServer.generador);
                    
                    if(respuestaServeramigo.status == "ok"){
                           
                          $.mobile.changePage("#amicook");
                    }else{
                      navigator.notification.alert('Si è verificato un errore, riprova più tardi');
                      /// ejecutar una conducta cuando la validacion falla
                    }
              
                });
                return false;
        });
  });


//my account

$(document).on('pageinit', '#mydate', function(){
//geo
var urlprov2 = 'http://www.tuttoshopping.com/app/seba/province_all.php';
    
   var selectorp2 = $('#provincias2');
    $.ajax({
        url: urlprov2 ,
        jsonp: 'jsoncallback',

        success: function(data, status){
            
            
            $.each(data, function(i2,itemp2){
                
                var ciudadesp2 = '<option value="' + itemp2.id + '">' + itemp2.nome + '</option>';
                selectorp2.append(ciudadesp2);


            });
        }
    });



var utente = window.localStorage.getItem("utente");
var url_myaccount = 'http://www.tuttoshopping.com/app/seba/my_account.php?email='+utente;



    $.ajax({
        url: url_myaccount ,
        jsonp: 'jsoncallback',
        beforeSend: function() {
                    // This callback function will trigger before data is sent
                    $('div.ui-loader').show(); // This will show ajax spinner
                },
        complete: function() {
                    // This callback function will trigger on data sent/received complete
                    $('div.ui-loader').hide(); // This will hide ajax spinner
                },
        success: function(data, status){
        
        



            $.each(data, function(is,itemya){
               
                $("#nombre2").attr('value', itemya.Nome);
                $("#apellido2").attr('value', itemya.Cognome);
                $("#provincias2").val(itemya.Provincia);
                $("#emails2").attr('value', itemya.Email);
                $("#password1a").attr('value', itemya.Password);
                if(itemya.Newsletter == 1){$("#oferta").html('<label class="bgblack" for="offertes"><input type="checkbox" name="newsletter" checked id="offertes2" value="on">Desidero ricevere le offerte sempre aggiornarte su tuttoshopping. Benessere, Ristoranti, Tempo Libero, Professionisti.</label>');}else{$("#oferta").html('<label class="bgblack" for="offertes"><input type="checkbox" name="newsletter"  id="offertes2" value="off">Desidero ricevere le offerte sempre aggiornarte su tuttoshopping. Benessere, Ristoranti, Tempo Libero, Professionisti.</label>');}
                if(itemya.Newsletter_commerciale == 1){$("#comercial").html('<label class="bgblack" for="nuovis"><input type="checkbox" name="newslettercommerciale" checked id="nuovis2" value="on">Desidero ricevere informazioni riguardo i nuovi prodotti ed eventi di Tuttoshopping e informazioni commerciali di aziende terze selezionate direttamente da Tuttoshopping o di società partnet</label>');}else{$("#comercial").html('<label class="bgblack" for="nuovis"><input type="checkbox" name="newslettercommerciale" id="nuovis2" value="off">Desidero ricevere informazioni riguardo i nuovi prodotti ed eventi di Tuttoshopping e informazioni commerciali di aziende terze selezionate direttamente da Tuttoshopping o di società partnet</label>');}
                
                var myselect = $("#provincias2");
                myselect[0].selectedIndex = itemya.Provincia;
                myselect.selectmenu("refresh");
                var vecchia = itemya.Password;
                

            });

            $(document).on('vclick', '#botonRegistra2', function(vecchia){
                    var nombre = $("#nombre2").val();
                    var apellido = $("#apellido2").val();
                    var provincias = $("#provincias2").val();
                    var emails = $("#emails2").val();
                    var password1 = $("#password1a").val();
                    var password2 = $("#password2b").val();
                    var password3 = $("#password2c").val();
                    var offertes = $("#offertes2").val();
                    var nuovis = $("#nuovis2").val();
                    
                    if(nombre == "" ){
                         alert("Scrivi il tuo nome");
                       
                        return false;
                    }else

                    if(apellido == "" ){
                         alert("Scrivi il tuo cognome");
                       
                        return false;
                    }else

                    if(provincias == "" ){
                         alert("Scegli una provincia");
                      
                        return false;
                    }else

                    if(emails == "" ){
                         alert("Scrivi la tua email");
                       
                        return false;
                    }else

                    

                    
                    if(password2 == "" ){
                         alert("Scrivi la tua nuova password");
                        
                        return false;
                    }else

                    if(password3 == "" ){
                         alert("Conferma la tua nuova password");
                        
                        return false;
                    }else

                    if(password2 != password3 ){
                         alert("La conferma della nuova password non corrisponde");
                      
                        return false;
                    }


                    archivoValidacion23 = "http://www.tuttoshopping.com/app/seba/invia-myaccount.php?jsoncallback=?"

                    $.getJSON( archivoValidacion23, { nome:encodeURIComponent(nombre) ,cognome:encodeURIComponent(apellido), email:emails, provincia:provincias, password:encodeURIComponent(password1), passwordnew:encodeURIComponent(password2), passwordnewconf:encodeURIComponent(password3), newsletter:offertes, newslettercommerciale:nuovis})
                    .done(function(respuestaServer23) {
                        
                        //alert(respuestaServer.mensaje + "\nGenerado en: " + respuestaServer.hora + "\n" +respuestaServer.generador);
                        
                        if(respuestaServer23.status == "ok"){
                            
                              $.mobile.changePage("#logout");
                        }else{
                          navigator.notification.alert('i dati non corrispondono');
                          /// ejecutar una conducta cuando la validacion falla
                        }
                  
                    });
                    return false;


                });
        },

        error: function (request,error) {
                    navigator.notification.alert('Conessione internet assente, riprova più tardi');
                }
    });

});

$(document).on('pageinit', '#recupera', function(){
         $(document).on('vclick', '#inviarecupero', function(){        
                var emailre = $("#email_recupero").val();
                if(emailre == ""){
                         navigator.notification.alert("Scrivi la tua email");
                      
                        return false;
                    }


                    archivoValidacion23a = "http://www.tuttoshopping.com/app/seba/recupero_password.php?jsoncallback=?"

                    $.getJSON( archivoValidacion23a, { email:emailre})
                    .done(function(respuestaServer23a) {
                        
                        //alert(respuestaServer.mensaje + "\nGenerado en: " + respuestaServer.hora + "\n" +respuestaServer.generador);
                        
                        if(respuestaServer23a.status == "ok"){
                            
                              $.mobile.changePage("#login");
                        }else{
                          navigator.notification.alert('i dati non corrispondono');
                          /// ejecutar una conducta cuando la validacion falla
                        }
                  
                    });
                    return false;
                    });
});

$(document).on('pageinit', '#logout', function(){
localStorage.clear();
 $.mobile.changePage("#home");
});

 