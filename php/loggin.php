<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "functions.php";
    $nutri_sql = "null"; $pass_hashed = null; $login_sql = null; $stmt = null; 
    
    $is_valid = false;
    $email = SQL_FIREWALL($_POST["email"], 's');
    $pass = SQL_FIREWALL($_POST["password"], 's');

    if(empty($email)){
        die(messageH1("Wrong"));
    }else if(empty($pass)){
        die(messageH1("Wrong"));
    }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        die(messageH1("Wrong"));
    }else if(!empty($email) && !empty($pass) && filter_var($email, FILTER_VALIDATE_EMAIL)){
        try{
            //connect server
            include "main_db.php";
            $nutri_sql = $sql_nutri;

            //sql querry
            $login_sql = "SELECT id, pass_hash FROM users WHERE email = (?)";

            //new prepare statement obj
            $stmt = $nutri_sql->stmt_init();

        }catch(Exception $e) {
            die(messageH1($e->getMessage()));
        };

        //prepare sql for execution and check
        try{
            $stmt->prepare($login_sql);
        }catch(Exception $e) {
            die(messageH1($e->getMessage()));
        };

        //attach variables
        try{
            $esc_email = $nutri_sql->real_escape_string($email);
            $stmt->bind_param("s", $esc_email);
        }catch(Exception $e) {
            die(messageH1($e->getMessage()));
        };

        //execute sql
        try{
            $hashed; $id;
            $stmt->execute();
            $result = $stmt->get_result (); //get the mysqli_result object
            while ($row = $result->fetch_assoc ()) { //get results
                $hashed = $row["pass_hash"]; //get pass
                $id = $row["id"]; //get id
            }; 

            if(!empty($hashed) && password_verify($pass, $hashed)){
                session_start();
                session_regenerate_id();
                $_SESSION["id"] = $id;
                navigate("index.html");
                exit;
            }else{
                echo(messageH1("Wrong"));
            };
            $stmt->close();
            exit;
        }catch(Exception $e) {
            if($nutri_sql->errno === 1062){
                die(messageH1("Email Already Taken"));
            }else{
                die(messageH1($e->getMessage()));
            };
            
        };   

    }else{
        echo(messageH1("gatau error nya apa"));
    };

}else{
    echo(messageH1("Mana POST nya"));
};

?>