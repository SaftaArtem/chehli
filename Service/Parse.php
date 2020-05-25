<?php


namespace Service;

use DOMDocument;

class Parse
{
    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }


    public function parse()
    {
        $doc = new DOMDocument();
        $document = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $doc->loadHTMLFile($this->fileName);
        libxml_use_internal_errors($internalErrors);


        $elements = $doc->getElementsByTagName("ul");
        $result = [];
        foreach ($elements as $element) {
            if ($element->getAttribute('class') == 'jq-ul') {
                $string = $element->nodeValue;
                $del = '															';
                $trimmedVal = explode($del, $string);
                unset($trimmedVal[0]);
                $out = array_filter($trimmedVal, function ($val) {
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
                    $modelZero = trim($model[0]);

                    if ($modelZero !== '' && $modelZero != 'Попсокеты') {
                        $key = $model[0];
                        $result[$key] = $trimmedVal;
                    }
                }
            }
        }
        return $result;
    }

}