<?php

/**
 * Display form to add equipment
 */

/* Form of equipment part */
echo '<div class="boxStyle">';
echo '<form enctype="multipart/form-data" action="process/productAddProcess.php" method="POST">';
echo '<input type="hidden" name="productForm">';
echo '<div><label>Name: </label><br><input type="text" name="nameEquipment" required></div>';
echo '<div><label>Brand: </label><br><input type="text" name="brandEquipment" required></div>';
echo '<div><label>Stock: </label><br><input type="number" name="stockEquipment" required></div>';
echo '<div><label>Price: </label><br><input type="number" name="priceEquipment" required></div>';
echo '<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />';
echo '<div><label>Image: </label><br><input type="file" name="imageEquipment" required></div>';
echo '<div><label>Visible: </label><br><select name="visibleEquipment"><option value="1">True</option><option value="0">False</option></select></div>';

/* Selecting correct form */
if(isset($_GET['type'])) {
    switch ($_GET['type']) {
        case 'body':include(VIEW_BODY_FORM); break;
        case 'lens':include(VIEW_LENS_FORM); break;
        case 'tripod':include(VIEW_TRIPOD_FORM); break;
        default:
    }
}
echo'<button type="submit" class="btn-success">Upload</button>';
echo '</form></div>';
