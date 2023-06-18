<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try{

        include "functions.php";

        $id = isLogged();

        if($id){

            $data = json_decode(file_get_contents('php://input'), true);
            $data1 = SQL_FIREWALL($data["val1"], 's');

            echo(runSQL("


            INSERT INTO testing(data1, id_user) VALUES('$data1', $id);


            "));
        }else{
            echo(json_encode("Logged Out"));
        };
        exit;
    }catch(Exception $e){
        echo(json_encode("Error: $e"));
    };

}else{
    include "functions.php";
    echo(messageH1("Mana POST nya"));
    exit;
};
?>

