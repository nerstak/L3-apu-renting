<?php
echo '<main><div id="searchContainer" class="container"><form name="searchForm" method="GET" class="boxStyle">';
$search = isset($_GET['search']) ? 'value="' . $_GET['search'] .'"': '';
echo '<input type="text" name="search" placeholder="Search" ' . $search . '>';

echo '<select name="type"><option value="">Type of product</option>';
$select = isset($_GET['type']) && $_GET['type'] == 'body' ? "selected" : "";
echo '<option value="body" ' . $select . '>Body</option>';
$select = isset($_GET['type']) && $_GET['type'] == 'lens' ? "selected" : "";
echo '<option value="lens" ' . $select . '>Lens</option>';
$select = isset($_GET['type']) && $_GET['type'] == 'tripod' ? "selected" : "";
echo '<option value="tripod"' . $select . '>Tripod</option>';
echo '</select><button class="btn-primary" type="submit">Search</button></form></div>';