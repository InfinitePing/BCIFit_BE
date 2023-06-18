<?php

function runSQL($sql){

if(empty($_SESSION)){session_start();};

if(!empty($_SESSION["id"])){

        $nutri_sql = null; $pass_hashed = null; $final_sql = null; $stmt = null; $final = null;
        
        try{
            //connect server
            include "main_db.php";
            $nutri_sql = $sql_nutri;

            //sql querry processing
            $final_sql = strval($sql);

            //new prepare statement obj
            $stmt = $nutri_sql->stmt_init();

        }catch(Exception $e) {
            return(json_encode("Error1: " .$e->getMessage()));
        };

        //prepare sql for execution and check
        try{
            $stmt->prepare($final_sql);
        }catch(Exception $e) {
            return(json_encode("Error2: " .$e->getMessage()));
        };

        //execute sql
        try{
            $stmt->execute();
            $result = $stmt->get_result(); //get the mysqli_result object
         
            if(!empty($result)){
                while ($row = $result->fetch_assoc()) { //get results
                    $final[] = $row;
                }; 
                $stmt->close();
                return(json_encode($final));
            }else{
                $final = json_encode("success");
                $stmt->close();
                return(json_encode($final));
            };
     
        }catch(Exception $e) {
            return(json_encode("Error4: " .$e->getMessage()));   
        };   
    }else{
        return(json_encode("You are logged out"));
    };
};


function navigate($luink){
    $queryString =  $_SERVER['QUERY_STRING'];
    header("Location: ".$queryString."/".$luink);
    exit;
};

function messageH1($message){

    $final = "Error: ".$message;

    return("
    <html><body>
    <div style='display: flex; align-items: center; flex-direction: column;'>
        <h1>$final</h1><br><br>
        <h1><a href=\"javascript:history.go(-1)\">GO BACK(Reccomended)</a></h1>
        <h2><a href='return.php'>Click to Return to Homepage</a></h2>
    </div>
    </body></html>
    ");
};

function messageH1s($message){

    $final = "Success: ".$message;

    return("
    <html><body>
    <div style='display: flex; align-items: center; flex-direction: column;'>
        <h1>$final</h1><br><br>
        <h1><a href='return.php'>Click to Return to Homepage</a></h1>
    </div>
    </body></html>
    ");
};


function SQL_FIREWALL($raw, $dtypes){
    include "main_db.php";
    $nutri_sql = $sql_nutri;
    $raw1; $raw2; $raw3;

    if(empty($raw)){return false;}

    else if(!preg_match("/[a-z]/i", $raw) && ($dtypes == "n" || $dtypes == "N" )){
        $raw2 = $raw;
        return $raw2;
    }else{
        $raw1 = strval($raw);
        $raw2 = $nutri_sql->real_escape_string($raw1); 
        $raw3 = check_string($raw2);
        return $raw3;
    };
};

function isLogged(){
    session_start();
    if(!empty($_SESSION)){
        return($_SESSION["id"]);
    }else{
        return false;
    };
    
};

function check_string($str) {
    // lowercase the string
    $raw = $str;
    $str = strtolower($str);
    // check for forbidden words or symbols
    $forbidden = array("insert", "select", "update", "delete", "where", "$");
    // loop through the forbidden array
    foreach ($forbidden as $word) {
        // if the string contains any of the forbidden words or symbols, return false
        if (strpos($str, $word) !== false) {
        return false;
        };
    };
    // otherwise, return the string
    return $raw;
};

?>