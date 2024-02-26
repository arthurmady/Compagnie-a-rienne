<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Supprimer une maintenance</title>
  </head>
  
  <body>
   	<h1><center>Supprimer une maintenance</center></h1><br/>
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
	
	$maintenance="select refm,refa,prenome,nome,datem,numposte from maintenance natural join employé order by refm";
	$resmaintenance=pg_query($maintenance);
	
	?>
	<!--Formulaire pour selectionner une maintenance-->
	<form action='supprimerMaintenance.php' method=POST>
		<label for="employé">Choisir une maintenance : </label>
		<table border=1>
			<tr><td>Référence de la maintenance</td><td>Référence de l'avion</td><td>Prénom et nom du responsable</td><td>Date de la maintenance</td><td>Sélectionner une maintenance</td></tr>
		<?php
		//Afficher toutes les maintenances dans un tableau
		while ($ligneMaint=pg_fetch_array($resmaintenance)){
			
			echo "<tr><td>". $ligneMaint['refm'] ."</td> <td>". $ligneMaint['refa'] ."</td><td>". $ligneMaint['prenome'] ." ". $ligneMaint['nome'] ."</td><td>". $ligneMaint['datem'] ."</td><td><input type='checkbox' name='choixMaint' value='".$ligneMaint['refm']."' /></td></tr>";
		}
		?>
		</table></br>
    <input type="hidden" name="validerChoixMaint">
    <input type="submit" value="Valider mon choix" />
    </form><br/>
    
    <?php
    //Si une maintenance est selectionnée, la supprimer
	if (isset($_POST['validerChoixMaint'])){
		$supp="delete from maintenance where numposte='".$_POST['choixMaint']."'";
    	pg_query ($con, $supp);
    	
    	$supp2="delete from equipe_maintenance where numposte='".$_POST['choixMaint']."'";
    	pg_query ($con, $supp2);
    	echo"Suppression effectuée <br/>";
	}
	?>
    <form action="index.php" method=POST>
		<input type="submit" value="Retourner à l'accueil" />
	</form>
	</body>
</html>
