<?php
	include_once("connection.php");	
	
	if(isset($_POST['operation']) && $_POST['operation'] == "delete"){
		if(isset($_POST['id'])){
			$person_id = $_POST['id'];
			$isDeleted = mysqli_query($conn, "DELETE FROM person WHERE id = $person_id");

			if($isDeleted){
				echo "deleted";
			}else{
				echo "not deleted";
			}
		}
	}

	if(isset($_POST['operation']) && $_POST['operation'] == "update"){
		if(isset($_POST['id'])){
			$person_id = $_POST['id'];
			$res = mysqli_query($conn, "SELECT id, user_name, phone, email, type, address FROM person WHERE id = $person_id");
			$res = mysqli_fetch_assoc($res);
			echo json_encode($res);
		}
	}	
?>