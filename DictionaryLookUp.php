<?php
/**
* @author Godfrey Damabel
* @licence: free for eductional purpose. for commercial use contact author for license and/or permission
* @info: get Dictionary-LookUp for words synonyms
* @return: Synonyms for an inputed word strings
*/

class DictionaryLookUp
{
  /**
  * var @ $apikey @ $language @$endpoint @$info
  */
  private static $apikey = "aIlI0krLFXTDoHG58XSw"; // : replace test_only with your own key
  private static $language = "en_US"; // you can use: en_US, es_ES, de_DE, fr_FR, it_IT
  private static $endpoint = "http://thesaurus.altervista.org/thesaurus/v1";
  private static $info;
  // send request to thesaurus dictionary server for synonyms lookup
  //@ return result for further processing
  private static function sendRequest($word){
    $ch = curl_init();
    $la = self::$language;
    $key = self::$apikey;
    $endpoint = self::$endpoint;
    curl_setopt($ch, CURLOPT_URL, "$endpoint?word=".urlencode($word)."&language=$la&key=$key&output=json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    self::$info = curl_getinfo($ch);
    curl_close($ch);
    return $result;
  }

  // get synonyms for every key world in input
  // return specified no of synonyms including inputed word.
  public static function requestAllSynonyms($keywords,$noOfSynonyms) : string{
    $wordArray = explode(' ', "$keywords");
    $result = array();
    foreach ($wordArray as $word) {
      $returnResult =  self:: sendRequest($word); //can look up local repository of synonyms before thesaurus lookup.
      $synonymsArray= self::processDictionaryOutput($returnResult, $noOfSynonyms);
      $result[] = $word; //include searched keyword
        foreach ($synonymsArray as $syn) {
          $result[] = $syn;
        }
    }
    $s_result = implode(' ', $result);
    return $s_result;
  }

  public static function requestAllSynonymsV2($keywords,$noOfSynonyms) : array{
    $wordArray = explode(' ', "$keywords");
    $resultArray = array();
    foreach ($wordArray as $word) {
      $returnResult =  self:: sendRequest($word); //can look up local repository of synonyms before thesaurus lookup.
      $synonymsArray= self::processDictionaryOutput($returnResult, $noOfSynonyms);
      $result = array();
        foreach ($synonymsArray as $syn) {
          $result[] = $syn;
        }
        $resultArray[$word] = $result;
    }
    return $resultArray;
  }
  //process result return from server
  //extract key data
  private static function processDictionaryOutput($returnResult,$noOfSynonyms) : array{
    $returnInfo = self::$info;
    $similarTerm = array();
    if($returnInfo['http_code'] == 200) {
      $my_wordList = array();
      $results = json_decode($returnResult, true);
      foreach ($results["response"] as $wordList) {
        $my_wordList[] = explode('|',$wordList["list"]["synonyms"]);
      }
      $genericTerm = array();
      $otherTerm = array();
      foreach ($my_wordList as $key =>$wordArray) {
        foreach ($wordArray as $key2 =>$word) {
          if(strpos($word, 'similar term')){
            $st= str_ireplace('(similar term)', '', $word);
            if(str_word_count($st) == 1){ //discard when 2 words is returned as synonyms result
              $genericTerm[] = $st;
            }
          }else if(strpos($word, 'generic term')){
            $st= str_ireplace('(generic term)', '', $word);
            if(str_word_count($st) == 1){
              $genericTerm[] = $st;
            }
          }else{
            if(str_word_count($word) == 1){
              $otherTerm[]= $word;
            }
          }
        }
      }
      if(count($similarTerm) < 3){
        $similarTerm = array_merge($similarTerm,$otherTerm);
      }
      if(count($similarTerm) > $noOfSynonyms){ //specify number of synonyms to return
        $similarTerm = array_slice($similarTerm, 0, $noOfSynonyms);
      }
      return $similarTerm;
    }else {
      return $similarTerm;
    }
  }
}
