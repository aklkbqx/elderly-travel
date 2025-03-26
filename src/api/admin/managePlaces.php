<?php
require_once("../../config.php");

$uploadPath = "../../images/place_images/";

function uploadImages($files)
{
    global $uploadPath;
    $imageArray = [];

    if (!isset($files["name"]) || !is_array($files["name"])) {
        return [];
    }

    foreach ($files["name"] as $key => $name) {
        $tmp_name = $files["tmp_name"][$key];

        if (!empty($name)) {
            $extension = pathinfo($name)["extension"];
            $newImageName = uniqid() . "." . $extension;
            if (move_uploaded_file($tmp_name, $uploadPath . $newImageName)) {
                $imageArray[] = $newImageName;
            }
        }
    }

    return $imageArray;
}

if (isset($_REQUEST["addPlace"])) {
    $name = $_POST["name"];
    $address = $_POST["address"];
    $health = $_POST["health"];
    $price = $_POST["price"];
    $category = $_POST["categorySelector"];

    if ($category == 0 || $category == null) {
        msg("warning", "คำเตือน!", "กรุณาเพิ่มหมวดหมู่ก่อน!", $_SERVER["HTTP_REFERER"]);
    }
    try {
        if (!empty($_FILES["image"]["name"][0])) {
            $images = uploadImages($_FILES["image"]);
        } else {
            throw new PDOException("กรุณาใส่รูปภาพสถานที่!");
        }

        sql("INSERT INTO places (name, address, health, price, images, category_id) VALUES (?, ?, ?, ?, ?, ?)", [
            $name,
            $address,
            $health,
            $price,
            json_encode($images),
            $category
        ]);

        msg("success", "สำเร็จ!", "เพิ่มสถานที่สำเร็จ!", $_SERVER["HTTP_REFERER"]);
    } catch (PDOException $e) {
        msg("danger", "เกิดข้อผิดพลาด", $e->getMessage(), $_SERVER["HTTP_REFERER"]);
    }
}

if (isset($_REQUEST["editPlace"]) && isset($_GET["place_id"])) {
    $place_id = $_GET["place_id"];
    $row = sql("SELECT * FROM places WHERE place_id = ?", [$place_id])->fetch();

    $name = $_POST["name"];
    $address = $_POST["address"];
    $health = $_POST["health"];
    $price = $_POST["price"];
    $category = $_POST["categorySelector"];

    try {
        $images = json_decode($row["images"], true) ?: [];

        if (!empty($_FILES["image"]["name"][0])) {
            foreach ($images as $oldImage) {
                if (file_exists($uploadPath . $oldImage)) {
                    unlink($uploadPath . $oldImage);
                }
            }

            $images = uploadImages($_FILES["image"]);
        }

        sql("UPDATE places SET name=?, address=?, health=?, price=?, images=?, category_id=? WHERE place_id=?", [
            $name,
            $address,
            $health,
            $price,
            json_encode($images),
            $category,
            $place_id
        ]);

        msg("success", "สำเร็จ!", "แก้ไขสถานที่สำเร็จ!", $_SERVER["HTTP_REFERER"]);
    } catch (PDOException $e) {
        msg("danger", "เกิดข้อผิดพลาด", $e->getMessage(), $_SERVER["HTTP_REFERER"]);
    }
}

if (isset($_REQUEST["deletePlace"]) && isset($_GET["place_id"])) {
    $place_id = $_GET["place_id"];
    $row = sql("SELECT * FROM places WHERE place_id = ?", [$place_id])->fetch();

    try {
        $images = json_decode($row["images"], true) ?: [];
        foreach ($images as $image) {
            if (file_exists($uploadPath . $image)) {
                unlink($uploadPath . $image);
            }
        }

        sql("DELETE FROM places WHERE place_id = ?", [$place_id]);

        msg("success", "สำเร็จ!", "ลบสถานที่สำเร็จ!", $_SERVER["HTTP_REFERER"]);
    } catch (PDOException $e) {
        msg("danger", "เกิดข้อผิดพลาด", $e->getMessage(), $_SERVER["HTTP_REFERER"]);
    }
}
