<?php
require '_lantera/utils.php';

$data = [
    ['model']
];
$data[] = ["Iphone"];
$data[] = ["Samsung"];
$data[] = ["Xiaomi"];
$data[] = ["Huawei"];
$data[] = ["Meizu"];
$data[] = ["Lenovo"];
$data[] = ["Acer"];
$data[] = ["Asus"];
$data[] = ["HTC"];
$data[] = ["Motorola"];
$data[] = ["Nokia"];
$data[] = ["Sony"];


$fp = fopen('category.csv', 'w');

foreach ($data as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);
d($data);