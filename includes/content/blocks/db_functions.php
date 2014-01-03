<?php
/*
 //Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/


?>

<?php

class DB_Functions
{

    private $db;

    //put your code here
    // constructor
    function __construct()
    {
    }

    // destructor
    function __destruct()
    {

    }

    /**
     * Storing new user
     * returns user details
     */

    public function storeUser($detectedip, $claimedip, $tag, $name, $email, $password, $master, $outlawcharacters)
    {

        //Escape entered info
        $escapedName = mysql_real_escape_string($name);
        $escapedPW = mysql_real_escape_string($password);
        $escapedEmail = mysql_real_escape_string($email);
        $credentials_version = "1"; //Credentials v1 is the first version to incorporate keys and the credited field

        //Verify that the username is not already taken

        $query = "select * from Users where username='$escapedName' OR email='$escapedEmail'";
        $pwq = mysql_query($query, $master);
        $num = mysql_num_rows($pwq);

        //Validation
        If ($num != 0) {
            $user["error"] = 4;
            $user["error_msg"] = "That username or email address is not unique. User not created.";
            return $user;
        } else if (strlen($escapedName) < 4) {
            $user["error"] = 5;
            $user["error_msg"] = "Please choose a username that is at least 4 characters.";
            return $user;
        } else if (strlen($escapedPW) < 4) {
            $user["error"] = 6;
            $user["error_msg"] = "Please choose a password that is at least 4 characters.";
            return $user;
        } else if (in_string($outlawcharacters, $escapedName)) {
            $user["error"] = 7;
            $user["error_msg"] = "You may not use special characters in your username.";
            return $user;
        } else if (email_bad2($escapedEmail)) {
            $user["error"] = 8;
            $user["error_msg"] = "$escapedEmail email is not valid.";
            return $user;
        }


        // generate a random salt to use for this account
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

        // generate a random mobile_auth_key to verify that the user is logged on to this device
        $mobile_auth_key = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

        //$salt = bin2hex(76);

        $saltedPW = $escapedPW . $salt;

        $hashedPW = hash('sha256', $saltedPW);


        $query = "insert into Users (username, hashedpw, salt, level, email, mobile_auth_key )";
        $query .= " values ('$escapedName', '$hashedPW', '$salt', 'member', '$escapedEmail', '$mobile_auth_key' ); ";
        $upd = mysql_query($query, $master);
        //Get the id for the new user
        $query = "select max(id) as id from Users";
        $res = mysql_query($query, $master);
        $max = mysql_fetch_assoc($res);
        //Verify that the user was created

        $query = "select id, salt, hashedpw, username, email, level, mobile_auth_key, follows   from Users where id='" . $max['id'] . "'";
        $res = mysql_query($query, $master);
        $name = mysql_fetch_assoc($res);
        $query = "INSERT INTO mobile_access (user, fest, auth, tag, phptime, detectedip, claimedip) VALUES ('" . $name['id'] . "', '0', '" . $name['mobile_auth_key'] . "', '$tag', '" . time() . "', '$detectedip', '$claimedip')";
        $result["debug"] = $query;
        //				$result["debug"] = "empty";
        $upd = mysql_query($query, $master);
        If ($name['username'] != $escapedName) return false;

        $query = "select username from Users where id='" . $max['id'] . "'";
        $res = mysql_query($query, $master);
        $name = mysql_fetch_assoc($res);
        If ($name['username'] != $escapedName) die("User not created");

        //Create a settings table for the user


        $sql = "CREATE TABLE user_settings_" . $max['id'] . " (id int NOT NULL AUTO_INCREMENT, item varchar( 255 ) NOT NULL ,value varchar( 255 ) NOT NULL, `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id))";
        $sql2 = "INSERT INTO user_settings_" . $max['id'] . " SELECT * FROM user_settings_template;";

        $res = mysql_query($sql, $master);
        $res = mysql_query($sql2, $master);
        $name['error'] = 0;
        return $name;

    }


    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($detectedip, $claimedip, $tag, $email, $name, $password, $master)
    {
        $escapedName = mysql_real_escape_string($name);
        $escapedEmail = mysql_real_escape_string($email);
        $escapedPW = mysql_real_escape_string($password);
        $detectedip = mysql_real_escape_string($detectedip);
        $claimedip = mysql_real_escape_string($claimedip);
        $tag = mysql_real_escape_string($tag);
        $result = mysql_query("SELECT id, salt, hashedpw, username, email, level, mobile_auth_key, follows  FROM Users WHERE email = '$escapedEmail' OR username = '$escapedName'") or die(mysql_error());
        // check for result
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows == 1) {
            $result = mysql_fetch_assoc($result);
            $salt = $result['salt'];
            $encrypted_password = $result['hashedpw'];
            $saltedPW = $escapedPW . $salt;
            unset($result['salt']);
            unset($result['hashedpw']);

            $hash = hash('sha256', $saltedPW);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct

                if (empty($result['mobile_auth_key'])) {
                    // generate a random mobile_auth_key to verify that the user is logged on to this device
                    $mobile_auth_key = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
                    $query = "Update Users Set mobile_auth_key='$mobile_auth_key' Where (email = '$escapedEmail' OR username = '$escapedName' ) AND hashedpw = '$hash'";
                    //					$result["debug"] = $query;
                    $upd = mysql_query($query, $master);
                } else $mobile_auth_key = $result['mobile_auth_key'];
                $query = "INSERT INTO mobile_access (user, fest, auth, tag, phptime, detectedip, claimedip) VALUES ('" . $result['id'] . "', '0', '$mobile_auth_key', '$tag', '" . time() . "', '$detectedip', '$claimedip')";
                $result["debug"] = $query;
                //				$result["debug"] = "empty";
                $upd = mysql_query($query, $master);
                $result["mobile_auth_key"] = $mobile_auth_key;
                $result['error'] = 0;
                return $result;
            }
        }
        // user not found
        return false;
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($name, $email, $master)
    {
        $escapedName = mysql_real_escape_string($name);
        $escapedEmail = mysql_real_escape_string($email);
        $result = mysql_query("SELECT email from Users WHERE email = '$escapedEmail' OR username = '$escapedName'", $master);
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed
            return true;
        } else {
            // user not existed
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password)
    {
        $escapedPW = mysql_real_escape_string($password);

        // generate a random salt to use for this account
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

        //$salt = bin2hex(76);

        $saltedPW = $escapedPW . $salt;

        $encrypted = hash('sha256', $saltedPW);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt , password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password)
    {
        $escapedPW = mysql_real_escape_string($password);
        $saltedPW = $escapedPW . $salt;

        $hash = hash('sha256', $saltedPW);

        return $hash;
    }

    /**
     * Check if auth keys match userid
     */
    public function userAuthOK($userid, $mobile_auth_key, $master)
    {
        $userid = mysql_real_escape_string($userid);
        $mobile_auth_key = mysql_real_escape_string($mobile_auth_key);
        $query = "select * from Users where id='" . $userid . "' and mobile_auth_key='$mobile_auth_key'";
        $res = mysql_query($query, $master);
        $num = mysql_num_rows($res);
        If ($num == 0) return false;
        return true;
    }

    public function bandList($fest_id)
    {
        $fest_id = mysql_real_escape_string($fest_id);

        //Connect to fest db and pull data frm there
        return true;
    }

    function getBandGenreAPI($master, $band, $user)
    {
        //This function gets the name of a genre for a given user and band

        //If the user has an entry in the genre table for that band, return that genre
        $sql = "select genre from bandgenres where band='" . $band . "' and user='$user'";
        $res = mysql_query($sql, $master);
        If (mysql_num_rows($res) > 0) {
            $row = mysql_fetch_assoc($res);
            $gid = $row['genre'];
        } else {
            //If the user has no entry, return the genre with the highest count
            $sql1 = "select genre, count(user) as num from bandgenres where band='" . $band . "' group by genre order by num desc limit 1";
            $res1 = mysql_query($sql1, $master);
            If (mysql_num_rows($res1) > 0) {
                $row1 = mysql_fetch_assoc($res1);
                $gid = $row1['genre'];
            } else $gid = 0;
        }
        $gname = getGname($gid);

        return $gname;
    }


    /**
     * Get user by email and password
     */
    public function storeUserAccess($detectedip, $claimedip, $tag, $master, $uid, $auth, $fest)
    {
        $escapedUID = mysql_real_escape_string($uid);
        $escapedAuth = mysql_real_escape_string($auth);
        $detectedip = mysql_real_escape_string($detectedip);
        $claimedip = mysql_real_escape_string($claimedip);
        $tag = mysql_real_escape_string($tag);
        $fest = mysql_real_escape_string($fest);

        $query = "INSERT INTO mobile_access (user, fest, auth, tag, phptime, detectedip, claimedip) VALUES ('" . $escapedUID . "', '$fest', '$escapedAuth', '$tag', '" . time() . "', '$detectedip', '$claimedip')";
        $result["debug"] = $query;
        //				$result["debug"] = "empty";
        $upd = mysql_query($query, $master);
        return true;

    }

    function getFullTable($main, $master, $table, $uid)
    {
        //This function gets the name of a genre for a given user and band


        $sql = "SELECT * FROM `$table`";
        $pwq = mysql_query($sql, $main);
        $num = mysql_num_rows($pwq);
        $db = new DB_Functions();

        //Validation
        If ($num != 0) {
            while ($row = mysql_fetch_assoc($pwq)) {
                if ($table == "bands") {
                    $row['genre'] = $db->getBandGenreAPI($master, $row['master_id'], $uid);
                }
                if ($table == "Users") {
                    unset($row['credentials_version']);
                    unset($row['salt']);
                    unset($row['hashedpw']);
                    unset($row['level']);
                    unset($row['count']);
                    unset($row['group']);
                    unset($row['used_key']);
                    unset($row['public_key']);
                    unset($row['private_key']);
                    unset($row['credited']);
                    unset($row['all_keys']);
                    unset($row['mobile_auth_key']);
                }
                $result[$table][] = $row;

            }
            $result['error'] = 0;
        } else {
            $result["error"] = 9;
            $result["error_msg"] = "Table $table contains no data.";
        }


        return $result;
    }

    function getFullDB($db)
    {
        //This function gets the name of a genre for a given user and band
        $query = "SHOW TABLES";
        $tables_result = mysql_query($query, $db);
        $i = 0;
        while ($table = mysql_fetch_row($tables_result)) {

            $sql = "SELECT * FROM `" . $table[0] . "`";
            $pwq = mysql_query($sql, $db);
            $num = mysql_num_rows($pwq);

            //Validation
            If ($num != 0) {
                while ($row = mysql_fetch_assoc($pwq)) {
                    $rows[] = $row;
                }
                $result['error'] = 0;
            } else {
                $result["error"] = 9;
                $result["error_msg"] = "The table contains no data.";
            }
            $tables[$i]['table'] = $table[0];
            $tables[$i]['data'] = $rows;
            $i++;
        }
        return $tables;
    }

    function getTableList($db)
    {
        //This function gets the name of a genre for a given user and band
        $query = "SHOW TABLES";
        $tables_result = mysql_query($query, $db);
        while ($table = mysql_fetch_row($tables_result)) {
            if (substr($table[0], 0, 11) != "discussion_" && $table[0] != "pics") $tables['tables'][] = $table[0];
        }
        $tables['error'] = 0;
        return $tables;
    }


}

?>