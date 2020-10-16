<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/DictionaryLookUp.php'; //class directory

$synonyms = DictionaryLookUp::requestAllSynonyms("arise revert danger", 4);
echo $synonyms;

// return an array of columns of search keywords and rows of synonyms
$synonymsArr = DictionaryLookUp::requestAllSynonymsV2("arise revert danger", 4);
 echo "<pre>";
 var_dump($synonymsArr);
 echo "</pre>";

// write to file
$fileLocation = "C:\Users/user/Desktop/Dictionary/test.txt";
file_put_contents($fileLocation, print_r($synonymsArr, true), FILE_APPEND | LOCK_EX);
