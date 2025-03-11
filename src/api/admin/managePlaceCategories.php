<?php
require_once("../../config.php");

if (isset($_GET["getCategories"])) {
    $categories = [];
    $fetchAll = sql("SELECT * FROM place_categories");
    if ($fetchAll->rowCount() > 0) {
        while ($category = $fetchAll->fetch()) {
            $categories[] = [
                "category_id" => $category["category_id"],
                "name" => $category["name"]
            ];
        }
    } else {
        $categories = [];
    }

    echo json_encode($categories);
}

if (isset($_GET["addCategory"])) {
    $name = $_POST["name"];
    sql("INSERT INTO place_categories(name) VALUES(?)", [$name]);
}

if (isset($_GET["deleteCategory"]) && isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    sql("DELETE FROM place_categories WHERE category_id = ?", [$category_id]);
}
