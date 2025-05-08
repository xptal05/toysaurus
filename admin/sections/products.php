<?php
include_once BASE_PATH . '/controllers/ToyController.php';
$toys = ToyController::getAllToys();
?>

<h2>Manage Products</h2>
<div>
    <input type="file" name="csv_file" accept=".csv" id="csv-file">
    <button type="submit" id="import-products-btn">Import products</button>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Meta</th>
        <th>Actions</th>
    </tr>
    <?php

    foreach ($toys as $toy) {
        echo "<tr>";
        echo "<td>" . $toy->get('toyId') . "</td>";
        echo "<td>" . $toy->get('name') . "</td>";
        echo "<td>" . $toy->get('tPoints') .  "</td>";
        echo "<td>";
        // Fetch order items
        $toyMeta = $toy->getMetaData(); // This is an array
        $metaMap = [];

        foreach ($toyMeta as $meta) {
            $metaMap[$meta["Meta_Key"]] = $meta["Meta_Value"];
        }

        echo "Size: " . ($metaMap["Size"] ?? "N/A") . "<br>";
        echo "Weight: " . ($metaMap["Weight"] ?? "N/A") . "<br>";
        echo "Material: " . ($metaMap["Material"] ?? "N/A") . "<br>";
        echo "</td>";
        echo "<td><button>action</button></td>";
        echo "</tr>";
    }

    ?>
</table>
<?php 
include_once BASE_PATH . '/admin/includes/mediaUploadForm.php';
?>
