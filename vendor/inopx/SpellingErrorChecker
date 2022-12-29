<?php
namespace inopx\text;

/**
 * @author Tomasz Zadora
 */
class SpellingErrorChecker {
  
  /**
   * Dictionary file address where every line contains one and only one word.
   * 
   * @var string
   */
  protected $dictionaryFileAddress;
  
  /**
   * Words to analyse
   * 
   * @var string[]
   */
  protected $words = [];
  
  /**
   * Suggestions
   * 
   * @var array
   */
  public $suggestions = [];
  
  /**
   * Constructor with dictionary file address where every line contains one and only one word.
   * 
   * @param string $dictionaryFileAddress - file address of the dictionary
   */
  public function __construct($dictionaryFileAddress) {
    
    $this->dictionaryFileAddress = $dictionaryFileAddress;
    
  }
  
  /**
   * Multibyte Levenshtein implementation in PHP - much slower than built in non-multibyte levenshtein function
   * 
   * @param string $str1  - the first string 
   * @param string $str2  - the second string 
   * @return int
   */
  public static function levenshtein_php($str1, $str2){
    
    $length1 = mb_strlen( $str1, 'UTF-8');
    $length2 = mb_strlen( $str2, 'UTF-8');
    if( $length1 < $length2) return self::levenshtein_php($str2, $str1);
    if( $length1 == 0 ) return $length2;
    if( $str1 === $str2) return 0;
    $prevRow = range( 0, $length2);
    $currentRow = array();
    for ( $i = 0; $i < $length1; $i++ ) {
        $currentRow=array();
        $currentRow[0] = $i + 1;
        $c1 = mb_substr( $str1, $i, 1, 'UTF-8') ;
        for ( $j = 0; $j < $length2; $j++ ) {
            $c2 = mb_substr( $str2, $j, 1, 'UTF-8' );
            $insertions = $prevRow[$j+1] + 1;
            $deletions = $currentRow[$j] + 1;
            $substitutions = $prevRow[$j] + (($c1 != $c2)?1:0);
            $currentRow[] = min($insertions, $deletions, $substitutions);
        }
        $prevRow = $currentRow;
    }
    return $prevRow[$length2];
    
  }
  
  /**
   * Create suggestions for polish language text in the $this->suggestions variable
   * 
   * @param string $text  - text for checking spelling
   * @param int $maxSuggestinsPerWord - maximum suggestions per word
   */
  public function createPolishLanguageTextSuggestions($text, $maxSuggestinsPerWord = 10) {
    
    if (empty($text)) {
      throw new \Exception("Text is empty");
    }
    
    if (!is_numeric($maxSuggestinsPerWord) || $maxSuggestinsPerWord < 1) {
      $maxSuggestinsPerWord = 1;
    }
    
    
    
    $wordsTmp = preg_split('/\s+/imsu', $text);
    
    // Resetting variables
    $this->words = [];
    $this->suggestions = [];
    
    ////////////
    // Cleaning text
    foreach ($wordsTmp as $word) {
      
      $word = preg_replace('/[^\p{L}]/imsu', '', $word);
      
      // Words shorter than 2 characters are not analysed
      if (empty($word) || mb_strlen($word) < 2) {
        continue;
      }
      
      // Word length
      $len =  mb_strlen($word);
      
      // Maximum levenshtein score to make a suggestion
      $len < 8 ? $max = 1 : $max = round($len*0.2);
      
      // oryginal word (may be uppercase) = [lower case word, length of the word, maximum levenshtein score to make a suggestion]
      $this->words[$word] = [mb_strtolower($word), mb_strlen($word), $max];
      
    } // foreach ($wordsTmp as $word) {
    
    // Empty word list
    if (empty($this->words)) {
      return;
    }
    
    
    ////////////
    // Opening the dictionary file
    if (!file_exists($this->dictionaryFileAddress)) {
      throw new \Exception("Dictionary file does not exists.");
    }
    
    $fp = fopen($this->dictionaryFileAddress, 'r');
    
    if ($fp === false) {
      throw new \Exception("Error opening dictionary file");
    }
    
    ////////////
    // Searching for the words that does not exists in the dictionary
    while ($line = fgets($fp)) {
      
      $line = trim($line); // trimming end of line
      
      foreach ($this->words as $key => $data) {
        
        // Word exists in the dictionary
        if ($line == $data[0]) {
          unset($this->words[$key]);
        }
        
      }
      
      // Each word from the text exists in the dictionary
      if (empty($this->words)) {
        break;
      }
      
    } // while ($line = fgets($fp)) {
    
    fclose($fp);
    
    // Each word from the text exists in the dictionary
    if (empty($this->words)) {
      return;
    }
    
    ////////////
    // Searching for suggestions
    $fp2 = fopen($this->dictionaryFileAddress, 'r');
    
    if ($fp2 === false) {
      throw new \Exception("Error opening dictionary file");
    }
    
    while ($line = fgets($fp2)) {
      
      $line = trim($line); // trimming end of line
      $lineLen = mb_strlen($line);
      
      foreach ($this->words as $key => $data) {
        
        // Length checking
        if ($data[1]+$data[2] < $lineLen || $data[1]-$data[2] > $lineLen) {
          continue;
        }
        
        // Checking basic non-multibyte levenshtein
        $p = levenshtein($line, $data[0]);
        
        // Checking maximum score
        if ($p > $data[2]) {
          continue;
        }
        
        // Cerrecting score using php multibyte levenshtein
        $p = self::levenshtein_php($line, $data[0]);
        
        if (!isset($this->suggestions[$key])) {
          $this->suggestions[$key] = [];
        }
        
        $this->suggestions[$key][$line] = $p;
        
        // Sorting - best suggestions at the top
        asort($this->suggestions[$key]);
        
        // Too many suggestions? Remove for the end of the array
        if (count($this->suggestions[$key]) > $maxSuggestinsPerWord) {
          array_pop($this->suggestions[$key]);
        }
        
      } // foreach ($this->words as $key => $data) {
      
    } // while ($line = fgets($fp2)) {
    
    fclose($fp2);
    
  }
  
}
