<?php
include '../connection.php';

// Function to reset IDs and update AUTO_INCREMENT (no transaction management here)
function resetIdsAndAutoIncrement($table, $con) {
    try {
        // Reset the ID sequence by updating each row's ID in order
        $con->exec("SET @num := 0");
        $con->exec("UPDATE `$table` SET ID = @num := (@num + 1)");

        // Get the max ID to set the AUTO_INCREMENT value
        $stmt = $con->prepare("SELECT MAX(ID) AS max_id FROM `$table`");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $maxId = $row['max_id'] ?? 0;

        // Set the AUTO_INCREMENT value to the next available ID
        $newAutoIncrement = $maxId + 1;
        $con->exec("ALTER TABLE `$table` AUTO_INCREMENT = $newAutoIncrement");

    } catch (PDOException $e) {
        echo "Error resetting IDs: " . $e->getMessage();
    }
}

if (isset($_GET['Id'])) {
    $id = $_GET['Id'];

    try {
        // Begin a transaction before the delete operation
        $con->beginTransaction();

        // Delete from 2024-2025 table
        $stmt = $con->prepare("DELETE FROM `2024-2025` WHERE Alumni_ID_Number = ?");
        $stmt->execute([$id]);

        // Delete from 2024-2025_ED table
        $stmt2 = $con->prepare("DELETE FROM `2024-2025_ED` WHERE Alumni_ID_Number = ?");
        $stmt2->execute([$id]);

        // Commit the transaction for delete operation
        $con->commit();

        // Now reset the IDs and AUTO_INCREMENT for both tables
        resetIdsAndAutoIncrement('2024-2025', $con);
        resetIdsAndAutoIncrement('2024-2025_ed', $con);

        // Redirect after deletion and reset
        header('Location: alumni_list.php');
        exit;

    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if no ID is provided
    header('Location: alumni_list.php');
    exit;
}
?>
