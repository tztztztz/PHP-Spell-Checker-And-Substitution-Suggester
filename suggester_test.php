<?php
// Bootstrap for CLI
require 'vendor/inopx/SpellingErrorChecker.php';

// Example dictionary file address = https://sjp.pl/sl/growy/

$start = microtime(true);

$fa = 'slowa.txt';

$text = "Z uległością właściwą tylko martwym przedmiotom powaracają co rano do świata, w którym wciąz istnieja drzwi, zamki, łańcuchy, granice, paszporty i ograniczenia każdej miary czasu. Bolesnie odczuwają zgżytliwe tykanie zegara i szmer przesypującego się w Klepsydrzę piasku. Oddają się na żer słowom, które najperfidniej oddzielają nasze doświadczenie od istnienia. I nawet jeżeli Bóg w swojej dobroci ukazuje im refleksy nieskończoności, oni, pełni niedowierzania, dzielą ją ymyślnymi narzędziami na małe drobinki, które przesypują im się przez palce.";

$suggester = new \inopx\text\SpellingErrorChecker($fa);
  
$suggester->createPolishLanguageTextSuggestions($text);

print_r($suggester->suggestions);

$timeTook = microtime(true) - $start;

echo \PHP_EOL.'Execution in seconds: '.$timeTook;

