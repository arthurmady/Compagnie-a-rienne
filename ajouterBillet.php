<!DOCTYPE html>
<!--serveur-etu.polytech-lille.fr/amady-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Ajouter un billet</title>
  </head>
  
  <body>
   	<h1><center>Ajouter un billet</center></h1><br/>
   	
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
	
	$refv="select refv,nbresiege from vol natural join avion";
	$resrefv=pg_query($refv);
	?>
	
	<!--Formulaire pour ajouter un billet-->
	<form action="ajouterBillet.php" method=POST>
		<label for="refb">Référence du billet </label>
		<input type="number" id="refb" name="refb" min="1" required><br/><br/>
	
		<label for="refv">Référence du vol : </label>
		<select name="refv" id="refv" required>
		<?php
		//selectionner un vol dans une liste
		while($lignerefv=pg_fetch_array($resrefv)){
			echo "<option value='".$lignerefv[0]."'>".$lignerefv[0]."</option>";
		}
		?>
		</select><br/><br/>
		
		<label for="namee">Nom et prénom de l'employé ayant émis le billet : </label><br/>
		<label for="nome">Nom</label>
		<input type="text" id="nome" name="nome" required><br/><br/>
		<label for="prenome">Prénom</label>
		<input type="text" id="prenome" name="prenome" required><br/><br/>
	
		<label for="date">Date d'émission du billet </label>
		<input type="date" id="date" name="dateEmission" required><br/><br/>

		<label for="nump">Numéro de la place</label>
		<input type="number" id="nump" name="nump" min="1" max='".$lignerefv[1]."' required><br/><br/>
		
		<label for="namep">Nom et prénom du passager : </label><br/>
		<label for="nomp">Nom</label>
		<input type="text" id="nomp" name="nomp" required><br/><br/>
		<label for="prenomp">Prénom</label>
		<input type="text" id="prenomp" name="prenomp" required><br/><br/>
		
		<input type="hidden" name="ajoutDonnée">
        <input type="submit" value="Valider" />
	</form><br/>
	
	<?php
	//si le formulaire est saisi, faire...
	if (isset($_POST['ajoutDonnée'])){
		$responsable="select * from employé where nome='".$_POST['nome']."' and prenome='".$_POST['prenome']."'";
		$resresp=pg_query($responsable);
		$ligneresp=pg_fetch_array($resresp);
		
		//insérer données dans la BDD billet
		if($ligneresp){
				$ajout="insert into billet values (".$_POST['refb'].",'".$_POST['refv']."',".$ligneresp[0].",'".$_POST['dateEmission']."','".$_POST['nump']."','".$_POST['prenomp']."','".$_POST['nomp']."')";
				pg_query ($con, $ajout);
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
