<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Modifier vol d'une compagnie aérienne</title>
  </head>
  
  <body>
   	<h1><center> Modifier un vol</center></h1><br/>
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
	
	$vol="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee from vol order by refv";
	$resvol=pg_query($vol);
	
	//Selectionner un vol dans un tableau
	echo "<h3>Sélectionnez un vol à modifier</h3>";
	echo "<form action='modifierVol.php' method=POST>
		<br/><table border=1>
		<tr><td>Référence de l'avion</td> <td>Référence du vol</td><td>Ville de départ</td><td>Ville d'arrivée</td><td>Date de départ</td><td>Date d'arrivée</td><td>Horaire de départ</td><td>Horaire d'arrivée</td><td>Sélectionnez un vol</td></tr>";
	
	while($lignevol=pg_fetch_array($resvol)){
			echo "<tr><td>". $lignevol['refa'] ."</td> <td>". $lignevol['refv'] ."</td><td>". $lignevol['villedepart'] ."</td><td>". $lignevol['villearrivee'] ."</td><td>". $lignevol['datedepart'] ."</td><td>". $lignevol['datearrivee'] ."</td><td>". $lignevol['horairedepart'] ."</td><td>". $lignevol['horairearrivee'] ."</td><td><input type='radio' name='volSelect' value='".$lignevol['refv']."' /></td></tr>";
    }
    
    echo"</table></br>
    <input type='hidden' name='validerChoixVol'>
    <input type='submit' value='Valider mon choix' />
    </form><br/>";
    
    //Si un vol est selectionné
    if (isset($_POST['validerChoixVol'])){
    	if(isset($_POST['volSelect'])){
    		$selectvol="select datedepart,datearrivee,horairedepart,horairearrivee from vol where refv='".$_POST['volSelect']."' order by refv";
			$resselectvol=pg_query($selectvol);
			
			//Selectionner les informations à modifier
			while($ligneselect=pg_fetch_array($resselectvol)){
				echo "<form action='modifierVol.php' method=POST>
		  		<fieldset>
		  		<legend>Choisissez les données à modifier</legend>
		  		
				<input type='checkbox' name='dateDep' value='dateDep'>
				<label for='dateDep'>Date de départ : ".$ligneselect[0]."</label>
				
				<input type='checkbox' name='dateArr' value='dateArr'>
				<label for='dateArr'>Date d'arrivée : ".$ligneselect[1]."</label>
				
				<input type='checkbox' name='horaireDep' value='horaireDep'>
				<label for='horaireDep'>Horaire de départ : ".$ligneselect[2]."</label>

				<input type='checkbox' name='horaireArr' value='horaireArr'>
				<label for='horaireArr'>Horaire d'arrivée : ".$ligneselect[3]."</label>
				
				<input type='hidden' name='modifVol'>
				<input type='hidden' name='refv' value='".$_POST['volSelect']."'>
				<button type='submit'>Envoyer</button>
				</fieldset>
				</form><br/>";
			}
    	}
    	else{
    		echo"Aucun vol saisi";
		}
	}
	
	//Modifier les informations selectionnées
    if (isset($_POST['modifVol'])){
    	echo"<form action='modifierVol.php' method=POST>";
    	
		if (isset($_POST['dateDep'])){
			echo"<label for='date'>Date de départ : </label>
			<input type='date' id='date' name='datedepart' required><br/><br/>";
		}
		if (isset($_POST['dateArr'])){
			echo"<label for='date'>Date d'arrivée : </label>
			<input type='date' id=date' name='datearrivee' required><br/><br/>";
		}
		if (isset($_POST['horaireDep'])){
			echo"<label for='horairedepart'>Horaire de départ : </label>
			<input type='time' id='horairedepart' name='horairedepart' min='00:00' max='23:59' required><br/><br/>";

		}
		if (isset($_POST['horaireArr'])){
			echo"<label for='horairearrivee'>Horaire d'arrivée : </label>
			<input type='time' id='horairearrivee' name='horairearrivee' min='00:00' max='23:59' required><br/><br/>";
		}
		
		if (!isset($_POST['horaireArr']) && !isset($_POST['horaireDep']) && !isset($_POST['dateArr']) && !isset($_POST['dateDep'])){
			echo"Aucune information modifiée <br/>";
		}
		
		echo"<input type='hidden' name='updateDonnée'>
			<input type='hidden' name='refv' value='".$_POST['refv']."'>
        	<input type='submit' value='Valider la/les modification(s)' />
		</form>";
	}
	
	//Faire la modification des informations
	if (isset($_POST['updateDonnée'])){
		if (isset($_POST['datedepart'])){
			$update3="update vol set datedepart='".$_POST['datedepart']."' where refv='".$_POST['refv']."'";
			pg_query ($con, $update3);
		}
		if (isset($_POST['datearrivee'])){
			$update4="update vol set datearrivee='".$_POST['datearrivee']."' where refv='".$_POST['refv']."'";
			pg_query ($con, $update4);
		}
		if (isset($_POST['horairedepart'])){
			$update5="update vol set horairedepart='".$_POST['horairedepart']."' where refv='".$_POST['refv']."'";
			pg_query ($con, $update5);
		}
		if (isset($_POST['horairearrivee'])){
			$update6="update vol set horairearrivee='".$_POST['horairearrivee']."' where refv='".$_POST['refv']."'";
			pg_query ($con, $update6);
		}
		echo"Modification effectuée <br/>";
	}
	?>
	
	<form action="index.php" method=POST>
		<input type="submit" value="Retourner à l'accueil" />
	</form>
	
  </body>
</html>
