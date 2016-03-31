<?php
/************************************************************
 * Establish connection with Oracle Database using OCI8
 ************************************************************/
//Adds setting to the server environment for the duration of the current request.
putenv('ORACLE_HOME=/oraclient');
//Set parameters
$db_username = 'database id';
$db_password = 'database password';
$host = "(DESCRIPTION =
            (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = sid3.comp.nus.edu.sg)(PORT = 1521)))
            (CONNECT_DATA = (SERVICE_NAME = sid3.comp.nus.edu.sg))
        )";
//Establish connection (Use oci_connect, as ocilogon is depreciated)
$connect = oci_connect($db_username, $db_password, $host);
//Throw error if failure to connect
if (!$connect) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    //Exit - The entire php script is rendered useless
    exit;
}
?>

