<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $id = "1";
    $conn = new mysqli("localhost", "root", "", "k9tin");
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    echo $result->num_rows;
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    else {
        echo "Connected successfully";
        echo "<br>";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Email: " . $row["email"]. "<br>";
            }
        } else {
            echo "0 results";
        }
    }
    ?>
</body>

</html>
