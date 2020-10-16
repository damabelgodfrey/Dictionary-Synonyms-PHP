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
}
