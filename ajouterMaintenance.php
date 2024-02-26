<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Ajouter une maintenance</title>
  </head>
  
  <body>
   	<h1><center>Ajouter une maintenance</center></h1><br/>
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
	
	$avion="select refa from avion";
	$resavion=pg_query($avion);
	?>
	
	<!--Formulaire pour ajouter une maintenance-->
	<form action="ajouterMaintenance.php" method=POST>
		<label for="refm">Référence de la maintenance </label>
		<input type="number" id="refm" name="refm" min="1" required><br/><br/>
	
		<label for="refa">Référence de l'avion : </label>
		<select name="refa" id="refa" required>
		<?php
		//Selectionner un avion parmi une liste
		while($ligneavion=pg_fetch_array($resavion)){
			echo "<option value='".$ligneavion[0]."'>".$ligneavion[0]."</option>";
		}
		?>
		</select><br/><br/>
			
		<label for="namee">Nom et prénom du responsable : </label><br/>
		<label for="nome">Nom</label>
		<input type="text" id="nome" name="nome" required><br/><br/>
		<label for="prenome">Prénom</label>
		<input type="text" id="prenome" name="prenome" required><br/><br/>
	
		<label for="datem">Date de la maintenance</label>
		<input type="date" id="datem" name="datem" required><br/><br/>
	
		<?php
		$membre="select numposte,nome,prenome,nomf from employé natural join fonction where employé.numf=fonction.numf order by numposte";
		$resmembre=pg_query($membre);
		?>
		<label for="membre">Membres de l'équipe de maintenance : </label>
		<table border=1>
		<tr><td>Nom et prénom de l'employé</td><td>Fonction</td><td>Sélectionnez les membres</td></tr>
		<?php
		//Selectionner les membres de la maintenance parmi une liste
		while($lignemembre=pg_fetch_array($resmembre)){
			echo "<tr><td>". $lignemembre['nome'] ." ". $lignemembre['prenome'] ."</td><td>". $lignemembre['nomf'] ."</td><td><input type='checkbox' name='empSelect' value='".$lignemembre['numposte']."' /></td></tr>";
    	}
    	?>
    	</table><br/>
    	
		<input type="hidden" name="ajoutDonnée">
        <input type="submit" value="Valider" />
	</form><br/>
	
	<?php
	//Si le formulaire est saisi, ajouter les données à la table maintenance
	if (isset($_POST['ajoutDonnée'])){
		$responsable="select * from employé where nome='".$_POST['nome']."' and prenome='".$_POST['prenome']."'";
		$resresp=pg_query($responsable);
		$ligneresp=pg_fetch_array($resresp);
		
		//Si le nom du responsable existe, faire...
		if($ligneresp){
			$ajout="insert into maintenance values (".$_POST['refm'].",'".$_POST['refa']."',".$ligneresp['numposte'].",'".$_POST['datem']."')";
			pg_query ($con, $ajout);
			
			$ajoutmembre="insert into equipe_maintenance values ('".$_POST['empSelect']."',".$_POST['refm'].")";
    		pg_query ($con, $ajoutmembre);
			echo"Modification effectuée <br/>";
		}
		else{
			echo"Identité du responsable introuvable";
		}
	}
	?>
	
	<form action="index.php" method=POST>
		<input type="submit" value="Retourner à l'accueil" />
	</form>
	</body>
</html>
