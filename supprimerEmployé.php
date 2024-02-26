<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Supprimer un employé</title>
  </head>
  
  <body>
   	<h1><center>Supprimer un employé</center></h1><br/>
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
	
	$employé="select numposte, nomf,prenome,nome from employé natural join fonction order by numposte";
	$resemployé=pg_query($employé);
	
	?>
	<!--Formulaire pour selectionner un employé-->
	<form action='supprimerEmployé.php' method=POST>
		<label for="employé">Choisir un employé : </label>
		<table border=1>
			<tr><td>Numéro de l'employé</td><td>Fonction exercée</td><td>Prénom et nom de l'employé</td><td>Sélectionner un employé</td></tr>
		<?php
		//Afficher les employés dans un tableau
		while ($ligneEmp=pg_fetch_array($resemployé)){
			
			echo "<tr><td>". $ligneEmp['numposte'] ."</td> <td>". $ligneEmp['nomf'] ."</td><td>". $ligneEmp['prenome'] ." ". $ligneEmp['nome'] ."</td><td><input type='checkbox' name='choixEmp' value='".$ligneEmp['numposte']."' /></td></tr>";
		}
		?>
		</table></br>
    <input type="hidden" name="validerChoixEmp">
    <input type="submit" value="Valider mon choix" />
    </form><br/>
    
    <?php
    //Si un employé est selectionné, le supprimer
	if (isset($_POST['validerChoixEmp'])){
		$supp="delete from employé where numposte='".$_POST['choixEmp']."'";
    	pg_query ($con, $supp);
    	
    	$supp2="delete from equipe_maintenance where numposte='".$_POST['choixEmp']."'";
    	pg_query ($con, $supp2);
    	echo"Suppression effectuée <br/>";
	}
	?>
	
    <form action="index.php" method=POST>
		<input type="submit" value="Retourner à l'accueil" />
	</form>
	</body>
</html>
