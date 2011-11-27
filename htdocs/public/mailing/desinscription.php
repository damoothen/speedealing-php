<?php
/* Copyright (C) 2011 Frédérique GUYOT
 * Copyright (C) 2011 Herve Prot     <herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *       \file       htdocs/public/mailing/desincription.php
 *       \ingroup    mailing
 *       \brief      Fiche mailing, desincription
 *       \version    $Id: fiche.php,v 1.123 2011/11/25 00:46:33 hepr Exp $
 */

define("NOLOGIN",1);		// This means this output page does not require to be logged.
define("NOCSRFCHECK",1);	// We accept to go on this page from external web site.

require("../../main.inc.php");

    // vérification du champ nom passé en paramétre dans l'url (nom de l'entreprise)
	if(isset($_GET['nom']))		
	{
		if (is_string($_GET['nom']))		
		{
			$nomEts = stripslashes(htmlentities($_GET['nom']));
		}
		else
		{
			echo '<p>Le nom de l\'entreprise n\'est pas de type string</p>';
		}
	}
	else 
	{
		echo '<p>Variable nom entreprise non d&eacute;termin&eacute;e</p>';
	}
    
        // vérification du champ mail passé en paramétre dans l'url (email de l'entreprise)
	if(isset($_GET['mail']))		
	{
		if (is_string($_GET['mail']))	
		{
			$mail = stripslashes(htmlentities($_GET['mail']));
		}
		else
		{
			echo '<p>Le mail n\'est pas de type string</p>';
		}
	}
	else 
	{
		echo '<p>Variable mail non d&eacute;termin&eacute;e</p>';
	}
        // vérification du champ id passé en paramétre dans l'url (email de l'entreprise)
	if(isset($_GET['id']))		
	{
		if (is_string($_GET['id']))	
		{
			$id = stripslashes(htmlentities($_GET['id']));
		}
		else
		{
			echo '<p>L\'id n\'est pas de type string</p>';
		}
	}
	else 
	{
		echo '<p>Variable id non d&eacute;termin&eacute;e</p>';
	}

    /* Si le formulaire est envoyé alors on fait les traitements */
    if (isset($_POST['envoye']))
    {
        $error=0;
        
        $sql = "UPDATE ".MAIN_DB_PREFIX."societe SET";
	$sql.= " newsletter=0";
	$sql.= " WHERE rowid = ".$_GET['id'];
        $sql.= " AND email = '".$_GET['mail']."'";
        
        if (!$db->query($sql) )
            $error++;
        
        $sql = "UPDATE ".MAIN_DB_PREFIX."socpeople SET";
	$sql.= " newsletter=0";
	$sql.= " WHERE rowid = ".$_GET['id'];
        $sql.= " AND email = '".$_GET['mail']."'";

	dol_syslog("Desincription::Update sql=".$sql,LOG_DEBUG);
	if (!$db->query($sql) )
            $error++;
        
        if($error==0)
        {
                $alert = 'D&eacutesinscription effectu&eacute;e avec succ&egrave;s';
                
                /* On détruit la variable $_POST */
                unset($_POST);
        }
        else
        {
            $alert = 'Erreur dans l\'e-mail';
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr">
    <head>
        <title>D&eacute;sinscription</title>
        <meta http-equiv="content-type" content="text/html;charset=ISO 8859-1" />
        <link rel="stylesheet" type="text/css" href="css/styleDesinscription.css" />
    </head>
    
    <body>
        <?php
            // test si les champs nom et mail sont vides
            if(empty($nomEts) OR empty($mail)) 
            {
                echo 'nomets '.$nomEts.'<br/>mail '.$mail.'<br/>';
                echo '<font color="red" style="font-size:14px;" >Attention, les param&egrave;tres passés dans l\'url sont absents  !<br/></font>';
            }
            else
            {
                print '<h2>'.$nomEts.'</h2>';
                print '<p class="titre">Formulaire de d&eacute;sinscription de la newsletter </p>';
                if (!empty($alert))
                {
                    echo '<p style="color:red">'.$alert.'</p>';
                }
               
                print '<form action="desinscription.php?nom='.$nomEts.'&amp;mail='.$mail.'&amp;id='.$id.'"" method="post" id="formulaire" >';
                    print '<p>Votre email : '.$mail.'</p>';
                    print '<p>';
                    print '</p>';
                    print '<p>';
                        print '<input type="hidden" id="sujet" name="sujet" value="désinscription de la newsletter" />';
                    print '</p>';
                    print '<p>';
                        print '<input type="hidden" id="message" name="message" value="Cette personne souhaite se désinscrire de la newsletter" />';
                    print '</p>';
                    print '<p>';
                        print '<input type="submit" name="envoye" id="bouton" value="Se d&eacute;sinscrire" />';
                    print '</p>';
                print '</form>';
            }
            ?>
    </body>
</html>