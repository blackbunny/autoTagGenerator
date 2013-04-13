<?php

/******************************************************************
Projectname:   Automatic Tag Generator
Version:       0.1
Author:        Murat Kucukosman
Last modified: 18 Mar 2013
Copyright (C): 2013 Murat Kucukosman, All Rights Reserved

Description:
This class can generates automatically tags for your
web pages based on the contents of your articles. This will
eliminate the tedious process of thinking what will be the best
keywords that suits your article. The basis of the keyword
generation is the number of iterations any word or phrase
occured within an article.

This automatic tags generator will create single words,
two word phrase and three word phrases. Single words will be
filtered from a common words list.

******************************************************************/

class autoTag {

	//declare variables
	//the site contents
	public $contents;
	public $encoding;
	//the generated keywords
	public $keywords;
	//minimum word length for inclusion into the single word
	//metakeys
	public $wordLengthMin;
	public $wordOccuredMin;
	//minimum word length for inclusion into the 2 word
	//phrase metakeys
	public $word2WordPhraseLengthMin;
	public $phrase2WordLengthMinOccur;
	//minimum word length for inclusion into the 3 word
	//phrase metakeys
	public $word3WordPhraseLengthMin;
	//minimum phrase length for inclusion into the 2 word
	//phrase metakeys
	public $phrase2WordLengthMin;
	public $phrase3WordLengthMinOccur;
	//minimum phrase length for inclusion into the 3 word
	//phrase metakeys
	public $phrase3WordLengthMin;

	public function autoTag($params, $encoding)
	{
		//get parameters
		$this->encoding = $encoding;
		mb_internal_encoding($encoding);
                $params['content'] = html_entity_decode($params['content'],1,'UTF-8');
		$this->contents = $this->replace_chars($params['content']);

		// single word
		$this->wordLengthMin = $params['min_word_length'];
		$this->wordOccuredMin = $params['min_word_occur'];

		// 2 word phrase
		$this->word2WordPhraseLengthMin = $params['min_2words_length'];
		$this->phrase2WordLengthMin = $params['min_2words_phrase_length'];
		$this->phrase2WordLengthMinOccur = $params['min_2words_phrase_occur'];

		// 3 word phrase
		$this->word3WordPhraseLengthMin = $params['min_3words_length'];
		$this->phrase3WordLengthMin = $params['min_3words_phrase_length'];
		$this->phrase3WordLengthMinOccur = $params['min_3words_phrase_occur'];

		//parse single, two words and three words

	}

	public function get_keywords()
	{
		$keywords = $this->parse_words().$this->parse_2words().$this->parse_3words();
		return substr($keywords, 0, -2);
	}

	//turn the site contents into an array
	//then replace common html tags.
	function replace_chars($content)
	{
		//convert all characters to lower case
		$content = mb_strtolower($content);
		//$content = mb_strtolower($content, "UTF-8");
		$content = strip_tags($content);

		$punctuations = array(',', ')', '(', '.', "'", '"',
		'<', '>', '!', '?', '/', '-',
		'_', '[', ']', ':', '+', '=', '#',
		'$', '&quot;', '&copy;', '&gt;', '&lt;', 
		'&nbsp;', '&trade;', '&reg;', ';', '•',
		chr(10), chr(13), chr(9));

		$content = str_replace($punctuations, " ", $content);
		// replace multiple gaps
		$content = preg_replace('/ {2,}/si', " ", $content);

		return $content;
	}

	//single words Tags
	public function parse_words()
	{
		//list of commonly used words
		// this can be edited to suit your needs
		$common = array("ve", "veya", "da", "de", "mi", "misin", "mısın", "mı", 
                    "ya", "sayı", "öğle", "akşam", "varış", "rezervasyon", "kodu", "kodunun", "iade", 
                    "yapmanız", "uzatabilirsiniz", "kadar", "iptali", "müsaitlik", "tatillerim", "tatili", "sonraki", 
                    "sonra", "giriş", "ulaşabilirsiniz", "kişilik", "kampanyalarda", "içerisinde",
                    "hemen","hattı’nı","hattı","günün","görevlisine","geçerlidir","gerekiyor","farklı","durumuna",
                    "dilediğiniz","bölümünden","başka","ancak","aldıktan","alabilir","adresine","yine","otele","için","ise",
                    "ile","göre","cep","bir","iki","üç","dört","beş","odada","gün","gönderdiğimiz","dahil",
                    "ara","tabağı","satın","alarak","muhteşem","hem","sunmaktadır","sadece","saat","ortasında","ortam","olarak","misafirlerine",
                    "mesafede","körfezin","kupon","sabiha","gökçen","atatürk","dakika","dünya",
                    "en", "bu", "önceden", "yoktur", "yapılmaz", "yapılamaz", "yaptırmanız", "vergileri", "uçuşunda",
                    "uçuşlar", "uçuşa", "telefonu", "tegel", "tarihli", "sona", "sitedeki", "servisimiz", 
                    "sadece", "olup", "olduğunuz", "numaralı", "kuponun", "kullanılabilir", "kodunuz", "kilo", "kayıtlı",
                    "kampanyalarla", "kala", "isim", "iadesi", "güvenlik", "gönderilecektir", "geç", "gerekmektedir", 
                    "fırsatlarım", "fiyata", "ermektedir", "diğer", "değişikliği", "değişiklik", "dahildir", 
                    "büyük", "bulunmamaktadır", "birleştirilemez", "biletinin","arayarak", "ister","dolu",
                    "çok", "çarşısı", "yakın", "turvan", "satın", "sarnıcı", "sarayı", "sadece", 
                    "size", "sevdiklerinizle", "salatası", "olarak", "nefis", "lezzet","birbirinden", "balık’ta",
                    "üyelerine", "yer", "yakala", "uzaklıktadır", "tl’den", "tek", "sık", "seçenekleriyle", 
                    "sadece", "otel’de", "otelin", "olup","mesafede", "merkezinde",  "kişi", "indirimlerle", "hizmet", 
                    "gibi", "fiyatlarla", "büfe", "bulunan", "bilgi", "başlayan", "açık", "alır",
                    "ücretli", "özelliği", "özellikleri", "özel", "çamlıca’da",  "özel", "zengin", 
                    "yerel", "sigara", "saç", "oturma", "direkt",  "banyoda", "zemin", "yüksek", "yer", 
                    "yayınını", "yayını", "yakasına", "tüm", "tacı", "sunuluyor", "standart", "ssc", "spot", 
                    "spa’nın", "seçeneği", "seçenekler", "servisi", radyo, queen, olarak, odalarının, oda, mini, mermer, malzemeleri, makinesi, led, kurutma, kuru, konforu, konaklama, kişi, kasası, kaplı, kahvaltı, kablosuz, içilemeyen, içilebilen, istanbul’un, internet, indirim, ikramlar, hızda, hotel, hizmeti, havalandırma, hakim, güzide, gece, ekran, döşenmiş, durum, dinleyebilme, deluxe, dekorasyon, co’ya, butonu, buklet, başı, baş, bar, banyoda, banyo, aydınlatma, avrupa, anadolu, alan, ahşap, acil

                    );
		//create an array out of the site contents
		$s = explode(" ", $this->contents);
		//initialize array
		$k = array();
		//iterate inside the array
		foreach( $s as $key=>$val ) {
			//delete single or two letter words and
			//Add it to the list if the word is not
			//contained in the common words list.
			if(mb_strlen(trim($val)) >= $this->wordLengthMin  && !in_array(trim($val), $common)  && !is_numeric(trim($val))) {
				$k[] = trim($val);
			}
		}
		//count the words
		$k = array_count_values($k);
		//sort the words from
		//highest count to the
		//lowest.

		$occur_filtered = $this->occure_filter($k, $this->wordOccuredMin);
		arsort($occur_filtered);

		$imploded = implode(", ", $occur_filtered);
		//release unused variables
		unset($k);
		unset($s);

		return $imploded;
	}

	public function parse_2words()
	{
		//create an array out of the site contents
		$x = explode(" ", $this->contents);
		//initilize array

		//$y = array();
		for ($i=0; $i < count($x)-1; $i++) {
			//delete phrases lesser than 5 characters
			if( (mb_strlen(trim($x[$i])) >= $this->word2WordPhraseLengthMin ) && (mb_strlen(trim($x[$i+1])) >= $this->word2WordPhraseLengthMin) )
			{
				$y[] = trim($x[$i])." ".trim($x[$i+1]);
			}
		}

		//count the 2 word phrases
		$y = array_count_values($y);

		$occur_filtered = $this->occure_filter($y, $this->phrase2WordLengthMinOccur);
		//sort the words from highest count to the lowest.
		arsort($occur_filtered);

		$imploded = implode(", ", $occur_filtered);
		//release unused variables
		unset($y);
		unset($x);

		return $imploded;
	}

	public function parse_3words()
	{
		//create an array out of the site contents
		$a = split(" ", $this->contents);
		//initilize array
		$b = array();

		for ($i=0; $i < count($a)-2; $i++) {
			//delete phrases lesser than 5 characters
			if( (mb_strlen(trim($a[$i])) >= $this->word3WordPhraseLengthMin) && (mb_strlen(trim($a[$i+1])) > $this->word3WordPhraseLengthMin) && (mb_strlen(trim($a[$i+2])) > $this->word3WordPhraseLengthMin) && (mb_strlen(trim($a[$i]).trim($a[$i+1]).trim($a[$i+2])) > $this->phrase3WordLengthMin) )
			{
				$b[] = trim($a[$i])." ".trim($a[$i+1])." ".trim($a[$i+2]);
			}
		}

		//count the 3 word phrases
		$b = array_count_values($b);
		//sort the words from
		//highest count to the
		//lowest.
		$occur_filtered = $this->occure_filter($b, $this->phrase3WordLengthMinOccur);
		arsort($occur_filtered);

		$imploded = implode(", ", $occur_filtered);
		//release unused variables
		unset($a);
		unset($b);

		return $imploded;
	}

	public function occure_filter($array_count_values, $min_occur)
	{
		$occur_filtered = array();
		foreach ($array_count_values as $word => $occured) {
			if ($occured >= $min_occur) {
				$occur_filtered[] = $word;
			}
		}

		return $occur_filtered;
	}


}
?>
