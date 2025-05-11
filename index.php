<?php
session_start();

if (isset($_POST['Submit'])) {
    $min_weight = $_POST['min_weight'];
    $port_prefix = $_POST['port_prefix'];

    if (empty($port_prefix)) {
        $port_prefix = "US";
    }

    try {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "webmtc";
        
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("
            SELECT cargo_id, shipping_line, dest_port, weight_kg
            FROM cargo_shipping 
            WHERE dest_port LIKE :destination_port 
            AND weight_kg > :weight_kg
        ");

        $destination_port = $port_prefix . "%";
        $stmt->bindParam(':destination_port', $destination_port);
        $stmt->bindParam(':weight_kg', $min_weight);

        $results = $stmt->execute();

        if ($results) {
            echo('2133506A Julius Chan<br><br>');
            
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                echo 'Cargo ID = ' . $row["cargo_id"] . '<br>' .
                     'Shipping Line = ' . $row["shipping_line"] . '<br>' .
                     'Destination Port = ' . $row["dest_port"] . '<br>' .
                     'Weight (KG) = ' . $row["weight_kg"] . ' kg<br><br>';
            }

            if (count($rows) === 0) {
                echo "No cargo containers found matching the criteria.<br><br>";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
    echo('<a href="index.php">Go back to search page</a>');
} else {
?>
    <form method="POST" action="#">
        2133506A Julius Chan<br><br>
        Search Cargo Shipping <br><br>
        Destination Port starts with : <input type='textbox' length=20 name='port_prefix'></input><br>
        Minimum Weight (KG) more than: <input type='textbox' length=20 name='min_weight'></input><br>
        <br><input type='Submit' name='Submit' value='Submit'></input>
    </form>
<?php
}
?>
