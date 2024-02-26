<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Annuler un billet</title>
  </head>
  
  <body>
   	<h1><center>Annuler un billet</center></h1><br/>
    <?php 
	//connexion à la BDD
	function connect()
	{
	$con=pg_connect("host=serveur-etu.polytech-lille.fr user=amady port=5432 password=postgres dbname=gestion_compagnie") ;
	return $con;
	}

	$con=connect();
	if (!$con)
		{
		echo "Probleme connexion à la base";
		exit;
	}
	
	$vols="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee,nome,prenome from vol natural join employé order by refv";
	$resvols=pg_query($vols);
	
	?>
	
	<!--Formulaire pour choisir un vol-->
	<form action='annulerBillet.php' method=POST>
		<label for="vol">Choisir un vol : </label>
		<table border=1>
			<tr><td>Référence de l'avion</td><td>Référence du vol</td><td>Ville de départ</td><td>Ville d'arrivée</td><td>Date de départ</td><td>Date d'arrivée</td><td>Horaire de départ</td><td>Horaire d'arrivée</td><td>Prénom et nom du responsable du vol</td><td>Sélectionner un vol</td></tr>
		<?php
		//Selectionner un vol parmi une liste
		while ($lignevol=pg_fetch_array($resvols)){
			
			echo "<tr><td>". $lignevol['refa'] ."</td> <td>". $lignevol['refv'] ."</td><td>". $lignevol['villedepart'] ."</td><td>". $lignevol['villearrivee'] ."</td><td>". $lignevol['datedepart'] ."</td><td>". $lignevol['datearrivee'] ."</td><td>". $lignevol['horairedepart'] ."</td><td>". $lignevol['horairearrivee'] ."</td><td>". $lignevol['prenome'] ." ". $lignevol['nome'] ."</td><td><input type='radio' name='choixVol' value='".$lignevol['refv']."' /></td></tr>";
		}
		?>
		</table></br>
    <input type="hidden" name="validerChoixVol">
    <input type="submit" value="Valider mon choix" />
    </form><br/>
    
    <?php
    //Si le vol est selectionné, afficher la liste de billets
    if (isset($_POST['validerChoixVol'])){
   		$billet="select refb,datee,numplace,prenomp,nomp from billet order by refb";
		$resbillet=pg_query($billet);
	
    	echo"<form action='annulerBillet.php' method=POST>
		<label for='vol'>Choisir un(les) billet(s) à supprimer : </label>
		<table border=1>
		<tr><td>Référence du billet</td><td>Date d'émission du billet</td><td>Numéro de la place</td><td>Prénom et nom du passager</td><td>Sélectionner un billet</td></tr>";
		
		while ($lignebillet=pg_fetch_array($resbillet)){
			echo"<tr><td>". $lignebillet['refb'] ."</td> <td>". $lignebillet['datee'] ."</td><td>". $lignebillet['numplace'] ."</td><td>". $lignebillet['prenomp'] ." ". $lignebillet['nomp'] ."</td><td><input type='checkbox' name='selectBillet' value='".$lignebillet['refb']."' /></td></tr>";
		}
		
		echo"</table></br>
    	<input type='hidden' name='validerChoixBillet'>
   		<input type='submit' value='Valider mon choix' />
    	</form><br/>";
	}
	
	//Si le billet est selectionné, le supprimer
	if (isset($_POST['validerChoixBillet'])){
		$supp="delete from billet where refb='".$_POST['selectBillet']."'";
    	pg_query ($con, $supp);
    	echo"Suppression effectuée <br/>";
	}
	?>
    <form action="index.php" method=POST>
		<input type="submit" value="Retourner à l'accueil" />
	</form>
	</body>
</html>
