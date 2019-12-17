<?php session_start(); ?>

<?php include('templates/header.php'); ?>


<div class="row">
    <div class="col-md-12">

        <div class="page-header clearfix mb-3">
          <p></p>
            <h2 class="pull-left">Liste des recettes</h2>
          <p></p>
            <a href="create.php" class="btn btn-success pull-right">Ajouter une recette</a>
        </div>

        <?php 

        if( isset($_SESSION[ 'messages' ]) ): ?>
            <div class="alert alert-<?php echo $_SESSION['success']; ?>">
                <?php 
                    echo $_SESSION[ 'messages' ]; 
                    unset( $_SESSION['messages']);
                ?>
            </div>
        <?php endif; 
            
        // Initialisation de la requête sql
        $sql = "SELECT ID_RECETTE, NOM_RECETTE FROM recette";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){
                
                // Affichage de la liste des recettes
                echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Nom de la recette</th>";
                            echo "<th>Action</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                            echo "<td>" . $row['NOM_RECETTE'] . "</td>";
                            echo "<td>";
                                echo "<a href='read.php?id=". $row['ID_RECETTE'] ."' title='Détails' data-toggle='tooltip'><i class='fas fa-eye'></i>&nbsp;&nbsp;</a>";
                                echo "<a href='update.php?id=". $row['ID_RECETTE'] ."' title='Mettre à jour' data-toggle='tooltip'><i class='fas fa-pencil-alt'></i>&nbsp;&nbsp;</a>";
                                echo "<a href='delete.php?id=". $row['ID_RECETTE'] ."' title='Supprimer' data-toggle='tooltip'><i class='fas fa-trash-alt'></i></a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";
                mysqli_free_result($result);
            } else{
                echo "<p class='lead'><em>Aucune recette n'a été trouvée.</em></p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }

        // Fermeture de la connexion
        mysqli_close($conn);
        ?>
    </div>
</div>   

<?php include('templates/footer.php'); ?>