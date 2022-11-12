<?php
$Generator_Name = 'Wolfcreator';
$Sprache = 'de';
$ordner = "../recordings/";

$Name_vom_Feed = 'Music';
$Beschreibung_des_Feeds = 'My Music Feed';

/*
Die aktuellste Datei wird nie angezeigt, weil ich davon ausgehe, dass dort gerade noch ein aktives Streamripping läuft.
*/

#################### kein Editieren unterhalb dieser Zeile notwendig ####################

//https://stackoverflow.com/questions/1283327/how-to-get-url-of-current-page-in-php#comment49052642_25651479
function isSSL() 
{
	return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443; 
}

$protokoll = "http://";

if(isSSL() == true)
{
	$protokoll = "https://";
} 

$Link_zur_Webseite = $protokoll.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")+1);
$Link_zum_Track = substr($Link_zur_Webseite,0,strrpos($Link_zur_Webseite,'/'));
$Link_zum_Track = substr($Link_zum_Track,0,strrpos($Link_zum_Track,'/')+1);

$date  = date(DATE_ATOM);

$dateien = array_slice(scandir($ordner),2);
unset($dateien[count($dateien)-1]);
$dateien = array_reverse($dateien);
$dateien = array_values($dateien);



echo '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">
<channel>
<title>
<![CDATA[ '.$Name_vom_Feed.' ]]>
</title>
<link>'.$Link_zur_Webseite.'</link>
<description>
<![CDATA[ '.$Beschreibung_des_Feeds.' ]]>
</description>
<pubDate>'.$date.'</pubDate>
<generator>'.$Generator_Name.'</generator>
<language>'.$Sprache.'</language>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>';
foreach($dateien as $push)
{
	$endung = substr($push,strrpos($push,"."));
	if($endung == ".mp3")
	{
	$fp = fopen($ordner.$push, "r");
	$fstat = fstat($fp);
	fclose($fp);
	//print_r(array_slice($fstat, 13));
	$filetime = $fstat['mtime'];
	$filetime = date(DATE_ATOM,$filetime);
	$tracklink = $Link_zum_Track.substr($ordner,3).$push;
		
echo '
<item>
<title>
<![CDATA[ '.$push.' ]]>
</title>
<link>'.$tracklink.'</link>
<enclosure url="'.$tracklink.'" length="0" type="audio/mpeg"/>
<guid>'.base64_encode($filetime).'</guid>
<description>
<![CDATA['.$push.']]>
</description>
<category>autoplay</category>
<pubDate>'.$filetime.'</pubDate>
</item>';
		
	}
}
echo '
</channel>
</rss>
';
?>


