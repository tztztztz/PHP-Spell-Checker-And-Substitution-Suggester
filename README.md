# PHP-Spell-Checker-And-Substitution-Suggester

Wczesna wersja alfa klasy/pakietu do sprawdzania poprawności słów w tekście, oraz sugerowania prawidłowych słów w przypadku wykrycia słów nieistniejących w słowniku.

# Testowanie:

Uruchomić przez CLI plik suggester_test.php, wcześniej ściągając plik słownika np. ze strony https://sjp.pl/sl/growy/.

Pliku suggester_test.php zawiera testowy tekst z błędami, a następnie pokazuje błędne słowa wraz z sugestiami.

# TODO:

* Przyspieszenie całego mechanizmu przez:
  - wprowadzenie indeksów typu b-tree, przynajmniej dla rozpoznawania nieistniejących słów w słowniku
  - możliwość uruchomienia procesu w kilku wątkach na raz, najlepiej automatycznie: rozpoznanie ilości procesorów maszyny i uruchomienie wątków w ilości tych procesorów minus 1
  - wymyślić inne optymalizacje
  
