<?php
$path = "./";
$file = "lang_en.properties";
$langFile = fopen($path.$file, "r") or die("Unable to open file!");
$langData = parseProperties(fread($langFile, filesize($path.$file)));
fclose($langFile);

//ksort($langData); //sort by key
natcasesort($langData); //sort by value

$content = implode("", array_map(
    function ($v, $k) {
            if ($position = strpos($v,"\n #")) {
                $k = trim(substr($v, $position))."\n".$k;
                $v = substr($v,0, $position)."\n";
            }
            return $k.'='.$v;
        },
    $langData,
    array_keys($langData)
));

echo "<pre>";
echo $content;
echo "</pre>";

$newFile = fopen("sorted_".$file,"wb") or die("Unable to create file!");
fwrite($newFile,$content);
fclose($newFile);

echo "Sorted File Created, Please check directory";

function parseProperties($fileContent): array {
    $result = [];
    $lines = explode("\n",  $fileContent);
    $comments = '';
    foreach ($lines as $l) {
        $cleanLine = trim($l);
        if ($cleanLine === '') continue;

        //handle comments
        if (str_starts_with($cleanLine, '#')) {
            $comments .= $cleanLine."\n";
            continue;
        }

        $key = trim(substr($l, 0, strpos($l, '=')));
        $value = substr($l,strpos($l,'=') + 1);
        $result[$key] = $value."\n".(strlen($comments) ? ' '.$comments : '');
        $comments = "";
    }
    return $result;
}

?>