
<?php session_start(); ?>
<?php include('templates/header.php'); ?>
<?php  
	$id = @$_GET['id'];
	if( $id ) {
		if( !empty($_POST) ) {

			// sql - suppression de recette
			$state = false;
			$compose_state = false;

			$sql = "DELETE FROM COMPOSE WHERE ID_RECETTE=$id";
			if ( $conn->query($sql)  === TRUE) {
				$compose_state = true;
			} else {
				$compose_state = false;
			}

			if( $compose_state == true )  {
				$r_sql = "DELETE FROM RECETTE WHERE ID_RECETTE=$id";
				if ($conn->query($r_sql) === TRUE) {
					$state = true;
				} else {
					$state = false;
				}	

			}

				
			if( $state = true && $compose_state == true ) {
				$_SESSION['success'] = 'success'; 
				$_SESSION[ 'messages' ] = 'Recette supprimée avec succès';
				header("Location: ".base_url().'/recette.php');
				exit();
			}
			else {
				$_SESSION['success'] = 'danger'; 
				$_SESSION[ 'messages' ] = 'Error while removing';
				flash()->message('danger', "Error deleting record: " . $conn->error);
				header("Location: ".base_url().'/recette.php');
				exit();
			}
			
			$conn->close();

		}
	}
	else {
		header("Location: ".base_url().'/recette.php');
		exit();
	}
?>
	<div class="row add-receipe-container">
    <div class="col-md-12">
    	
    	<div class="page-heading mt-4 mb-1">
    		<h2>Supprimer une recette</h2>
    	</div>

    	<p>Voulez-vous vraiment la supprimer ?</p>

    	<form action="" method="POST" id="addRecipe">
    		<input type="hidden" name="recipe_id" id="recipe_id" value="<?php echo $_GET[ 'id' ]; ?>">
		  	<button type="submit" class="btn btn-primary">Oui</button>
		  	<a href="<?php echo base_url() . '/recette.php'; ?>" class="btn btn-danger">Non</a>
		</form>
    </div>
</div>  
<?php include('templates/footer.php'); ?>