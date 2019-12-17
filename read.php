<?php include('templates/header.php'); ?>

<?php
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
  // Include config file
  require_once "config.php";
  
  // Initialisation de la requête sql
  $sql = "SELECT * FROM recette WHERE ID_RECETTE = ?";

  if($stmt = mysqli_prepare($conn, $sql)){

      mysqli_stmt_bind_param($stmt, "i", $param_id);
      
      // Récupération de l'id
      $param_id = trim($_GET["id"]);
      
      if(mysqli_stmt_execute($stmt)){
          $result = mysqli_stmt_get_result($stmt);
  
          if(mysqli_num_rows($result) == 1){
              // Récupération des résultats. Comme le résultat ne contient qu'une seule ligne, il n'y pas de besoins de boucle while
              $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

              $name = $row["NOM_RECETTE"];
          } else{
              // L'URL ne contient pas d'id existant. Redirige vers la page d'erreur
              header("location: error.php");
              exit();
          }
          
      } else{
          echo "Oops! Something went wrong. Please try again later.";
      }
  }
   
  mysqli_stmt_close($stmt);
  

} else{
  // L'URL ne contient pas d'id existant. Redirige vers la page d'erreur
  header("location: eror.php");
  exit();
}
?>

<h3 align="center"><?php echo 'Détails de la recette '; echo $name ?><h3>

<h4 align="center"> Liste des ingrédients : </h4>

<!-- Table pour les informations détaillées des ingrédients de la recette -->
<table class="table table-striped table-hover">
  <thead class="thead-light">
  </thead>
  <tbody>
  <?php
  require_once "config.php";
   if (isset($_GET["id"])) {
    $sql = "SELECT NOM_INGREDIENT, QUANTITE, UNITE_MASSE, VALEURCAL, LIPIDE, GLUCIDE, PROTEINE, PRIX_RUNGIS, PRIX_LEADER FROM ingredient INNER JOIN compose ON ingredient.ID_INGREDIENT = compose.ID_INGREDIENT INNER JOIN unite ON unite.ID_UNITE = compose.ID_UNITE WHERE ID_RECETTE=".$_GET['id'];
    $result = mysqli_query($conn, $sql);
    // var_dump($result);
    if(mysqli_num_rows($result) > 0){
      echo "<table class='table table-bordered table-striped'>";
      echo "<thead>";
          echo "<tr>";
            echo "<th>Ingrédient</th>";
            echo "<th>Quantité</th>";
            echo "<th>Unité</th>";
            echo "<th>Valeur calorique (kcal)</th>";
            echo "<th>Lipide</th>";
            echo "<th>Glucide</th>";
            echo "<th>Proteine</th>";
            echo "<th>Prix Rungis(€)</th>";
            echo "<th>Prix Leader(€)</th>";
          echo "</tr>";
      echo "</thead>";
      echo "<tbody>";

      // Initialisation des variables pour le total des valeurs présent dans la seconde table
      // En dehors de la boucle while sinon l'incrémentation des valeurs n'est pas prise en compte (voir ligne 113)
      $sumcal = $sumlip = $sumglu = $sumprot = $sumrungis = $sumleader = 0;

      // Boucle pour parcourir chaque valeur de la requête
      while($row = mysqli_fetch_assoc($result)){
        
        // Calcul de la valeur sur une référence de la valeur sur 100g et selon la quantité
        $sum1 = ($row['VALEURCAL']*$row['QUANTITE'])/100;
        $sum2 = ($row['LIPIDE']*$row['QUANTITE'])/100;
        $sum3 = ($row['GLUCIDE']*$row['QUANTITE'])/100;
        $sum4 = ($row['PROTEINE']*$row['QUANTITE'])/100;

        // Conversion de kg en g pour le calcul des valeurs
        if($row['UNITE_MASSE'] == 'kg'){
          $sum1 = ($row['VALEURCAL']*1000*$row['QUANTITE'])/100;
          $sum2 = ($row['LIPIDE']*1000*$row['QUANTITE'])/100;
          $sum3 = ($row['GLUCIDE']*1000*$row['QUANTITE'])/100;
          $sum4 = ($row['PROTEINE']*1000*$row['QUANTITE'])/100;
        }

        // Calcul du prix sur une référence du prix au kilo et selon la quantité
        $total1 = $row['PRIX_RUNGIS']*$row['QUANTITE'];
        $total2 = $row['PRIX_LEADER']*$row['QUANTITE'];

        // Conversion de g en kg pour le calcul du prix
        if($row['UNITE_MASSE'] == 'g'){
          $total1 = ($row['PRIX_RUNGIS']*$row['QUANTITE']/1000);
          $total2 = ($row['PRIX_LEADER']*$row['QUANTITE']/1000);
        }

        // Calcul des valeurs totales
        $sumcal += $sum1;
        $sumlip += $sum2;
        $sumglu += $sum3;
        $sumprot += $sum4;
        $sumrungis += $total1;
        $sumleader += $total2;

        echo "<tr>";
        echo "<td>" . $row['NOM_INGREDIENT'] . "</td>";
        echo "<td>" . $row['QUANTITE'] . "</td>";
        echo "<td>" . $row['UNITE_MASSE'] . "</td>";
        echo "<td>" . $sum1 . "</td>";
        echo "<td>" . $sum2 . "</td>";
        echo "<td>" . $sum3 . "</td>";
        echo "<td>" . $sum4 . "</td>";
        echo "<td>" . $total1 . "</td>";
        echo "<td>" . $total2 . "</td>";
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";

      // Calcul pour le diagramme, résultat en pourcentage (sur 100%)
      $sum = $sumlip+$sumglu+$sumprot;
      $sumlipr= $sumlip*100/$sum;
      $sumglur= $sumglu*100/$sum;
      $sumprotr= $sumprot*100/$sum;
      
    }
    else {
      echo "<p class='lead'><em>No records were found.</em></p>";
    }
  } else{
    // L'URL ne contient pas d'id existant. Redirige vers la page d'erreur
    header("location: error.php");
    exit();
  }
  // Fermeture de la connexion
  mysqli_close($conn);
?>
  </tbody>
</table>

<!-- Table des valeurs totales -->
<h4 align="center"> Total des valeurs pour la recette : </h4>
<p></p>

<table class="table table-striped table-hover">
  <thead class="thead-light">
    <tr>
      <th scope="col">Valeur calorique</th>
      <th scope="col">Lipide</th>
      <th scope="col">Glucide</th>
      <th scope="col">Proteine</th>
      <th scope="col">Prix Rungis(€)</th>
      <th scope="col">Prix Leader(€)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
    <?php
      echo "<td>$sumcal</td>";
      echo "<td>$sumlip</td>";
      echo "<td>$sumglu</td>";
      echo "<td>$sumprot</td>";
      echo "<td>$sumrungis</td>";
      echo "<td>$sumleader</td>";
    ?>
    </tr>
  </tbody>
</table>

<p></p>

<!-- Diagramme circulaire -->
<script>
window.onload = function() {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	data: [{
		type: "pie",
		startAngle: 240,
		yValueFormatString: "##0.00\"%\"",
		indexLabel: "{label} {y}",
		dataPoints: [
			{y: <?php echo $sumlipr ?>, label: "Lipide"},
			{y: <?php echo $sumglur ?>, label: "Glucide"},
			{y: <?php echo $sumprotr ?>, label: "Proteine"}
		]
	}]
});
chart.render();

}
</script>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

</body>
</html>