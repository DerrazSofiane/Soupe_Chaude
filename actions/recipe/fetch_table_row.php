<?php  
 // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL)
	include( '../../config.php' );

	$row_id = isset( $_REQUEST[ 'row_id' ] ) ? $_REQUEST[ 'row_id' ] : null;

	$sql = "SELECT * FROM INGREDIENT";
	$results = mysqli_query($conn, $sql);

	$data = '';
	if( $results ) {
		foreach ( $results as $result ) {
			$data .= "<option value='". $result[ 'ID_INGREDIENT' ] ."'>".$result[ 'NOM_INGREDIENT' ]."</option>";	
		}
	}

	$u_sql = "SELECT * FROM UNITE";
	$u_results = mysqli_query($conn, $u_sql);

	$u_data = '';
	if( $u_results ) {
		foreach ( $u_results as $result ) {
			$u_data .= "<option value='". $result[ 'ID_UNITE' ] ."'>".$result[ 'UNITE_MASSE' ]."</option>";	
		}
	}


	$html = '';

	$html = '<tr data-row-id="'.$row_id.'" id="row_'.$row_id.'">';
	$html .= '<td style="width: 40%">';
	$html .= '<select class="form-control" name="select['.$row_id.'][ingredient_id]" class="select2">';
	$html .= '<option></option>';
	$html .= $data;
	$html .= '</select>';
	$html .= '</td>';
	$html .= '<td style="width: 10%">';
	$html .= '<input type="text" name="select['.$row_id.'][qty]" class="form-control">';
	$html .= '</td>';
	$html .= '<td style="width: 40%">';
	$html .= '<select class="form-control" name="select['.$row_id.'][unit_id]">';
	$html .= '<option></option>';
	$html .= $u_data;
	$html .= '</select>';
	$html .= '</td>';
	$html .= '<td style="width: 10%">';
	$html .= '<button type="button" onclick="removeRow('.$row_id.')" class="btn btn-danger"><i class="fas fa-minus"></i></button>';
	$html .= '</td>';
	$html .= '</tr>';

	

	// Close connection
    mysqli_close($conn);

    echo $html;



?>