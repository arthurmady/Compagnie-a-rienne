<!DOCTYPE html>
<!--http://serveur-etu.polytech-lille.fr/~amady/-->
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Gestion d'une compagnie aérienne</title>
  	<style>
	#coteAcote {
      display:inline;}
	</style>
  </head>
  
  <body>
    <h1><center> Gestion d'une compagnie aérienne </center></h1><br/>
    
     <!--INFORMATION SUR LES AVIONS-->
    <h2> Consultation d'un avion </h2>
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
	
	//Fonction pour afficher des avions dans un tableau
	function afficherAvion($resultat)
	{
		if(!$resultat){
		echo"Information introuvable";
		}
		else{
		echo"<br/><table border=1>";
		echo "<tr><td>Référence</td> <td>Type</td><td>Date de mise en service</td><td>Nombre de sièges</td></tr>";
		while ($ligneavion=pg_fetch_array($resultat)){
			echo "<tr><td>". $ligneavion['refa'] ."</td> <td>". $ligneavion['nomt'] ."</td><td>". $ligneavion['dateservice'] ."</td><td>". $ligneavion['nbresiege'] ."</td></tr>";
		}
		echo"</table></br>";
		}
	}
	
	//Fonction pour afficher des vols dans un tableau
	function afficherVol($resultat)
	{
		if(!$resultat){
		echo"Information introuvable";
		}
		else{
		echo"<br/><table border=1>";
		echo "<tr><td>Référence de l'avion</td><td>Référence du vol</td><td>Ville de départ</td><td>Ville d'arrivée</td><td>Date de départ</td><td>Date d'arrivée</td><td>Horaire de départ</td><td>Horaire d'arrivée</td><td>Prénom et nom du responsable du vol</td><td>Membres de l'équipage</td></tr>";
		while ($lignevol=pg_fetch_array($resultat)){
			$membre="select refv,membre_équipage.numposte,nome,prenome,nomf from membre_équipage natural join employé natural join fonction where refv ='".$lignevol[0]."' and employé.numposte=membre_équipage.numposte and employé.numf=fonction.numf order by refv";
			$resmembre=pg_query($membre);
			
			echo "<tr><td>". $lignevol['refa'] ."</td> <td>". $lignevol['refv'] ."</td><td>". $lignevol['villedepart'] ."</td><td>". $lignevol['villearrivee'] ."</td><td>". $lignevol['datedepart'] ."</td><td>". $lignevol['datearrivee'] ."</td><td>". $lignevol['horairedepart'] ."</td><td>". $lignevol['horairearrivee'] ."</td><td>". $lignevol['prenome'] ." ". $lignevol['nome'] ."</td>";
			echo"<td>";
			//Afficher les membres de l'équipage du vol
			while ($lignemembre=pg_fetch_array($resmembre)){
				echo $lignemembre['prenome']." ".$lignemembre['nome']." | ".$lignemembre['nomf']."<br/>";
			}
			echo"</td></tr>";
		}
		echo"</table></br>";
		}
	}
	
	//Fonction pour afficher des passagers dans un tableau
	function afficherPassager($resultat){
		if(!$resultat){
		echo"Information introuvable";
		}
		else{
		echo"<br/><table border=1>";
		echo "<tr><td>Passager</td><td>Date d'émission du billet</td><td>Numéro de la place</td><td>Employé ayant validé le billet</td></tr>";
		while ($lignepassager=pg_fetch_array($resultat)){
			echo "<tr><td>". $lignepassager['prenomp'] ." ". $lignepassager['nomp'] ."</td><td>". $lignepassager['datee'] ."</td><td>". $lignepassager['numplace'] ."</td><td>". $lignepassager['prenome'] ." ". $lignepassager['nome'] ."</td></tr>";
		}
		echo"</table></br>";
		}
	}
	
	//Fonction pour afficher des billets dans un tableau
	function afficherBillet($resultat){
		if(!$resultat){
		echo"Information introuvable";
		exit;
		}
		else{
		echo"<br/><table border=1>";
		echo "<tr><td>Référence du billet</td><td>Référence du vol</td><td>Passager</td><td>Employé ayant validé le billet</td><td>Date d'émission du billet</td><td>Numéro de la place</td></tr>";
		while ($lignebillet=pg_fetch_array($resultat)){
			echo "<tr><td>". $lignebillet['refb'] ."</td><td>". $lignebillet['refv'] ."</td><td>". $lignebillet['prenomp'] ." ". $lignebillet['nomp'] ."</td><td>". $lignebillet['prenome'] ." ". $lignebillet['nome'] ."</td><td>". $lignebillet['datee'] ."</td><td>". $lignebillet['numplace'] ."</td></tr>";
		}
		echo"</table></br>";
		}
	}
	
	//Fonction pour afficher des employés dans un tableau
	function afficherPersonnel($resultat){
		if(!$resultat){
			echo"Information introuvable";
		}
		else{
			echo"<br/><table border=1>";
			echo "<tr><td>Numéro de poste</td><td>Nom </td><td> prenom</td><td> Fonction occupée</td></tr>";
			while ($lignepersonnel=pg_fetch_array($resultat)){
				echo "<tr><td>". $lignepersonnel['numposte'] ."</td><td>". $lignepersonnel['nome'] ."</td><td>". $lignepersonnel['prenome'] ."</td><td>". $lignepersonnel['nomf'] ."</td></tr>";
			}
			echo"</table></br>";
		}
	}
	
	//Fonction pour afficher des maintenances dans un tableau
	function afficherMaintenance($resultat){
		if(!$resultat){
			echo"Information introuvable";
		}
		else{
			echo"<br/><table border=1>";
			echo "<tr><td>Référence de la maintenance</td><td> Référence de l'avion </td><td>Prénom et nom du responsable </td><td> Date de la maintenance</td><td> Equipe de maintenance</td></tr>";
			while ($lignemaintenance=pg_fetch_array($resultat)){
				$equipe="select refm,equipe_maintenance.numposte,nome,prenome,nomf from equipe_maintenance natural join employé natural join fonction where refm ='".$lignemaintenance[0]."' and employé.numposte=equipe_maintenance.numposte and employé.numf=fonction.numf order by refm";
				$resequipe=pg_query($equipe);
			
				echo "<tr><td>". $lignemaintenance['refm'] ."</td><td>". $lignemaintenance['refa'] ."</td><td>". $lignemaintenance['prenome'] ." ". $lignemaintenance['nome'] ."</td><td>". $lignemaintenance['datem'] ."</td>";
				echo"<td>";
				//Afficher les membres de la maintenance
				while ($ligneEquipe=pg_fetch_array($resequipe)){
					echo $ligneEquipe['prenome']." ".$ligneEquipe['nome']." | ".$ligneEquipe['nomf']."<br/>";
				}
				echo"</td></tr>";
			}
			echo"</table></br>";
		}
	}
	?>
	
	<!--INFORMATIONS SUR LES AVIONS-->
	<form action='index.php' method=POST>
	<h3>Rechercher par : </h3>
    	   Référence : <input type='text' name='ref' />
    	   Type : <input type='text' name='type' />
    	   Nombre de siège : <input type='text' name='nbresiege' />
   	    <input type='hidden' name='valideravion1'>
    	<input type='submit' value='Rechercher' />
    </form><br/>
    
    	<!--Bouton pour afficher tous les avions-->
    <form action='index.php' method=POST>
    	<input type='hidden' name='valideravion2'>
        <input type='submit' value="Afficher tous les avions" />
    </form>
    
    <?php
    //Si c'est une recherche par catégorie, afficher les données dans un tableau
    if(isset($_POST['valideravion1'])){
    	if($_POST['ref'] != ''){
    		$avion="select refa, nomt, dateservice, nbresiege from type natural join avion where refa='".$_POST['ref']."' order by refa;";
			$resavion=pg_query($avion);
			echo afficherAvion($resavion);
		}
		elseif($_POST['type'] != ''){
			$avion="select refa, nomt, dateservice, nbresiege from type natural join avion where nomt='".$_POST['type']."' order by refa;";
			$resavion=pg_query($avion);
			echo afficherAvion($resavion);
		}
		elseif($_POST['nbresiege'] != ''){
			$avion="select refa, nomt, dateservice, nbresiege from type natural join avion where nbresiege='".$_POST['nbresiege']."' order by refa;";
			$resavion=pg_query($avion);
			echo afficherAvion($resavion);
		}
		else{
			echo"Veuillez saisir une information";
		}
	}
	
	    //Si c'est "afficher tous les avions", afficher tous les avions dans un tableau
	if(isset($_POST['valideravion2'])){
		$avions="select refa, nomt, dateservice, nbresiege from type natural join avion";
		$resavions=pg_query($avions);
		echo afficherAvion($resavions);
	}
	?>
    
    <!--INFORMATION SUR LES VOLS-->
    <br/><br/><h2> Consultation d'un vol </h2>
    <div id="section_vol">
    <form action='index.php' method=POST>
		<h3>Rechercher par : </h3>
    	Ville de départ : <input type='text' name='depart' />
   		Ville d'arrivée : <input type='text' name='arrivee' />
   		Date de départ : <input type='date' name='dateDepart' />
        Date d'arrivée : <input type='date' name='dateArrivee' />
        Référence du vol : <input type='text' name='refV' />
        <input type='hidden' name='validervol1'>
        <input type='submit' value='Rechercher' />
    </form><br/></div>
    
    
    <form action='#section_vol' id='coteAcote' method=POST>
    	<input type='hidden' name='validervol2'>
        <input type='submit' value="Afficher tous les vols" />
    </form>
    
    <form action='modifierVol.php' id='coteAcote' method=POST>
    	<input type='submit' value="Modifier les horaires d'un vol" />
    </form>
    
    <form action='nouveauVol.php' id='coteAcote' method=POST>
    	<input type='submit' value="Organiser un nouveau vol" />
    </form>
    
    <form action='supprimerVol.php' id='coteAcote' method=POST>
    	<input type='submit' value="Supprimer un vol" />
    </form><br>
    
    <?php
        //Si c'est une recherche par catégorie, afficher les données dans un tableau
    if(isset($_POST['validervol1'])){
    	if($_POST['depart'] != ''){
    		$vol="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee,nome,prenome from vol natural join employé where villedepart ='".$_POST['depart']."' order by refv";
			$resvol=pg_query($vol);
			echo afficherVol($resvol);
		}
		elseif($_POST['arrivee'] != ''){
			$vol="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee,nome,prenome from vol natural join employé where villearrivee ='".$_POST['arrivee']."' order by refv";
			$resvol=pg_query($vol);
			echo afficherVol($resvol);
		}
		elseif($_POST['dateDepart'] != ''){
			$vol="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee,nome,prenome from vol natural join employé where datedepart ='".$_POST['dateDepart']."' order by refv";
			$resvol=pg_query($vol);
			echo afficherVol($resvol);
		}
		elseif($_POST['dateArrivee'] != ''){
			$vol="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee,nome,prenome from vol natural join employé where datearrivee ='".$_POST['dateArrivee']."' order by refv";
			$resvol=pg_query($vol);
			echo afficherVol($resvol);
		}
		elseif($_POST['refV'] != ''){
			$vol="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee,nome,prenome from vol natural join employé where refv ='".$_POST['refV']."' order by refv";
			$resvol=pg_query($vol);
			echo afficherVol($resvol);
		}
		else{
			echo"Veuillez saisir une information";
		}
	}
	
	if(isset($_POST['validervol2'])){
	 	$vols="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee,nome,prenome from vol natural join employé order by refv";
		$resvols=pg_query($vols);
		echo afficherVol($resvols);
	}
	?>
	
    <!-- INFORMATION SUR LES PASSAGERS D'UN VOL-->
    <br/><br/><h2> Liste des passagers d'un vol</h2>
    <div id="section_passager">
    <form action='#section_passager' id='coteAcote' method=POST>
    	<input type='hidden' name='validerpassager1'>
        <input type='submit' value="Sélectionner un vol" />
    </form><br/></div>
    
    <?php
    //Afficher la liste de vols
	if(isset($_POST['validerpassager1'])){
		$vol="select refv,refa,villedepart,villearrivee,datedepart,datearrivee,horairedepart,horairearrivee from vol order by refv";
		$resvol=pg_query($vol);
		
		echo "<h3>Sélectionnez un vol pour pouvoir afficher ses passagers </h3>";
		echo "<form action='#section_passager' method=POST>
			<br/><table border=1>
			<tr><td>Référence de l'avion</td> <td>Référence du vol</td><td>Ville de départ</td><td>Ville d'arrivée</td><td>Date de départ</td><td>Date d'arrivée</td><td>Horaire de départ</td><td>Horaire d'arrivée</td><td>Sélectionnez un vol</td></tr>";
		
		while($lignevol=pg_fetch_array($resvol)){
				echo "<tr><td>". $lignevol['refa'] ."</td> <td>". $lignevol['refv'] ."</td><td>". $lignevol['villedepart'] ."</td><td>". $lignevol['villearrivee'] ."</td><td>". $lignevol['datedepart'] ."</td><td>". $lignevol['datearrivee'] ."</td><td>". $lignevol['horairedepart'] ."</td><td>". $lignevol['horairearrivee'] ."</td><td><input type='radio' name='choixVol' value='".$lignevol['refv']."' /></td></tr>";
    	}
    	
    	echo"</table></br>
    	<input type='hidden' name='validerChoixVol'>
        <input type='submit' value='Valider mon choix' />
    	</form>";
	}
	
	//Si le vol est sélectionné, afficher les passagers du vol dans un tableau
	if(isset($_POST['validerChoixVol'])){
		$passager="select prenomp, nomp,datee,numplace, prenome,nome, nomf from billet join vol on billet.refv=vol.refv join employé on billet.numposte=employé.numposte natural join fonction;";
		$respassager=pg_query($passager);
		echo afficherPassager($respassager);
	}
	?>
    	
    <!-- INFORMATION SUR LES BILLETS-->
    <br/><h2> Consultation d'un billet</h2>
    <div id="section_billet">
    <form action='#section_billet' method=POST>
		<h3>Rechercher par : </h3>
		Ville de départ du vol : <input type='text' name='depart1' />
   		Ville d'arrivée du vol : <input type='text' name='arrivee1' />
   		Date de départ du vol : <input type='date' name='dateDepart1' />
        Date d'arrivée du vol : <input type='date' name='dateArrivee1' />
        Référence du vol : <input type='text' name='refV1' />
  		<input type='hidden' name='validerbillet1'>
  		<input type='submit' value='Rechercher' />
     </form><br/></div>
     
     <form action='ajouterBillet.php' id='coteAcote' method=POST>
        <input type='submit' value="Ajouter un billet" />
     </form>
     
     <form action='annulerBillet.php' id='coteAcote' method=POST>
        <input type='submit' value="Annuler un billet" />
    </form><br/>
    
    <?php
        //Si c'est une recherche par catégorie, afficher les données dans un tableau
    if(isset($_POST['validerbillet1'])){
    	if($_POST['depart1'] != ''){
    		$billet="select refb,billet.refv,nomp,prenomp,numplace,datee,prenome,nome from billet join vol on billet.refv=vol.refv join employé on billet.numposte=employé.numposte where vol.villedepart='".$_POST['depart1']."';";
			$resbillet=pg_query($billet);
			echo afficherBillet($resbillet);
		}
		elseif($_POST['arrivee1'] != ''){
		 $billet="select refb,billet.refv,nomp,prenomp,numplace,datee,billet.numposte from billet join vol on billet.refv=vol.refv  where vol.villearrivee='".$_POST['arrivee1']."';";
			$resbillet=pg_query($billet);
			echo afficherBillet($resbillet);
		}
		elseif($_POST['dateDepart1'] != ''){
		 $billet="select refb,billet.refv,nomp,prenomp,numplace,datee,billet.numposte from billet join vol on billet.refv=vol.refv  where vol.datedepart='".$_POST['dateDepart1']."';";
			$resbillet=pg_query($billet);
			echo afficherBillet($resbillet);
		}
		elseif($_POST['dateArrivee1'] != ''){
		 $billet="select refb,billet.refv,nomp,prenomp,numplace,datee,billet.numposte from billet join vol on billet.refv=vol.refv  where vol.datearrivee='".$_POST['dateArrivee1']."';";
			$resbillet=pg_query($billet);
			echo afficherBillet($resbillet);
		}
		elseif($_POST['refV1'] != ''){
		 $billet="select refb,billet.refv,nomp,prenomp,numplace,datee,prenome,nome from billet join vol on billet.refv=vol.refv join employé on billet.numposte=employé.numposte where vol.refv='".$_POST['refV1']."';";
			$resbillet=pg_query($billet);
			echo afficherBillet($resbillet);
		}
		else{
			echo"Veuillez saisir une information";
		}
	}
	?>
    
     <!-- INFORMATION SUR LE PERSONNEL-->
     <br/><br/><h2> Consultation du personnel</h2>
     <div id="section_personnel">
     <form action='#section_personnel' method=POST>
	 	<h3>Rechercher un employé par : </h3>
    	  Numéro du poste : <input type='int' name='numPoste' />
  		  Nom : <input type='text' name='nomE' />
  	      Prénom : <input type='text' name='prenomE' />
  	      Fonction : <input type='text' name='fonction' />
  	      <input type='hidden' name='valideremploye1'>
  		  <input type='submit' value='Rechercher' />
    </form><br/></div>
    
    <form action='#section_personnel' id='coteAcote' method=POST>
    	<input type='hidden' name='valideremploye2'>
        <input type='submit' value="Afficher tous les employés" />
    </form>
    
    <form action='ajouterEmployé.php' id='coteAcote' method=POST>
           <input type='submit' value="Ajouter un employé" />
    </form>
    
    <form action='supprimerEmployé.php' id='coteAcote' method=POST>
        <input type='submit' value="Supprimer un employé" />
    </form><br/>
    
     <?php
         //Si c'est une recherche par catégorie, afficher les données dans un tableau
    if(isset($_POST['valideremploye1'])){
    	if($_POST['numPoste'] != ''){
    		$personnel="select numposte, nome , prenome, nomf from employé natural join fonction where numposte=".$_POST['numPoste'].";";
			$respersonnel=pg_query($personnel);
			echo afficherPersonnel($respersonnel);
		}
		elseif($_POST['nomE'] != ''){
		$personnel="select numposte, nome , prenome, nomf from employé natural join fonction where nome='".$_POST['nomE']."';";
			$respersonnel=pg_query($personnel);
			echo afficherPersonnel($respersonnel);
		 
		}
		elseif($_POST['prenomE'] != ''){
		$personnel="select numposte, nome , prenome, nomf from employé natural join fonction where prenome='".$_POST['prenomE']."';";
			$respersonnel=pg_query($personnel);
			echo afficherPersonnel($respersonnel);
		 
		}
		elseif($_POST['fonction'] != ''){
		 $personnel="select numposte, nome , prenome, nomf from employé natural join fonction where nomf='".$_POST['fonction']."';";
			$respersonnel=pg_query($personnel);
			echo afficherPersonnel($respersonnel);
		}	
		else{
			echo"Veuillez saisir une information";
		}
	}
	
	 if(isset($_POST['valideremploye2'])){
	 	$employes="select numposte, prenome,nome,nomf from employé natural join fonction order by numposte";
		$resemployes=pg_query($employes);
		echo afficherPersonnel($resemployes);
	 }
	?>
    
    <!-- INFORMATION SUR LES MAINTENANCES-->
     <div id="section_maintenance">
     <br/><br/><h2> Consultation d'une maintenance</h2>
     <form action='#section_maintenance' method=POST>
     	<h3>Rechercher par : </h3>
	 	Date de la maintenance : <input type='date' name='dateM' />
  		Référence de la maintenance : <input type='int' name='refM' />
  		Référence de l'avion : <input type='int' name='refA' /><br/><br/>
  		<label for='nameresp'>Prénom et nom du responsable : </label><br/>
  		Prénom : <input type='text' name='prenomresp' />
  		Nom : <input type='text' name='nomresp' />
  		<input type='hidden' name='validermaintenance1'>
  		<input type='submit' value='Rechercher' />
    </form><br/></div>
    
    <form action='#section_maintenance' id='coteAcote' method=POST>
    <input type='hidden' name='validermaintenance2'>
        <input type='submit' value="Afficher toutes les maintenances" />
    </form>
    
    <form action='ajouterMaintenance.php' id='coteAcote' method=POST>
        <input type='submit' value="Ajouter une maintenance" />
    </form>
    
    <form action='supprimerMaintenance.php' id='coteAcote' method=POST>
        <input type='submit' value="Supprimer une maintenance" />
    </form><br/><br/>
    
    <form action='#section_maintenance' id='coteAcote' method=POST>
    <?php
    	echo"<input type='hidden' name='validermaintenance3'>
           <input type='submit' value='Sélectionner un avion' />";
    ?></form><br/>
    
    <?php
    //Selectionner un avion pour pouvoir afficher les maintenances
	if(isset($_POST['validermaintenance3'])){
		$avions="select refa, nomt, dateservice, nbresiege from type natural join avion";
		$resavions=pg_query($avions);
		echo "<h3>Sélectionnez un avion pour pouvoir afficher ses maintenances </h3>";
		echo "<form action='#section_maintenance' method=POST>
			<table border=1><tr><td>Référence</td> <td>Type</td><td>Date de mise en service</td><td>Nombre de sièges</td><td>Sélectionner un avion</td></tr>";
		while ($ligneavion=pg_fetch_array($resavions)){
			echo "<tr><td>". $ligneavion['refa'] ."</td> <td>". $ligneavion['nomt'] ."</td><td>". $ligneavion['dateservice'] ."</td><td>". $ligneavion['nbresiege'] ."</td><td><input type='radio' name='choixAvion' value='".$ligneavion['refa']."' /></td></tr>";
		}
		echo"</table></br>
    	<input type='hidden' name='validerChoixAvion'>
        <input type='submit' value='Valider mon choix' />
    	</form>";
	}
	
	//Si un avion est selectionné, afficher les maintenances
	if(isset($_POST['validerChoixAvion'])){
		$maintenance="select refm,refa,numposte,datem,prenome,nome from maintenance natural join employé where refa='".$_POST['choixAvion']."'";
		$resmaintenance=pg_query($maintenance);
		echo afficherMaintenance($resmaintenance);
	}
	
	    //Si c'est une recherche par catégorie, afficher les données dans un tableau
    if(isset($_POST['validermaintenance1'])){
    	if($_POST['dateM'] != ''){
			$maintenance="select refm,refa,numposte,datem,prenome,nome from maintenance natural join employé where datem='".$_POST['dateM']."'";
			$resmaintenance=pg_query($maintenance);
			echo afficherMaintenance($resmaintenance);
		}
		elseif($_POST['refM'] != ''){			
			$maintenance="select refm,refa,numposte,datem,prenome,nome from maintenance natural join employé where refm='".$_POST['refM']."'";
			$resmaintenance=pg_query($maintenance);
			echo afficherMaintenance($resmaintenance);
		}	
		elseif($_POST['refA'] != ''){
			$maintenance="select refm,refa,numposte,datem,prenome,nome from maintenance natural join employé where refa='".$_POST['refA']."'";
			$resmaintenance=pg_query($maintenance);
			echo afficherMaintenance($resmaintenance);
		}
		elseif($_POST['prenomresp'] != '' && $_POST['nomresp'] != ''){
			$maintenance="select refm,refa,numposte,datem,prenome,nome from maintenance natural join employé where nome='".$_POST['nomresp']."' and prenome='".$_POST['prenomresp']."'";
			$resmaintenance=pg_query($maintenance);
			echo afficherMaintenance($resmaintenance);
		}	 
		else{
			echo"Veuillez saisir une information";
		}
	}
	
	    //Si "afficher toutes les maintenances", afficher maintenances dans un tableau
	if(isset($_POST['validermaintenance2'])){
	 	$maintenance="select refm,refa,numposte,datem,prenome,nome from maintenance natural join employé";
			$resmaintenance=pg_query($maintenance);
			echo afficherMaintenance($resmaintenance);
	}
    ?>
    
  </body>
</html>
