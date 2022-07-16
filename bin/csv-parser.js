// Read file system and process CSV
const fs = require("fs");
const config = require("../package.json");
const csvParser = require("csv-parser");
const basicConfig = require("../test/basicConfig");
let project_id = '';

process.argv.forEach( function( value ) {
  if ( -1 !== value.indexOf('--project_id=' ) ) {
    project_id = value.replace('--project_id=', '' );
  }
} );

let testScenarios = [];

// File system reading on user defined input csv from package.json
fs.createReadStream( './data/' + project_id + '.csv' )
  .pipe(csvParser())
  .on("data", (data) => testScenarios.push(data))
  .on("end", () => {
    // After generating the testScenarios we are creating a json file
    fs.writeFile(
      './data/scenarios-' + project_id + '.json',
      JSON.stringify(testScenarios),
      "utf8",
      function (error) {
        console.log("Scenario JSON file generated successfully!");
      }
    );
  });
