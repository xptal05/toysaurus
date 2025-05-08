<?php
if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');
    fgetcsv($csvFile); // Skip header

    while (($row = fgetcsv($csvFile, 1000, ',')) !== FALSE) {
        // Example: [0] => name, [1] => price, [2] => category
        $name = $row[0];
        $price = $row[1];
        $category = $row[2];

        // TODO: Save to database
        // Example:
        // $stmt = $pdo->prepare("INSERT INTO products (name, price, category_id) VALUES (?, ?, ?)");
        // $stmt->execute([$name, $price, $category]);
    }

    fclose($csvFile);
    echo "Products imported successfully!";
} else {
    echo "Error uploading file.";
}
?>
