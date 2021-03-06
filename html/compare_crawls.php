<?php

require_once __DIR__.'/../vendor/autoload.php';


$resultsStorage = new \ScraperBot\Storage\SqlLite3Storage('../glitcherbot.sqlite3');
$crawls = $resultsStorage->getTimeStamps();

$rows = [];
$headers = [0,1];
$index = 0;
$tolerance = 1000;

if (isset($_GET['tolerance'])) {
    $tolerance = $_GET['tolerance'];
}

$lastElem = array_key_last($crawls);
$rows = $resultsStorage->getCrawlDiffs($crawls[$lastElem-1], $crawls[$lastElem], $tolerance);

// Specify our Twig templates location
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../src/templates');
// Instantiate our Twig
$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$template = $twig->load('crawls_diffs.twig');
echo $template->render(['headers' => $headers, 'rows' => $rows, 'tolerance' => $tolerance]);
