echo "<H1>Input - text</H1>";
echo $data;

//this the actual application.
include('autoTagGenerator.php');

$params['content'] = $data; //page content
//set the length of keywords you like
$params['min_word_length'] = 5;  //minimum length of single words
$params['min_word_occur'] = 2;  //minimum occur of single words

$params['min_2words_length'] = 3;  //minimum length of words for 2 word phrases
$params['min_2words_phrase_length'] = 10; //minimum length of 2 word phrases
$params['min_2words_phrase_occur'] = 2; //minimum occur of 2 words phrase

$params['min_3words_length'] = 3;  //minimum length of words for 3 word phrases
$params['min_3words_phrase_length'] = 10; //minimum length of 3 word phrases
$params['min_3words_phrase_occur'] = 2; //minimum occur of 3 words phrase

$keyword = new autoTag($params, "UTF-8");

echo "<H1>Output - keywords</H1>";
//echo $keyword->get_keywords();
echo "<H2>words</H2>";
echo $keyword->parse_words();
echo "<H2>2 words phrase</H2>";
echo $keyword->parse_2words();
echo "<H2>2 words phrase</H2>";
echo $keyword->parse_3words();

echo "<H2>All together</H2>";
echo $keyword->get_keywords();
