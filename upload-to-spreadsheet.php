<?php
include(__DIR__.'/parse.php');


foreach ($saving_goals_values as $saving_goal) {
    $json_object = new stdClass();
    $json_object->document = GOOGLE_SPREADSHEET;
    $json_object->sheet = $mapping[$saving_goal['name']];
    $json_object->rows = array(array(
        'Datum' => date('d.m.Y'),
        'Prozent' => numfmt_format($fmt, $saving_goal['percent']). "%",
        'Wert' => 0,
        'Gewinn' => numfmt_format($fmt, $saving_goal['eur']). " €"
    ));

    $upload_filename = '/tmp/parse-vaamo-'.md5(rand()).'.json';
    file_put_contents($upload_filename, json_encode($json_object, JSON_PRETTY_PRINT));

    exec("node upload-to-spreadsheet.js $upload_filename");
    unlink ($upload_filename);
}
