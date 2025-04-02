<?php
require "auth_functions.php";
$score = getScore($pdo);
$records = getRecords($pdo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score</title>
    <style>
        h1 {
            text-align: center;
            color: red;
            font-size: 60px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Score:<?php echo $score; ?></h1>
    <div>
        <p>Success Records:</p>
    </div>
    <table>
        <tr>
            <th>Puzzles</th>
            <th>Score</th>
            <th>Submit time</th>
        </tr>
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['puzzle_id']) ?></td>
                <td>+1</td>
                <td><?php echo htmlspecialchars($record['submit_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>