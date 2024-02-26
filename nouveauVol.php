<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Ajouter un nouveau vol</title>
  </head>
  
  <body>
   	<h1><center>Ajouter un nouveau vol</center></h1><br/>
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
	
	$refav="select refa from avion";
	$resrefa=pg_query($refav);
	?>
	
	<!--Formulaire pour saisir un vol-->
	<form action="nouveauVol.php" method=POST>
		<label for="refa">Référence de l'avion : </label>
		<select name="refa" id="refa" required>
		<?php
		//selectionner un avion parmi la liste
		while($lignerefa=pg_fetch_array($resrefa)){
			echo "<option value='".$lignerefa[0]."'>".$lignerefa[0]."</option>";
		}
		?>
		</select><br/><br/>
		
		<label for="name">Référence du vol : </label>
		<input type="text" id="refv" name="refv" required><br/><br/>
		
		<label for="name">Ville de départ : </label>
		<input type="text" id="villedepart" name="villedepart" required><br/><br/>

		<label for="name">Ville d'arrivée : </label>
		<input type="text" id="villearrivee" name="villearrivee" required><br/><br/>
	
		<label for="date">Date de départ : </label>
		<input type="date" id="date" name="datedepart"required><br/><br/>
	
		<label for="date">Date d'arrivée : </label>
		<input type="date" id="date" name="datearrivee" required><br/><br/>

		<label for='horairedepart'>Horaire de départ : </label>
		<input type="time" id="horairedepart" name="horairedepart" min="00:00" max="23:59" required><br/><br/>

		<label for="horairearrivee">Horaire d'arrivée : </label>
		<input type="time" id="horairearrivee" name="horairearrivee" min="00:00" max="23:59" required><br/><br/>
		
		<label for="name">Nom et prénom du responsable du vol : </label><br/>
		<label for="nom">Nom</label>
		<input type="text" id="nom" name="nom" required><br/><br/>
		<label for="nom">Prénom</label>
		<input type="text" id="prenom" name="prenom" required><br/><br/>
		
		<?php
		$membre="select numposte,nome,prenome,nomf from employé natural join fonction where employé.numf=fonction.numf order by numposte";
		$resmembre=pg_query($membre);
		?>
		
		<label for="membre">Membres de l'équipage : </label>
		<table border=1>
		<tr><td>Nom et prénom de l'employé</td><td>Fonction</td><td>Sélectionnez les membres du vol</td></tr>
		<?php
		//Selectionner les membres du vol parmi une liste
		while($lignemembre=pg_fetch_array($resmembre)){
			echo "<tr><td>". $lignemembre['nome'] ." ". $lignemembre['prenome'] ."</td><td>". $lignemembre['nomf'] ."</td><td><input type='checkbox' name='empSelect' value='".$lignemembre['numposte']."' /></td></tr>";
    	}
    	?>
    	</table><br/>

		<input type="hidden" name="ajoutDonnée">
        <input type="submit" value="Valider" />
	</form><br/>
	
	<?php
	//Si les informations du vol sont saisis, ajouter le vol
	if (isset($_POST['ajoutDonnée'])){
		$responsable="select * from employé where nome='".$_POST['nom']."' and prenome='".$_POST['prenom']."'";
		$resresp=pg_query($responsable);
		$ligneresp=pg_fetch_array($resresp);
		
		if($ligneresp){
			$ajoutvol="insert into vol(refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee,numposte) values ('".$_POST['refv']."',".$_POST['refa'].",'".$_POST['villedepart']."','".$_POST['villearrivee']."','".$_POST['datedepart']."','".$_POST['datearrivee']."','".$_POST['horairedepart']."','".$_POST['horairearrivee']."',".$ligneresp[0].")";
    		pg_query ($con, $ajoutvol);
    		
    		$ajoutmembre="insert into membre_équipage values ('".$_POST['refv']."',".$_POST['empSelect'].")";
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
