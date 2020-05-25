<?php


require '_lantera/utils.php';


function createSku($name)
{
    $sku = md5($name);
    return $sku;
}

$row = 1;
$result = [['name', 'stock_status', 'sku', 'category']];
if (($handle = fopen("Item list.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $name = $data[0];
        $stockStatus = $data[1];
        $sku = createSku($name);
        $category = 'glossy-plastic';
        $result[] = [$name, $stockStatus, $sku, $category];
    }
    fclose($handle);
}

$fp = fopen('file.csv', 'w');

foreach ($result as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);

d('-da');
