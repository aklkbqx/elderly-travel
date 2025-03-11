<div class='d-flex justify-content-between '>
    <div class='d-flex align-items-center gap-2 position-relative'>
        <input type="search" name='search' class='form-control ps-5' placeholder='ค้นหา' oninput="searchResult($(this))">
        <label for="" style='position: absolute;top: 0px;left: 8px;font-size: 25px;'>🔍</label>
        <button type='button' class='btn btn-teal'>ค้นหา</button>
    </div>
    <div class='d-flex gap-2 align-items-center'>
        <button data-bs-toggle='modal' data-bs-target="#addPlaces" type="button" class='btn btn-teal'>เพิ่ม+</button>
        <button data-bs-toggle='modal' data-bs-target="#manageCategories" type="button" class='btn btn-outline-teal'>จัดการหมวดหมู่สถานที่</button>
    </div>

    <div class="modal fade" id='manageCategories'>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">จัดการหมวดหมู่สถานที่</h4>
                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                </div>
                <div class="modal-body">
                    <div id='box-categories'>

                    </div>
                    <div class="form-floating flex-1">
                        <input type="text" class="form-control" placeholder='ชื่อหมวดหมู่' id='category_name' name='name' required>
                        <label for="ชื่อหมวดหมู่">ชื่อหมวดหมู่</label>
                    </div>
                </div>
                <div class="modal-footer d-flex w-100 align-items-center">
                    <div class="w-100 d-flex align-items-center gap-2">
                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                        <button type="button" onclick="addCategory()" class='btn btn-teal w-100'>เพิ่ม</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .image-preview {
            position: relative;
            width: 200px;
            height: 200px;
            display: inline-block;
            flex-shrink: 0;
        }

        .addImage:hover {
            label[for='select-image'] {
                background: #fff !important;
                color: black;
            }
        }
    </style>

    <div class="modal fade" id='addPlaces'>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <form action="../api/admin/managePlaces.php" class="modal-content" method='post' enctype='multipart/form-data'>
                <div class="modal-header">
                    <h4 class="modal-title">เพิ่มสถานที่</h4>
                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                </div>
                <div class="modal-body">
                    <div class="overflow-x-auto p-2" style="white-space: nowrap;">
                        <div id="image-preview-container" class="d-flex align-items-center gap-2 position-relative">
                            <div class='addImage'>
                                <input type="file" multiple name="image[]" id="select-image" accept="image/*" class="position-absolute border rounded-xl cursor-pointer opacity-0" style="width:200px;height:200px;">
                                <label for="select-image" class="d-flex align-items-center justify-content-center btn fs-1" style="width: 200px;height:200px;border:2px dashed #ffff">+</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 mt-3">
                        <div class="form-floating flex-1">
                            <input type="text" class="form-control" placeholder="ชื่อสถานที่" name="name" required>
                            <label for="ชื่อสถานที่">ชื่อสถานที่</label>
                        </div>
                        <div class="form-floating flex-1">
                            <textarea class="form-control" placeholder="ที่อยู่สถานที่" name="address" style="height: 100px;" required></textarea>
                            <label for="ที่อยู่สถานที่">ที่อยู่สถานที่</label>
                        </div>
                        <div class="form-floating flex-1">
                            <textarea class="form-control" placeholder="ผลต่อสุขภาพ" name="health" style="height: 100px;" required></textarea>
                            <label for="ผลต่อสุขภาพ">ผลต่อสุขภาพ</label>
                        </div>
                        <div class="form-floating flex-1">
                            <input type="number" class="form-control" placeholder="ราคา" name="price" required value="0" min='0'>
                            <label for="ราคา">ราคา</label>
                        </div>
                        <select name="categorySelector" class="form-select cursor-pointer"></select>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        let imageArray = [];

                        $("#select-image").change(function(event) {
                            handleFiles(event.target.files);
                        });

                        function handleFiles(files) {
                            $.each(files, function(index, file) {
                                if (file.type.startsWith("image/")) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const imageId = new Date().getTime();

                                        imageArray.push({
                                            id: imageId,
                                            file: file
                                        });

                                        $("#image-preview-container").prepend(`
                                            <div class="image-preview" data-id="${imageId}">
                                                <img src="${e.target.result}" class='rounded-xl object-fit-cover w-100 h-100'>
                                                <div class='remove-btn rounded-xl position-absolute bg-danger' style='top:10px;right:10px;' data-id='${imageId}'>
                                                    <button class="btn-close rounded-circle p-2 border"></button>
                                                </div>
                                            </div>
                                        `);
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });
                        }

                        $(document).on("click", ".remove-btn", function() {
                            const imageId = $(this).data("id");
                            $(this).parent().remove();
                            imageArray = imageArray.filter(img => img.id !== imageId);
                        });

                        $("#image-preview-container").sortable({
                            items: ".image-preview",
                            cursor: "grab",
                            update: function(event, ui) {
                                let sortedIds = $(this).sortable("toArray", {
                                    attribute: "data-id"
                                });
                                imageArray = sortedIds.map(id => imageArray.find(img => img.id == id));
                            }
                        });
                    });

                    const categorySelector = $("[name=categorySelector]");
                    const boxCategories = $("#box-categories");

                    const getCategory = () => {
                        $.ajax({
                            url: "../api/admin/managePlaceCategories.php?getCategories",
                            success: (response) => {
                                const json = JSON.parse(response);
                                if (json.length > 0) {
                                    let li = ``;
                                    let option = ``;
                                    json.map(({
                                        category_id,
                                        name
                                    }, index) => {
                                        li += `
                                                <li>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>${name}</div>
                                                        <button type="button" onclick="deleteCategory(${category_id})" class="btn btn-danger">ลบ</button>
                                                    </div>
                                                </li>
                                            `;
                                        option += `<option value="${category_id}">${name}</option>`;
                                    })
                                    boxCategories.html(`<ul class='d-flex flex-column gap-2'>${li}</ul>`);
                                    console.log(option);
                                    categorySelector.html(option);
                                } else {
                                    boxCategories.html(`<div class="text-center py-5">ยังไม่มีหมวดหมู่ในขณะนี้</div>`);
                                    categorySelector.html(`<option value="0">กรุณาทำการเพิ่มหมวดหมู่ก่อนทำการเพิ่มสถานที่</option>`);
                                }
                            }
                        });
                    }

                    getCategory()

                    const addCategory = () => {
                        $.ajax({
                            url: "../api/admin/managePlaceCategories.php?addCategory",
                            type: "POST",
                            data: {
                                name: $("#category_name").val()
                            },
                            success: (res) => {
                                $("#category_name").val(null)
                                getCategory()
                            }
                        })
                    }

                    const deleteCategory = (category_id) => {
                        $.ajax({
                            url: `../api/admin/managePlaceCategories.php?deleteCategory&category_id=${category_id}`,
                            type: "POST",
                            success: () => {
                                getCategory()
                            }
                        })
                    }
                </script>

                <div class="modal-footer d-flex w-100 align-items-center">
                    <div class="w-100 d-flex align-items-center gap-2">
                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                        <button type="submit" name='addPlace' class='btn btn-teal w-100'>เพิ่ม</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class='mt-2 h-100 overflow-auto'>
    <div class='row mb-5'>
        <?php $fetchPlaces = sql("SELECT * FROM places");
        if ($fetchPlaces->rowCount() > 0) {
            while ($place = $fetchPlaces->fetch()) { ?>
                <div class='col-lg-4 mb-2' data-search-item>
                    <div class="rounded-xl overflow-hidden border position-relative">
                        <img src="<?= imagePath('place_images', json_decode($place["images"])[0]) ?>" width="100%" height='250px;' class='object-fit-cover'>
                        <div class='badge text-bg-success position-absolute' style="top:10px; right:10px"><?= sql("SELECT * FROM place_categories WHERE category_id = ?", [$place["category_id"]])->fetch()["name"]; ?></div>
                        <div class='p-2 d-flex flex-column justify-content-between' style='height:160px'>
                            <div>
                                <h5 data-search-keyword="<?= $place["name"] ?><"><?= $place["name"] ?></h5>
                                <div style='overflow: hidden;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;'>ที่อยู่: <?= $place["address"] ?></div>
                                <div style='overflow: hidden;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;'>ผลต่อสุขภาพ: <?= $place["health"] ?></div>
                                <div style='overflow: hidden;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;'>ราคา: <?= $place["price"] ?></div>
                            </div>
                            <div class=''>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <button type='button' data-bs-toggle='modal' data-bs-target='#editPlace-<?= $place["place_id"] ?>' class='btn btn-warning border w-100'>แก้ไขสถานที่</button>
                                    <button type='button' data-bs-toggle='modal' data-bs-target='#deletePlaces-<?= $place["place_id"] ?>' class='btn btn-danger w-100'>ลบสถานที่</button>

                                    <div class="modal fade" id='editPlace-<?= $place["place_id"] ?>'>
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <form action="../api/admin/managePlaces.php?place_id=<?= $place["place_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                                <div class="modal-header">
                                                    <h4 class="modal-title">แก้ไขสถานที่</h4>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="overflow-x-auto p-2" style="white-space: nowrap;">
                                                        <div id="edit-image-preview-container-<?= $place["place_id"] ?>" class="d-flex align-items-center gap-2 position-relative">

                                                            <?php
                                                            $images = json_decode($place["images"]);
                                                            foreach ($images as $index => $image) {?>
                                                                <div class="image-preview" data-id="current">
                                                                    <img src="<?= imagePath("place_images", $image) ?>" class='rounded-xl object-fit-cover w-100 h-100'>
                                                                    <div class='remove-btn rounded-xl position-absolute bg-danger' style='top:10px;right:10px;' data-id='current'>
                                                                        <button class="btn-close rounded-circle p-2 border"></button>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                            <div class='addImage'>
                                                                <input type="file" multiple name="image[]" id="edit-select-image-<?= $place["place_id"] ?>" accept="image/*" class="position-absolute border rounded-xl cursor-pointer opacity-0" style="width:200px;height:200px;">
                                                                <label for="edit-select-image-<?= $place["place_id"] ?>" class="d-flex align-items-center justify-content-center btn fs-1" style="width: 200px;height:200px;border:2px dashed #ffff">+</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex flex-column gap-2 mt-3">
                                                        <div class="form-floating flex-1">
                                                            <input type="text" class="form-control" placeholder="ชื่อสถานที่" name="name" required value='<?= $place["name"] ?>'>
                                                            <label for="ชื่อสถานที่">ชื่อสถานที่</label>
                                                        </div>
                                                        <div class="form-floating flex-1">
                                                            <textarea class="form-control" placeholder="ที่อยู่สถานที่" name="address" style="height: 100px;" required><?= $place["address"] ?></textarea>
                                                            <label for="ที่อยู่สถานที่">ที่อยู่สถานที่</label>
                                                        </div>
                                                        <div class="form-floating flex-1">
                                                            <textarea class="form-control" placeholder="ผลต่อสุขภาพ" name="health" style="height: 100px;" required><?= $place["health"] ?></textarea>
                                                            <label for="ผลต่อสุขภาพ">ผลต่อสุขภาพ</label>
                                                        </div>
                                                        <div class="form-floating flex-1">
                                                            <input type="number" class="form-control" placeholder="ราคา" name="price" required value="<?= $place["price"] ?>" min='0'>
                                                            <label for="ราคา">ราคา</label>
                                                        </div>
                                                        <select name="categorySelector" class='form-select cursor-pointer'>
                                                            <?php
                                                            $fetchPlaceCategories = sql("SELECT * FROM place_categories");
                                                            while ($category = $fetchPlaceCategories->fetch()) { ?>
                                                                <option <?= $category["category_id"] == $place["category_id"] ? "selected" : "" ?> value="<?= $category["category_id"] ?>"><?= $category["name"] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex w-100 align-items-center">
                                                    <div class="w-100 d-flex align-items-center gap-2">
                                                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                        <button type="submit" name='editPlace' class='btn btn-teal w-100'>บันทึก</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).ready(function() {
                                            let editImageArray = {};

                                            $("[id^='edit-select-image-']").each(function() {
                                                const placeId = this.id.split('-').pop();
                                                editImageArray[placeId] = [];

                                                $(this).change(function(event) {
                                                    handleEditFiles(event.target.files, placeId);
                                                });
                                            });

                                            function handleEditFiles(files, placeId) {
                                                $.each(files, function(index, file) {
                                                    if (file.type.startsWith("image/")) {
                                                        const reader = new FileReader();
                                                        reader.onload = function(e) {
                                                            const imageId = new Date().getTime();

                                                            editImageArray[placeId].push({
                                                                id: imageId,
                                                                file: file
                                                            });

                                                            $('#edit-image-preview-container-' + placeId + ' .addImage').before(`
                                                                <div class="image-preview" data-id="${imageId}">
                                                                    <img src="${e.target.result}" class='rounded-xl object-fit-cover w-100 h-100'>
                                                                    <div class='remove-btn rounded-xl position-absolute bg-danger' style='top:10px;right:10px;' data-id='${imageId}'>
                                                                        <button class="btn-close rounded-circle p-2 border"></button>
                                                                    </div>
                                                                </div>
                                                            `);
                                                        };
                                                        reader.readAsDataURL(file);
                                                    }
                                                });
                                            }

                                            $(document).on("click", "[id^='edit-image-preview-container-'] .remove-btn", function() {
                                                const imageId = $(this).data("id");
                                                const container = $(this).closest("[id^='edit-image-preview-container-']");
                                                const placeId = container.attr("id").split("-").pop();

                                                $(this).parent().remove();
                                                if (editImageArray[placeId]) {
                                                    editImageArray[placeId] = editImageArray[placeId].filter(img => img.id !== imageId);
                                                }
                                            });

                                            $("[id^='edit-image-preview-container-']").each(function() {
                                                $(this).sortable({
                                                    items: ".image-preview",
                                                    cursor: "grab",
                                                    update: function(event, ui) {
                                                        const placeId = this.id.split("-").pop();
                                                        let sortedIds = $(this).sortable("toArray", {
                                                            attribute: "data-id"
                                                        });
                                                        if (editImageArray[placeId]) {
                                                            editImageArray[placeId] = sortedIds
                                                                .filter(id => id !== "current")
                                                                .map(id => editImageArray[placeId].find(img => img.id == id))
                                                                .filter(Boolean);
                                                        }
                                                    }
                                                });
                                            });
                                        });
                                    </script>

                                    <div class="modal fade" id='deletePlaces-<?= $place["place_id"] ?>'>
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="../api/admin/managePlaces.php?place_id=<?= $place["place_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                                <div class="modal-header">
                                                    <h4 class="modal-title">ลบสถานที่</h4>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4 class='text-center'>คุณแน่ใจที่จะทำการลบสถานที่นี้ใช่หรือไม่?</h4>
                                                </div>
                                                <div class="modal-footer d-flex w-100 align-items-center">
                                                    <div class="w-100 d-flex align-items-center gap-2">
                                                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                        <button type="submit" name='deletePlace' class='btn btn-danger w-100'>ลบ</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>
            <h5 class='text-center text-muted'>ยังไม่มีสถานที่ในขณะนี้...</h5>
        <?php } ?>
    </div>
</div>