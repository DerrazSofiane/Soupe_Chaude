<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM recette WHERE ID_RECETTE = ?";

    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $id = $row["ID_RECETTE"];
                $name = $row["NOM_RECETTE"];
                $price = $row["PRIX"];
                $valeurcalr = $row["VALEURCALR"];
                $lipide = $row["LIPIDER"];
                $glucide = $row["GLUCIDER"];
                $proteine = $row["PROTEINER"];
                // Calcul pour mettre les résultat en % sur une base de 100
                $sum = $lipide+$glucide+$proteine;
                $sumlip= $lipide*100/$sum;
                $sumglu= $glucide*100/$sum;
                $sumprot= $proteine*100/$sum;
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    

} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: eror.php");
    exit();
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Soupe Chaude</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #d9d9d9;">
  <a class="navbar-brand" onclick="window.location.href='index.php'">Soupe Chaude</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" onclick="window.location.href='index.php'">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" onclick="window.location.href='prix.php'">Ingredient price</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" onclick="window.location.href='recette.php'">Recipes</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
<h3 align="center"><?php echo 'Recipe detail '; echo $name ?><h3>

<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<p></p>

<!-- Table for detailed information of the ingredients of the recipe -->
<h3 align="center"> Ingredient list: </h3>

<table class="table table-striped table-hover">
  <thead class="thead-light">
  </thead>
  <tbody>
  <?php
   if (isset($_GET["id"])) {
    $sql = "SELECT NOM_INGREDIENT, QUANTITE, UNITE_MASSE, VALEURCAL, LIPIDE, GLUCIDE, PROTEINE, PRIX_RUNGIS, PRIX_LEADER FROM ingredient INNER JOIN compose ON ingredient.ID_INGREDIENT = compose.ID_INGREDIENT INNER JOIN unite ON unite.ID_UNITE = compose.ID_UNITE WHERE ID_RECETTE=".$_GET['id'];
    $result = mysqli_query($conn, $sql);
    // var_dump($result);
    if(mysqli_num_rows($result) > 0){
      require_once "config.php";
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

      // Initialization of the variables for the total values present in the second table
      // Outside the while loop otherwise the incrementation of the values is not taken into account (see line 197)
      $sumcal = $sumlip = $sumglu = $sumprot = $sumrungis = $sumleader = 0;

      // Path of each value of the query
      while($row = mysqli_fetch_assoc($result)){
        
        // Calculation of the value on a reference of the value for 100g, according to the quantity
        $sum1 = ($row['VALEURCAL']*$row['QUANTITE'])/100;
        $sum2 = ($row['LIPIDE']*$row['QUANTITE'])/100;
        $sum3 = ($row['GLUCIDE']*$row['QUANTITE'])/100;
        $sum4 = ($row['PROTEINE']*$row['QUANTITE'])/100;

        // Conversion from kg to g for the calculation of values
        if($row['UNITE_MASSE'] == 'kg'){
          $sum1 = ($row['VALEURCAL']*1000*$row['QUANTITE'])/100;
          $sum2 = ($row['LIPIDE']*1000*$row['QUANTITE'])/100;
          $sum3 = ($row['GLUCIDE']*1000*$row['QUANTITE'])/100;
          $sum4 = ($row['PROTEINE']*1000*$row['QUANTITE'])/100;
        }

        // Calculation of the price on a value of the price per kilo, according to the quantity
        $total1 = $row['PRIX_RUNGIS']*$row['QUANTITE'];
        $total2 = $row['PRIX_LEADER']*$row['QUANTITE'];

        // Conversion from g to kg for price calculation
        if($row['UNITE_MASSE'] == 'g'){
          $total1 = ($row['PRIX_RUNGIS']*$row['QUANTITE']/1000);
          $total2 = ($row['PRIX_LEADER']*$row['QUANTITE']/1000);
        }

        // Calculation of total values
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
      
    }
    else {
      echo "<p class='lead'><em>No records were found.</em></p>";
    }
  } else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
  }
  // Close connection
  mysqli_close($conn);
?>
  </tbody>
</table>

<!-- Table for total value -->
<h3 align="center"> Total values for the recipe: </h3>
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
      {y: <?php echo $sumlip ?>, label: "Lipide"},
      {y: <?php echo $sumglu ?>, label: "Glucide"},
      {y: <?php echo $sumprot ?>, label: "Proteine"}
    ]
  }]
});
chart.render();

}
</script>
</body>
</html>