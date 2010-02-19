<?php

/*
Plugin Name: Wordpress Translation Plugin (en-h)
Plugin URI: http://www.carlosquiles.com/indo-european-language-blog/wordpress-translation-plugin/
Description: Provides links to different automatic translations
Version: 1.4 (27/2/2009)
Author: Carlos Quiles
Author URI: http://dnghu.org/
*/

function transdukete() {

$url = $_SERVER["PHP_SELF"];
if ( $_SERVER['QUERY_STRING'] <> '') {
   $url .= "?" . $_SERVER['QUERY_STRING'];
 }
 
$url = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
$url = str_replace(":", "%3A", $url);
$url = str_replace("/", "%2F", $url);
$url = str_replace("&", "%26", $url);
?>
<div class="dnghu">Translate into:
<a lang="en" xml:lang="en" href="#">English</a> •
<?php echo "<a lang=\"ar\" xml:lang=\"ar\" title=\"Araby\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Car&amp;u=$url\" rel=\"nofollow\">"; ?>العربية</a> • 
<?php echo "<a lang=\"bg\" xml:lang=\"bg\" title=\"Bulgarski\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cbg&amp;u=$url\" rel=\"nofollow\">"; ?>Български</a> • 
<?php echo "<a lang=\"ca\" xml:lang=\"ca\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cca&amp;u=$url\" rel=\"nofollow\">"; ?>Català</a> • 
<?php echo "<a lang=\"cs\" xml:lang=\"cs\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Ccs&amp;u=$url\" rel=\"nofollow\">"; ?>Česky</a> • 
<?php echo "<a lang=\"cy\" xml:lang=\"cy\" href=\"http://www.tranexp.com:2000/InterTran?url=$url&amp;type=text&amp;text=&amp;from=eng&amp;to=wel\" rel=\"nofollow\">"; ?>Cymraeg</a> • 
<?php echo "<a lang=\"da\" xml:lang=\"da\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cda&amp;u=$url\" rel=\"nofollow\">"; ?>Dansk</a> • 
<?php echo "<a lang=\"de\" xml:lang=\"de\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cde&amp;u=$url\" rel=\"nofollow\">"; ?>Deutsch</a> • 
<?php echo "<a lang=\"et\" xml:lang=\"et\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cet&amp;u=$url\" rel=\"nofollow\">"; ?>Eesti</a> • 
<?php echo "<a lang=\"el\" xml:lang=\"el\" title=\"Ellenika\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cel&amp;u=$url\" rel=\"nofollow\">"; ?>Ελληνικά</a> • 
<?php echo "<a lang=\"es\" xml:lang=\"es\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Ces&amp;u=$url\" rel=\"nofollow\">"; ?>Español</a> • 
<?php echo "<a lang=\"fa\" xml:lang=\"fa\" title=\"Fārsī\" href=\"http://babel.gts-translation.com/geturl?direction=47&amp;input_url=$url\" rel=\"nofollow\">"; ?>فارسی</a> • 
<?php echo "<a lang=\"fr\" xml:lang=\"fr\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cfr&amp;u=$url\" rel=\"nofollow\">"; ?>Français</a> • 
<?php echo "<a lang=\"gl\" xml:lang=\"gl\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cgl&amp;u=$url\" rel=\"nofollow\">"; ?>Galego</a> • 
<?php echo "<a lang=\"hi\" xml:lang=\"hi\" title=\"Hindī\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Chi&amp;u=$url\" rel=\"nofollow\">"; ?>हिन्दी</a> • 
<?php echo "<a lang=\"hr\" xml:lang=\"hr\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Chr&amp;u=$url\" rel=\"nofollow\">"; ?>Hrvatski</a> • 
<?php echo "<a lang=\"id\" xml:lang=\"id\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cid&amp;u=$url\" rel=\"nofollow\">"; ?>Bahasa Indonesia</a> • 
<?php echo "<a lang=\"is\" xml:lang=\"is\" href=\"http://www.tranexp.com:2000/InterTran?url=$url&amp;type=text&amp;text=&amp;from=eng&amp;to=ice\" rel=\"nofollow\">"; ?>Íslenska</a> • 
<?php echo "<a lang=\"it\" xml:lang=\"it\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cit&amp;u=$url\" rel=\"nofollow\">"; ?>Italiano</a> • 
<?php echo "<a lang=\"he\" xml:lang=\"he\" title=\"‘Ivrit\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Che&amp;u=$url\" rel=\"nofollow\">"; ?>עברית</a> • 
<?php echo "<a lang=\"lv\" xml:lang=\"lv\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Clv&amp;u=$url\" rel=\"nofollow\">"; ?>Latviešu</a> • 
<?php echo "<a lang=\"lt\" xml:lang=\"lt\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Clt&amp;u=$url\" rel=\"nofollow\">"; ?>Lietuvių</a> • 
<?php echo "<a lang=\"ko\" xml:lang=\"ko\" title=\"Hangugeo\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cko&amp;u=$url\" rel=\"nofollow\">"; ?>한국어</a> • 
<?php echo "<a lang=\"hu\" xml:lang=\"hu\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Chu&amp;u=$url\" rel=\"nofollow\">"; ?>Magyar</a> • 
<?php echo "<a lang=\"mt\" xml:lang=\"mt\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cmt&amp;u=$url\" rel=\"nofollow\">"; ?>Malti</a> • 
<?php echo "<a lang=\"nl\" xml:lang=\"nl\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cnl&amp;u=$url\" rel=\"nofollow\">"; ?>Nederlands</a> • 
<?php echo "<a lang=\"ja\" xml:lang=\"ja\" title=\"Nihongo\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cja&amp;u=$url\" rel=\"nofollow\">"; ?>日本語</a> • 
<?php echo "<a lang=\"no\" xml:lang=\"no\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cno&amp;u=$url\" rel=\"nofollow\">"; ?>Norsk (Bokmål)</a> • 
<?php echo "<a lang=\"pl\" xml:lang=\"pl\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cpl&amp;u=$url\" rel=\"nofollow\">"; ?>Polski</a> • 
<?php echo "<a lang=\"pt\" xml:lang=\"pt\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cpt&amp;u=$url\" rel=\"nofollow\">"; ?>Português</a> • 
<?php echo "<a lang=\"ro\" xml:lang=\"ro\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cro&amp;u=$url\" rel=\"nofollow\">"; ?>Română</a> • 
<?php echo "<a lang=\"ru\" xml:lang=\"ru\" title=\"Russkij\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cru&amp;u=$url\" rel=\"nofollow\">"; ?>Русский</a> • 
<?php echo "<a lang=\"sk\" xml:lang=\"sk\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Csk&amp;u=$url\" rel=\"nofollow\">"; ?>Slovenčina</a> • 
<?php echo "<a lang=\"sl\" xml:lang=\"sl\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Csl&amp;u=$url\" rel=\"nofollow\">"; ?>Slovenščina</a> • 
<?php echo "<a lang=\"sq\" xml:lang=\"sq\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Csq&amp;u=$url\" rel=\"nofollow\">"; ?>Shqip</a> • 
<?php echo "<a lang=\"sr\" xml:lang=\"sr\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Csr&amp;u=$url\" rel=\"nofollow\">"; ?>Srpski</a> • 
<?php echo "<a lang=\"fi\" xml:lang=\"fi\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cfi&amp;u=$url\" rel=\"nofollow\">"; ?>Suomi</a> • 
<?php echo "<a lang=\"sv\" xml:lang=\"sv\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Csv&amp;u=$url\" rel=\"nofollow\">"; ?>Svenska</a> • 
<?php echo "<a lang=\"th\" xml:lang=\"th\" title=\"Thai\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cth&amp;u=$url\" rel=\"nofollow\">"; ?>ไทย</a> • 
<?php echo "<a lang=\"tl\" xml:lang=\"tl\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Ctl&amp;u=$url\" rel=\"nofollow\">"; ?>Tagalog</a> • 
<?php echo "<a lang=\"tr\" xml:lang=\"tr\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Ctr&amp;u=$url\" rel=\"nofollow\">"; ?>Türkçe</a> • 
<?php echo "<a lang=\"uk\" xml:lang=\"uk\" title=\"Ukraïns’ka\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cuk&amp;u=$url\" rel=\"nofollow\">"; ?>Українська</a> • 
<?php echo "<a lang=\"vi\" xml:lang=\"vi\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Cvi&amp;u=$url\" rel=\"nofollow\">"; ?>Tiếng Việt</a> • 
<?php echo "<a lang=\"zh-CN\" xml:lang=\"zh-CN\" title=\"Zhōngwén\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Czh-CN&amp;u=$url\" rel=\"nofollow\">"; ?>中文</a> /
<?php echo "<a lang=\"zh-TW\" xml:lang=\"zh-TW\" title=\"Zhōngwén\" href=\"http://www.google.com/translate?hl=en&amp;ie=UTF8&amp;langpair=en%7Czh-TW&amp;u=$url\" rel=\"nofollow\">"; ?>漢語</a>.
Get this <a lang="en" xml:lang="en" href="http://carlosquiles.com/indo-european-language-blog/wordpress-translation-plugin/" title="Wordpress Translation Plugin"><u>Wordpress Translation Plugin</u></a>.
</div>
<?php
}
?>