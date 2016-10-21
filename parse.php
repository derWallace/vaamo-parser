<?php
/**
 * @author Gerd Rönsch <gerd-roensch@gmx.de>
 */

require(__DIR__.'/vendor/autoload.php');
require(__DIR__.'/config.php');

use Symfony\Component\DomCrawler\Crawler;

define('COOKIE_FILE', tempnam('/tmp/', 'parse-vaamo-jar'));
$fmt = numfmt_create(SPREADSHEET_LANG, NumberFormatter::DECIMAL);


// Get CSRF Token
$ch = curl_init('https://mein.vaamo.de/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE);
curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE);
$data = curl_exec ($ch);
$crawler = new Crawler($data);

$csrf_token = $crawler->filter('body form input[name=csrfToken]')->attr('value');

// Login the user
$ch = curl_init('https://mein.vaamo.de/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE);
curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
	'email' => VAAMO_USER,
	'password' => VAAMO_PASSWORD,
	'csrfToken' => $csrf_token
));
curl_exec($ch);


// Get contents from saving goals overview
$ch = curl_init('https://mein.vaamo.de/savings-goal');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE);
curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE);
$data = curl_exec($ch);
$crawler = new Crawler($data);



// Iterate over all saving goals
$saving_goals_values = array();
$saving_goals = $crawler->filter('div.savingsgoal-row');
foreach ($saving_goals as $saving_goal) {
    $saving_goal_crawler = new Crawler($saving_goal);

    // Get name by searching for Performance Details für Dein Sparziel "NAME DES SPARZIELS“
    $saving_goal_name = "";
    $modals = $saving_goal_crawler->filter('.modal-title');
    foreach ($modals as $modal) {
        $matches = array();
        if (1 === preg_match('/„(.*)“/', $modal->textContent, $matches)) {
            $saving_goal_name = trim($matches[1]);
        }
    }

    // Get raw data of performance
    $performance_raw = $saving_goal_crawler->filter('div.sg-card div.sg-performance span.float-right')->text();

    // Get performance in percent
    $performance_percent = 0.0;
    $matches = null;
    preg_match('/([\+|-][0-9]+,[0-9]{2}) \%/', $performance_raw, $matches);
    if (isset($matches[1])) {
        $performance_percent = (float) str_replace(",", ".", $matches[1]);
    }


    // Get performance in eur
    $performance_eur = 0.0;
    $matches = null;
    preg_match('/([\+|-][0-9]+,[0-9]{2}) €/', $performance_raw, $matches);
    if (isset($matches[1])) {
        $performance_eur = (float) str_replace(",", ".", $matches[1]);
    }

    printf("%s: %.2f %%, %.2f €".PHP_EOL, $saving_goal_name, $performance_percent, $performance_eur);

    $saving_goals_values[] = array(
        'name' => $saving_goal_name,
        'percent' => $performance_percent,
        'eur' => $performance_eur
    );
}
