<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Supprimer un vol</title>
  </head>
  
  <body>
   	<h1><center>Supprimer un vol</center></h1><br/>
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
	
	//Formulaire pour selectionner un vol
	echo "<form action='supprimerVol.php' method=POST>
		<br/><table border=1>
		<tr><td>Référence de l'avion</td> <td>Référence du vol</td><td>Ville de départ</td><td>Ville d'arrivée</td><td>Date de départ</td><td>Date d'arrivée</td><td>Horaire de départ</td><td>Horaire d'arrivée</td><td>Sélectionnez un vol</td></tr>";
		
	//Afficher tous les vols dans un tableau
	while($lignevol=pg_fetch_array($resvol)){
			echo "<tr><td>". $lignevol['refa'] ."</td> <td>". $lignevol['refv'] ."</td><td>". $lignevol['villedepart'] ."</td><td>". $lignevol['villearrivee'] ."</td><td>". $lignevol['datedepart'] ."</td><td>". $lignevol['datearrivee'] ."</td><td>". $lignevol['horairedepart'] ."</td><td>". $lignevol['horairearrivee'] ."</td><td><input type='checkbox' name='volSelect' value='".$lignevol['refv']."' /></td></tr>";
    }
    
    echo"</table></br>
    <input type='hidden' name='validerChoixVol'>
    <input type='submit' value='Valider mon choix' />
    </form><br/>";
    
    //Si un vol est selectionné, le supprimer
    if (isset($_POST['validerChoixVol'])){
    	$supp="delete from vol where refv='".$_POST['volSelect']."'";
    	pg_query ($con, $supp);
    	echo"Suppression effectuée <br/>";
	}
	?>
    <form action="index.php" method=POST>
		<input type="submit" value="Retourner à l'accueil" />
	</form>
	</body>
</html>
