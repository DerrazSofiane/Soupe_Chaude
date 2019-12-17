<?php ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); ?>

<?php include('templates/header.php'); ?>

<?php  
// Attempt select query execution
$unite_data = array();
$sql = "SELECT * FROM UNITE";
$unite_data = mysqli_query($conn, $sql);
// Close connection
mysqli_close($conn);
?>

<div class="row add-receipe-container">
    <div class="col-md-12">
    	
    	<div class="page-heading mt-4 mb-1">
    		<h2>Ajouter une recette</h2>
    	</div>

    	<div id="message"></div>

    	<br />

    	<form action="actions/recipe/create.php" method="POST" id="addRecipe">
		  <div class="form-group">
		    <label for="nom_recette">Nom de la recette</label>
		    <input type="text" class="form-control" id="nom_recette" name="nom_recette" />
		  </div>
		
		  <h4>Entrer des ingrédients</h4>
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
			  	</tbody>
			  </table>
		  </div>
		 
		  <button type="submit" class="btn btn-primary">Valider</button>
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
		  		// $("html, body").animate({ scrollTop: pos }, 1000);
				// $("#message").html( response.message );
				window.location = base_url + '/recette.php';

				if( response.success == true ) {
					setTimeout(function(){
						
					}, 2000);
				}
			}
		});
		return false;
	});

	addRow();

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