<?php include('templates/header.php'); ?>

<?php  
require_once "config.php"; 

$id = @$_GET['id'];
if( $id ) {
	$id = (int) $id;
	// $recette_data = array();
	$sql = "SELECT * FROM RECETTE WHERE ID_RECETTE=$id";
	$result = mysqli_query($conn, $sql);
	$recette_data = $result->fetch_assoc();
	
	
	$i_sql = "SELECT * FROM INGREDIENT";
	$i_results = mysqli_query($conn, $i_sql);
	

	$u_sql = "SELECT * FROM UNITE";
	$u_results = mysqli_query($conn, $u_sql);
	
	
}		
else {
	header("Location: ".base_url());
	exit();
}
?>

<div class="row add-receipe-container">
    <div class="col-md-12">
    	
    	<div class="page-heading mt-4 mb-1">
    		<h2>Modification de la recette</h2>
    	</div>

    	<div id="message"></div>

    	<br />

    	<form action="actions/recipe/update.php" method="POST" id="addRecipe">
		  <div class="form-group">
		    <label for="nom_recette">Nom de la recette</label>
		    <input type="text" class="form-control" id="nom_recette" name="nom_recette" value="<?php echo ( isset($_POST[ 'nom_recette' ]) ) ? $_POST[ 'nom_recette' ] : $recette_data[ 'NOM_RECETTE' ] ?>" />
		  </div>

		  <h4>Modifier les ingrédients</h4>
		  <div class="table-responsive">
			  <table class="table recipe-table">
			  	<thead>
			  		<tr>
			  			<th>Ingredient</th>
			  			<th>Quantité</th>
			  			<th>Unité</th>
			  			<th><button type="button" onclick="addRow()" class="btn btn-success"><i class="fas fa-plus"></i></button></th>
			  		</tr>
			  	</thead>
			  	<tbody>
			  		<?php 

			  		$sql = "SELECT * FROM COMPOSE WHERE ID_RECETTE=$id";
					    if($result = mysqli_query($conn, $sql)){
					        if(mysqli_num_rows($result) > 0){
					           // print_r($result);
					        	$x=1;
					           while($row = mysqli_fetch_array($result)){ ?>
					           	<tr data-row-id="<?php echo $x; ?>" id="row_<?php echo $x; ?>">
						  			<td style="width: 40%">
						  				<select class="form-control" name="select[<?php echo $x; ?>][ingredient_id]" class="select2">
						  					<option></option>
						  					<?php 
						  					if( $i_results ) {
												foreach ( $i_results as $r ) {

													$selected = ( $r[ 'ID_INGREDIENT' ] == $row[ 'ID_INGREDIENT' ] ) ? 'selected="selected"' : '';
													echo "<option value='". $r[ 'ID_INGREDIENT' ] ."' $selected>".$r[ 'NOM_INGREDIENT' ]."</option>";	
												}
											}
						  					?>
						  				</select>
						  			</td>
						  			<td style="width: 10%">
						  				<input type="text" name="select[<?php echo $x; ?>][qty]" class="form-control" value="<?php echo $row[ 'QUANTITE' ] ?>">
						  			</td>
						  			<td style="width: 40%">
						  				<select class="form-control" name="select[<?php echo $x; ?>][unit_id]">
						  					<option></option>
						  					<?php if( $u_results ) {
												foreach ( $u_results as $u ) {
													$selected = ( $u[ 'ID_UNITE' ] == $row[ 'ID_UNITE' ] ) ? 'selected="selected"' : '';

													echo "<option value='". $u[ 'ID_UNITE' ] ."' $selected>".$u[ 'UNITE_MASSE' ]."</option>";	
												}
											} ?>
						  				</select>
						  			</td>
						  			<td style="width: 10%">
						  				<button type="button" onclick="removeRow(<?php echo $x; ?>)" class="btn btn-danger"><i class="fas fa-minus"></i></button>
						  			</td>
						  		</tr>
					           	<?php
					           	$x++;
					           	}
					            mysqli_free_result($result);
					        }
					    }

					    mysqli_close($conn);
					   ?>
					  		
			  	</tbody>
			  </table>
		  </div>
		 	
		 	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
		  	<button type="submit" class="btn btn-primary">Valider les modifications</button>
		  	<a href="<?php echo base_url() . '/recette.php' ?>" class="btn btn-danger">Retour</a>
		</form>
    </div>
</div>   

<script type="text/javascript">

	var base_url = "<?php echo base_url() ?>";

	$('#addRecipe').on( 'submit', function(){
		
		var action = $(this).attr( 'action' );
		var method = $(this).attr( 'method' );

		$.ajax({
			url: action,
			type: method,
			dataType: 'json',
			data: $(this).serialize(),
			success:function( response ) {
				var pos = jQuery('.add-receipe-container').offset().top - 70;
		  		$("html, body").animate({ scrollTop: pos }, 1000);
				$("#message").html( response.message );

			}
		});
		return false;
	});

	// addRow();

	function addRow( id = null )
	{
		var count_row = $('.recipe-table tbody tr:last-child').attr( 'data-row-id' );

		if( count_row >= 1 ) {
			count_row = parseInt( count_row ) + 1;
		}
		else {
			count_row = 1;
		}

		$.ajax({
			url: base_url + '/actions/recipe/fetch_table_row.php',
			type: 'get',
			data: {
				row_id : count_row
			},
			success:function( response ) {
				$('.recipe-table tbody').append( response );
			}
		});
	}

	function removeRow( row_id = null )
	{
		if( row_id ) {
			$( 'table tbody tr#row_'+row_id ).remove();
		}
	}
</script>
<?php include('templates/footer.php'); ?>