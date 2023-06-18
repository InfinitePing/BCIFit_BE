<?php
    try{

        include "functions.php";
  
        $id = isLogged();

        if($id){

            echo(runSQL("


            SELECT data1 FROM testing WHERE id_user = $id;


            "));
        }else{
            echo(json_encode("Logged Out"));
        };
        exit;
    }catch(Exception $e){
        echo(json_encode("Error: $e"));
    };

?>