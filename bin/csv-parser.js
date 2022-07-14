// Read file system and process CSV
const fs = require("fs");
const config = require("../package.json");
const csvParser = require("csv-parser");
const basicConfig = require("../test/basicConfig");

let testScenarios = [];

// File system reading on user defined input csv from package.json
fs.createReadStream( './data/' + basicConfig.projectId + '.csv' )
  .pipe(csvParser())
  .on("data", (data) => testScenarios.push(data))
  .on("end", () => {
    // After generating the testScenarios we are creating a json file
    fs.writeFile(
      './data/scenarios-' + basicConfig.projectId + '.json',
      JSON.stringify(testScenarios),
      "utf8",
      function (error) {
        console.log("Scenario JSON file generated successfully!");
      }
    );
  });
