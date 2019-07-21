'use strict';

/* - Permet de lancer un TIMER pour les message flash - */
let message = document.querySelector('div#message');
if (message)
{
	if (message.parentNode !== null) {
		if (message.parentNode.hasChildNodes())
		{
			setInterval(function(){
				message.innerHTML = "";
				if (message.hasChildNodes()) {
					message.parentNode.removeChild(message);
				}
				clearInterval(message.parentNode.removeChild(message) !== null);
			}, 10000);
		}
	}
}

/* - Permet de cacher des div pour une meilleur lisibilite- */
var view_subscribe = document.querySelector("#view_subscribe");

if (view_subscribe) {
	view_subscribe.onclick = function () {
		var information = document.querySelector("#information");
		information.innerHTML = "<div class=\"inscription\"> <div class=\"title\"> <h2>Formulaire d'inscription</h2> </div> <div class=\"form\"> <form action=\"pages/subscribe.php\" method=\"post\"> <div class=\"form-input\"> <div class=\"input-name\">Email</div> <div class=\"input\"> <input id=\"email\" type=\"email\" name=\"email\" required> </div> </div> <div class=\"form-input\"> <div class=\"input-name\">Identifiant</div> <div class=\"input\"> <input id=\"login\" type=\"login\" name=\"login\" required> </div> </div> <div class=\"form-input\"> <div class=\"input-name\">Mot de passe</div> <div class=\"input\"> <input id=\"pass1\" type=\"password\" name=\"pass\" required> </div> </div> <div class=\"form-input\"> <div class=\"input-name\">Confirmez le mot de passe</div> <div class=\"input\"> <input id=\"pass2\" type=\"password\" name=\"pass2\" required> </div> </div> <div class=\"submit\"> <button class=\"btn\" style=\"float:right\" type=\"submit\">Continuer</button> </div> </form> <br clear=\"both\"> </div> </div>";
	};
}

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			var preupload = document.querySelector("#preupload");
			preupload.src = e.target.result;
		}

		reader.readAsDataURL(input.files[0]);
	}
}