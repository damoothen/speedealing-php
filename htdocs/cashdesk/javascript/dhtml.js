
/* Copyright (C) 2007-2008 Jeremie Ollivier <jeremie.o@laposte.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// Instanciation et initialisation de l'objet xmlhttprequest
function file (fichier) {

	// Instanciation de l'objet pour Mozilla, Konqueror, Opera, Safari, etc ...
	if (window.XMLHttpRequest) {

		xhr_object = new XMLHttpRequest ();

	// ... ou pour IE
	} else if (window.ActiveXObject) {

		xhr_object = new ActiveXObject ("Microsoft.XMLHTTP");

	} else {

		return (false);

	}

	xhr_object.open ("GET", fichier, false);
	xhr_object.send (null);

	if (xhr_object.readyState == 4) {

		return (xhr_object.responseText);

	} else {

		return (false);

	}

}


// Affichage des donnees aTexte dans le bloc identifie par aId
function afficheDonnees (aId, aTexte) {

	document.getElementById(aId).innerHTML = aTexte;

}


// aCible : id du bloc de destination; aCode : argument a passer a la page php chargee du traitement et de l'affichage
function verifResultat (aCible, aCode) {
	if (aCode != '') {

		if (texte = file ('facturation_dhtml.php?code='+escape(aCode))) {

			afficheDonnees (aCible, texte);

		} else

			afficheDonnees (aCible, '');

	}

}


// Change dynamiquement la classe de l'element ayant l'id aIdElement pour aClasse
function setStyle (aIdElement, aClasse) {

	aIdElement.className = aClasse;

}













