<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Ajouter un employé</title>
  </head>
  
  <body>
   	<h1><center>Ajouter un employé</center></h1><br/>
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
	
	$fonction="select numf,nomf from fonction";
	$resfonction=pg_query($fonction);
	?>
	
	<!--Formulaire pour ajouter un employé-->
	<form action="ajouterEmployé.php" method=POST>
		<label for="numemp">Numéro de l'employé </label>
		<input type="number" id="numemp" name="numemp" min="1" required><br/><br/>
	
		<label for="nomf">Fonction exercée : </label>
		<select name="nomf" id="nomf" required>
		<?php
		//Sélectionner une fonction parmi la liste
		while($lignefonction=pg_fetch_array($resfonction)){
			echo "<option value='".$lignefonction['numf']."'>".$lignefonction['nomf']."</option>";
		}
		?>
		</select><br/><br/>
		
		<label for="namee">Nom et prénom de l'employé : </label><br/>
		<label for="nome">Nom</label>
		<input type="text" id="nome" name="nome" required><br/><br/>
		<label for="prenome">Prénom</label>
		<input type="text" id="prenome" name="prenome" required><br/><br/>
	
		<input type="hidden" name="ajoutDonnée">
        <input type="submit" value="Valider" />
	</form><br/>
	
	<?php
	//Si le formulaire est saisi, faire...
	if (isset($_POST['ajoutDonnée'])){
		$ajout="insert into employé values (".$_POST['numemp'].",'".$_POST['nomf']."','".$_POST['nome']."','".$_POST['prenome']."')";
		pg_query ($con, $ajout);
		echo"Modification effectuée <br/>";
	}
	?>
	
	<form action="index.php" method=POST>
		<input type="submit" value="Retourner à l'accueil" />
	</form>
	</body>
</html>
