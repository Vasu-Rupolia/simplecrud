<?php
	include_once("connection.php");

    $res = mysqli_query($conn, "SELECT * FROM person");
    $data = array();
    while($single = mysqli_fetch_assoc($res)){
        $data[] = $single;
    }

    if(isset($_POST['add_record'])){
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $userphone = mysqli_real_escape_string($conn, $_POST['userphone']);
        $usertype = mysqli_real_escape_string($conn, $_POST['usertype']);
        $usermail = mysqli_real_escape_string($conn, $_POST['usermail']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $added_date = date('Y-m-d');

        $isInserted = mysqli_query($conn, "INSERT INTO person (user_name, phone, type, email, address, added_date) VALUES ('$username', '$userphone', '$usertype', '$usermail', '$address', '$added_date')");
        header("Refresh:0");
    }

    if(isset($_POST['update_record'])){

        $id = $_POST['person_id'];
        $username = mysqli_real_escape_string($conn, $_POST['username_update']);
        $userphone = mysqli_real_escape_string($conn, $_POST['userphone_update']);
        $usertype = mysqli_real_escape_string($conn, $_POST['usertype_update']);
        $usermail = mysqli_real_escape_string($conn, $_POST['usermail_update']);
        $address = mysqli_real_escape_string($conn, $_POST['address_update']);
        $updated_date = date('Y-m-d');

        $isUpdated = mysqli_query($conn, "UPDATE person SET user_name = '$username', phone = '$userphone', type = '$usertype', email = '$usermail', address = '$address', updated_date = '$updated_date' WHERE id = $id");
        
        if($isUpdated){
            $updateMessage = "Record Updated Successfully!";
        }

        header("Refresh:0");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <title>Simple Crud</title>

        <!-- Styles -->
        <style>
            



        </style>
    </head>
    <body>
        <div id="parent">  
        <header>
            <div id="logo_box">Simple Crud </div>
            <div id="header_links_box" class="links">
            </div>
        </header>

        <div class="form_box">
            <span id="messages">
                <?php
                if(isset($updateMessage)){
                    set_time_limit(5);
                    echo $updateMessage;
                }
                ?>
            </span>
            <button type="button" id="add_button" class="buttons">Add</button> &nbsp; <button type="button" class="buttons" id="view_button">View</button>
            <br /><br />
        </div>    
            
        <div class="form_box" id="crud_add_box">
        	
        	<form action="" method="POST">
                <input placeholder="Enter name" class="halfinput" type="text" name="username">
                <input placeholder="Enter phone number" class="halfinput" type="text" name="userphone">
                <select class="halfinput dropdown" name="usertype">
                	<option value="">Select One</option>
                	<option value="student">Student</option>
                	<option value="professional">Professional</option>
                </select>
                <input placeholder="Enter email" class="halfinput" type="text" name="usermail">
                <textarea placeholder="Enter address" id="address" name="address"></textarea>
                <br /><br />
                <button type="submit" class="buttons" id="submit" name="add_record">Submit</button>
            </form>
        </div>

        <div class="form_box" id="crud_update_box">
            
            <form action="" method="POST">
                <input type="hidden" id="person_id" name="person_id" value=""/>
                <input placeholder="Enter name" class="halfinput" type="text" id="username_update" name="username_update">
                <input placeholder="Enter phone number" class="halfinput" type="text" id="phone_update" name="userphone_update">
                <select class="halfinput dropdown" name="usertype_update" id="usertype_update">
                    <option value="">Select One</option>
                    <option value="student">Student</option>
                    <option value="professional">Professional</option>
                </select>
                <input placeholder="Enter email" class="halfinput" id="email_update" type="text" name="usermail_update">
                <textarea placeholder="Enter address" name="address_update" id="address_update"></textarea>
                <br /><br />
                <button type="submit" class="buttons" id="update" name="update_record">Submit</button>
            </form>
        </div>

        <div class="table_box" id="crud_view_box">
        	<table class="mytable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>

                    <tbody>
                        <?php
                            if($data != null){
                                $sno = 1;
                                foreach ($data as $key => $val) {
                                    ?>
                                    <tr>
                                        <td><?php echo $sno;?></td>
                                        <td><?php echo $val['user_name'];?></td>
                                        <td><?php echo $val['phone'];?></td>
                                        <td><?php echo $val['email'];?></td>
                                        <td><?php echo $val['type'];?></td>
                                        <td><?php echo $val['address'];?></td>
                                        <td>
                                            <button type="button" id="update_button_<?php echo $val['id']?>" class="buttons update_button">Update</button>
                                            <button type="button" id="delete_button_<?php echo $val['id']?>" class="buttons delete_button">Delete</button>
                                        </td>    
                                    </tr>
                                    <?php
                                    $sno++;
                                }
                            }
                        ?>
                    </tbody>
                </thead>
            </table>
        </div>
        
        </div>
        <script src="js/jquery.js"></script>
        <script>
            $(document).ready(function(){
                $("#add_button").on("click", function(){
                    $("#crud_view_box").css("display", "none");
                    $("#crud_update_box").css("display", "none");
                    $("#crud_add_box").css("display", "block");
                });
                $("#view_button").on("click", function(){
                    $("#crud_view_box").css("display", "block");
                    $("#crud_update_box").css("display", "none");
                    $("#crud_add_box").css("display", "none");
                });

                $(".delete_button").on("click", function(){
                    
                    var confirm = window.confirm("Are you sure you want to delete this?");
                    if(confirm){    
                        var person_id = this.id.split("_")[2];

                        $.ajax({
                            url: "ajax_part.php",
                            method: "post",
                            data: {id: person_id, operation:"delete"},
                            success:function(result, status){
                                if(result == "deleted"){
                                    alert("Deleted Successfully");
                                    location.reload();
                                }
                            }    
                        });
                    }
                });

                $(".update_button").on("click", function(){
                    
                        var person_id = this.id.split("_")[2];

                        $.ajax({
                            url: "ajax_part.php",
                            method: "post",
                            data: {id: person_id, operation:"update"},
                            success:function(result, status){
                                var res = $.parseJSON(result);

                                $("#crud_update_box").css("display", "block");
                                $("#person_id").val(res['id']);
                                $("#username_update").val(res['user_name']);
                                $("#phone_update").val(res['phone']);
                                $("#email_update").val(res['email']);
                                $("#usertype_update").val(res['type']);
                                $("#address_update").val(res['address']);

                                $("#crud_add_box").css("display", "none");
                                $("#crud_view_box").css("display", "none");    
                            }    
                        });
                    
                });
            });
        </script>

        
    </body>

</html>