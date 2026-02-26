<?php
$servername = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$sql = "SELECT id, content FROM posts";
$result = $conn->query($sql);

echo "<!DOCTYPE html>
<html lang='it'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Lista Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #2c3e50;
            padding: 20px 0;
            margin-bottom: 40px;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: #fff;
            font-size: 1.1rem;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        table, th, td {
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        table {
            margin-top: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Lista Post</h1>";

if ($result->num_rows > 0) {
    // Se ci sono dei post, mostrali in una tabella
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Contenuto del Post</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["content"] . "</td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>Non ci sono post da visualizzare.</p>";
}

$conn->close();

echo "</body>
</html>";
?>
