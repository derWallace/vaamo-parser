var GoogleSpreadsheet = require('google-spreadsheet');
var upload;
var async = require('async');

// Check if args are correct
console.log(process.argv);
if (process.argv.length !== 3) {
    console.log('Usage: upload-to-google-spreadsheets.js <filename>');
    process.exit(-1);
} else {
    upload = require(process.argv[2]);
}

console.log(upload);

// spreadsheet key is the long id in the sheets URL
var doc = new GoogleSpreadsheet(upload.document);
var sheet;

async.series([
  function setAuth(step) {
    // see notes below for authentication instructions!
    var creds = require('./parse-vaamo-a988cb5f1c01.json');

    doc.useServiceAccountAuth(creds, step);
  },
  function getInfoAndWorksheets(step) {
    doc.getInfo(function(err, info) {
        // Get sheets
        sheets = info.worksheets;

        // Search for the sheets
        var sheet = null;
        for (var i = 0; i < sheets.length; i++) {
            if (upload.sheet === sheets[i].title) {
                sheet = sheets[i];
            }
        }

        // Add rows from upload file
        for (var e = 0; e < upload.rows.length; e++) {
            var row = upload.rows[e];

            sheet.addRow(row, function(err) {
            });
        }
        step();
    });
  }
]);
