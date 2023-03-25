<?php
    // adduser.php is the file for database connection


    // creating a session. This will make sure the user is logging into his account after account has been created
    session_start();

    //Open a new connection to the MySQL server
    //$mysqli is the connection name and can be changed to anything
    $mysqli = new mysqli('localhost', 'root', '', 'perfectcup');

    //Output any connection error
    if ($mysqli->connect_error) {
    die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    
    /** mysqli_real_escape_string is used to escape any special charater input by the user into form inputs and this
     * makes the website secure by preventing an sql injection
      */
    $fname = mysqli_real_escape_string($mysqli, $_POST['fname']);
    $lname = mysqli_real_escape_string($mysqli, $_POST['lname']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    //error checking

    if (strlen($fname) < 2) {
        echo 'fname';
    } elseif (strlen($lname) < 2) {
        echo 'lname';
    } elseif (strlen($email) <= 4) {
        echo 'eshort';
        // FILTER_VALIDATE_EMAIL is used to validate email format
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo 'eformat';
    } elseif (strlen($password) <= 4) {
        echo 'pshort';
        
    //error checking
        
    } else {
	
        //PASSWORD ENCRYPT
        //the higher the cost value, the stronger the hash. The slower the webpage load
        $spassword = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        
        $query = "SELECT * FROM members WHERE email='$email'";
        $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
        $num_row = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);
        
            if ($num_row < 1) {
                // checking if account already exist or create an account
                $insert_row = $mysqli->query("INSERT INTO members (fname, lname, email, password) VALUES ('$fname', '$lname', '$email', '$spassword')");
    
                if ($insert_row) {
    
                    $_SESSION['login'] = $mysqli->insert_id;
                    $_SESSION['fname'] = $fname;
                    $_SESSION['lname'] = $lname;
    
                    echo 'true';
    
                }
    
            } else {
    
                echo 'false';
    
            }
            
    }

?>