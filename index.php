<?php

$id = $_GET['id'];
$mem = new Memcache;
$mem->connect('localhost', 11211);

$feed = "http://feeds.feedburner.com/uclick/$id";
$rss_xml = file_get_contents($feed);
foreach($http_response_header as $header) {
  if (strpos($header, "Expires: ") === 0) $exp = ltrim($header, "Expires: ");
}
  
if (!empty($exp)) {
  header("Expires: $exp");
  header("X-Time-Expire: " . time() - strtotime($exp));
}

$rss = new simplexmlelement($rss_xml);
//$rss_heads = $http_response_header;
$title = preg_replace('@GoComics.com - (.+) by .+@', '\1', $rss->channel->title);

foreach($rss->channel->item as $i) {
  $link = strpos($i->link, '//') === 0 ? "http:" . $i->link : $i->link;
  $html = $mem->get($link);
  if (empty($html)) {
    $html = file_get_contents($link);
    $mem->set($link, $html);
  }

  $i->title = "$title: " . $i->title;
  if (preg_match('@<img[^>]+class="strip"[^>]+src="([^"]+)"[^>]+>@',$html,$m)) {
    $i->description = $m[0];
  }
}

header("Content-type: application/rss+xml");
echo $rss->saveXML();
