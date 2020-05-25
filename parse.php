<?php
require '_lantera/utils.php';


$doc = new DOMDocument();


$document = new \DOMDocument('1.0', 'UTF-8');

// set error level
$internalErrors = libxml_use_internal_errors(true);

$doc->loadHTMLFile('test.html');


// Restore error level
libxml_use_internal_errors($internalErrors);


$elements = $doc->getElementsByTagName("ul");
$result = [];
foreach ($elements as $element) {
    if ($element->getAttribute('class') == 'jq-ul') {
        $string = $element->nodeValue;

        $del = '															';
        $trimmedVal = explode($del, $string);
        unset($trimmedVal[0]);
//        d($trimmedVal);
        $out = array_filter($trimmedVal, function ($val){
            return trim($val);
        });
//        $delimiter = '															';
//        d(explode(' ', preg_replace("/[[:^print:]]/", "", $string)));
//        $trimmedVal = explode($delimiter, preg_replace("/[[:^print:]]/", "", $string));
//        d($trimmedVal);
        unset($trimmedVal[0]);
        if ($trimmedVal) {
            $model = $trimmedVal[1];

            $model = explode(' ', $model);

            if ($model[0] !== '' && $model[0] != 'Попсокеты') {
                $key = $model[0];
                $result[$key] = $trimmedVal;
            }
        }
    }
}




// import to wp_term
$cats = [];
foreach ($result as $parent => $subcat) {
    $cats[] = $parent;
    foreach ($subcat as $subca) {
        $cats[] = $subca;
    }
}

foreach ($cats as $cat) {

    $newValues[] = "('".$cat."', '$cat'".")";
}

$newValues = implode(',', $newValues);



$sql = "INSERT INTO `wp_terms`
                    (`name`, `slug`)
                    VALUES $newValues
                    ON DUPLICATE KEY UPDATE `value`= VALUES(value)";





d($result);
d(count($elements));
ci($doc);




