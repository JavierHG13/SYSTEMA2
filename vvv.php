<?php
$serverName = "YAZIR1";
$connectionOptions = array(
    "Database" => "System_G_Test_20242",
    "Uid" => "",
    "PWD" => ""
);

// Establecer conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);

if($conn === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    echo "Conexión exitosa!";
}

// Cerrar conexión
sqlsrv_close($conn);
?>
