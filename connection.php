<?php
	try{
		$conn = mysqli_connect("localhost", "root", "", "crud");
		if(!$conn){
			throw new Exception("No database found");
		}
	}catch(Exception $e){
		$e->getMessage();
	}
	
?>