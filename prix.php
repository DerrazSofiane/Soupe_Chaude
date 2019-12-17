<?php include('templates/header.php'); ?>
<h3 align="center"> Prix des ingrédients (au kg) : </h3>

<table class="table table-striped table-hover">
  <thead class="thead-light">
    <tr>
      <th scope="col">Ingredient</th>
      <th scope="col">Leader Price</th>
      <th scope="col">Rungis</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  // Include config file
  require_once "config.php";

  $sql = "SELECT nom_ingredient, prix_leader, prix_rungis FROM ingredient";
  $result = $conn-> query($sql);
  if ($result->num_rows > 0) {
    // Affiche les données pour chaque ligne
    while($row = $result->fetch_assoc()) {
    $prixleader[] = $row['prix_leader'];
    $prixrungis[] = $row['prix_rungis'];
    echo "<tr><td>" . $row["nom_ingredient"]. "</td><td>" . $row["prix_leader"] . "</td><td>"
    . $row["prix_rungis"]. "</td></tr>";
    }
    echo "</table>";
    } else { echo "0 resultats"; }
    $conn->close(); 
  ?>
  </tbody>
</table>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<figure class="highcharts-figure">
    <div id="container"></div>
</figure>

<!-- Diagramme en barres -->
<script>
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Comparaison des prix'
    },
    subtitle: {
        text: 'Source: www.leaderdrive.fr rungischezvous.com'
    },
    xAxis: {
        categories: [
            'Carotte',
            'Courgette',
            'Ail',
            'Echalote',
            'Poireau',
            'Aubergine',
            'Citron jaune',
            'Pomme de terre',
            'Tomate grappe'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Prix (€)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} €</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'LeaderPrice',
        data: [<?php echo join($prixleader, ',') ?>], color : 'green'

    }, {
        name: 'Rungis',
        data: [<?php echo join($prixrungis, ',') ?>], color : 'orange'

    },
  ]
});
</script>

</body>
</html>