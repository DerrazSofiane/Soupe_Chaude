<?php
session_start();
include( '../../config.php' );

$response = array( 'success' => false, 'message' => array() );
// echo "here";die;
if( isset($_POST['select']) ) {
	var_dump($_POST);
	$nom_recette = $_POST[ 'nom_recette' ];
	// $prix = (float) $_POST[ 'prix' ];


	$sumcal = $sumlip = $sumglu = $sumprot = $sumrungis = $sumleader = 0;
	
	/*calculation for recippe table*/
	if( isset($_POST['select'])) {

		foreach ($_POST[ 'select' ] as $s) {
			$ingredient_id = (int) $s[ 'ingredient_id' ];
			$unit_id = (int) $s[ 'unit_id' ];
			$qty = (int) $s[ 'qty' ];	

			/*ingredients*/
			$sql_ingredient = "SELECT * FROM INGREDIENT WHERE ID_INGREDIENT = $ingredient_id";
			$data_ingredient = $conn->query( $sql_ingredient );
			$data_ingredient = $data_ingredient->fetch_assoc();

			/*unit mass*/
			$sql_unite = "SELECT * FROM UNITE WHERE ID_UNITE = $unit_id";
			$data_unite = $conn->query( $sql_unite );
			$data_unite = $data_unite->fetch_assoc();

			// Calculation of the value on a reference of the value for 100g, according to the quantity
	        $sum1 = ($data_ingredient['VALEURCAL']* $s['qty'])/100;
	        $sum2 = ($data_ingredient['LIPIDE']*$s['qty'])/100;
	        $sum3 = ($data_ingredient['GLUCIDE']*$s['qty'])/100;
	        $sum4 = ($data_ingredient['PROTEINE']*$s['qty'])/100;

	        // Conversion from kg to g for the calculation of values
	        if($data_unite['UNITE_MASSE'] == 'kg'){
	          $sum1 = ($data_ingredient['VALEURCAL']*1000*$s['qty'])/100;
	          $sum2 = ($data_ingredient['LIPIDE']*1000*$s['qty'])/100;
	          $sum3 = ($data_ingredient['GLUCIDE']*1000*$s['qty'])/100;
	          $sum4 = ($data_ingredient['PROTEINE']*1000*$s['qty'])/100;
	        }

	        // Calculation of the price on a value of the price per kilo, according to the quantity
	        $total1 = $data_ingredient['PRIX_RUNGIS']*$s['qty'];
	        $total2 = $data_ingredient['PRIX_LEADER']*$s['qty'];

	        // Conversion from g to kg for price calculation
	        if($data_unite['UNITE_MASSE'] == 'g'){
	          $total1 = ($data_ingredient['PRIX_RUNGIS']*$s['qty']/1000);
	          $total2 = ($data_ingredient['PRIX_LEADER']*$s['qty']/1000);
	        }

	        // Calculation of total values
	        $sumcal += $sum1;
	        $sumlip += $sum2;
	        $sumglu += $sum3;
	        $sumprot += $sum4;
	        $sumrungis += $total1;
	        $sumleader += $total2;

		}
	}


	$sql = "INSERT INTO  RECETTE (NOM_RECETTE, PRIXR_RUNGIS, PRIXR_LEADER) VALUES ('$nom_recette', $sumrungis, $sumleader)";

	$state = false;
	if ($conn->query($sql) === TRUE) {
		$last_id = $conn->insert_id;
		$state = true;
	} else {
		$state = false;
		$response[ 'message' ] = '<div class="alert alert-danger" role="alert">'.$conn->error.'</div>';					
	}

	$recep_id = $conn->insert_id;
	if( $state == true ) {

		if( $_POST[ 'select' ] ) {
			$m_sql .= "";
			foreach ($_POST[ 'select' ] as $s) {
				$ingredient_id = (int) $s[ 'ingredient_id' ];
				$unit_id = (int) $s[ 'unit_id' ];
				$qty = (int) $s[ 'qty' ];

				// calculation for compose table
				/*ingredeints*/
				$sql_ingredient = "SELECT * FROM INGREDIENT WHERE ID_INGREDIENT = $ingredient_id";
				$data_ingredient = $conn->query( $sql_ingredient );
				$data_ingredient = $data_ingredient->fetch_assoc();

				/*unit mass*/
				$sql_unite = "SELECT * FROM UNITE WHERE ID_UNITE = $unit_id";
				$data_unite = $conn->query( $sql_unite );
				$data_unite = $data_unite->fetch_assoc();

				// Calculation of the value on a reference of the value for 100g, according to the quantity
		        $sum1 = ($data_ingredient['VALEURCAL']* $s['qty'])/100;
		        $sum2 = ($data_ingredient['LIPIDE']*$s['qty'])/100;
		        $sum3 = ($data_ingredient['GLUCIDE']*$s['qty'])/100;
		        $sum4 = ($data_ingredient['PROTEINE']*$s['qty'])/100;

		        // Conversion from kg to g for the calculation of values
		        if($data_unite['UNITE_MASSE'] == 'kg'){
		          $sum1 = ($data_ingredient['VALEURCAL']*1000*$s['qty'])/100;
		          $sum2 = ($data_ingredient['LIPIDE']*1000*$s['qty'])/100;
		          $sum3 = ($data_ingredient['GLUCIDE']*1000*$s['qty'])/100;
		          $sum4 = ($data_ingredient['PROTEINE']*1000*$s['qty'])/100;
		        }

		        // Calculation of the price on a value of the price per kilo, according to the quantity
		        $total1 = $data_ingredient['PRIX_RUNGIS']*$s['qty'];
		        $total2 = $data_ingredient['PRIX_LEADER']*$s['qty'];

		        // Conversion from g to kg for price calculation
		        if($data_unite['UNITE_MASSE'] == 'g'){
		          $total1 = ($data_ingredient['PRIX_RUNGIS']*$s['qty']/1000);
		          $total2 = ($data_ingredient['PRIX_LEADER']*$s['qty']/1000);
		        }

				$m_sql = "INSERT INTO COMPOSE (ID_INGREDIENT, ID_RECETTE, ID_UNITE, QUANTITE) VALUES ($ingredient_id,$recep_id,$unit_id,$qty)";
				$r[] = $conn->query($m_sql);
			}
			
			if( !in_array( false, $r ) ) {
				$_SESSION['success'] = 'success'; 
				$_SESSION[ 'messages' ] = 'Recette ajoutée avec succès';

				$response[ 'success' ] = true;
				$response[ 'message' ] = '<div class="alert alert-success" role="alert">Recette ajoutée avec succès</div>';
			}
			else {
				$_SESSION['success'] = 'success'; 
				$_SESSION[ 'messages' ] = 'Recette supprimée avec succès';

				$response[ 'success' ] = false;
				$response[ 'message' ] = '<div class="alert alert-danger" role="alert">'.$conn->error.'</div>';					

				$sql = "DELETE FROM RECETTE WHERE ID_RECETTE=$recep_id";
				$conn->query($sql);
			}
		}
	}
	else {
		// sql to delete a record
		$sql = "DELETE FROM RECETTE WHERE ID_RECETTE=$recep_id";
		$conn->query($sql);
	}

	$conn->close();
}
echo json_encode( $response );
