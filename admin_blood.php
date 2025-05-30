<?php
$conn = new mysqli("localhost", "root", "", "blood_donation");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $conn->query("DELETE FROM donors WHERE id = $deleteId");
}

$result = $conn->query("SELECT * FROM donors ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f44336; color: white; }
    </style>
</head>
<body>
    <h1>Blood Donor Registrations</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Blood Type</th>
                <th>Last Donation</th>
                <th>Location</th>
                <th>Medications</th>
                <th>Allergies</th>
                <th>Registered At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['blood_type']) ?></td>
                    <td><?= htmlspecialchars($row['last_donation']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['medications']) ?></td>
                    <td><?= htmlspecialchars($row['allergies']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                            <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                            <button type="submit" style="background:#f44336;color:white;border:none;padding:6px 12px;cursor:pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
