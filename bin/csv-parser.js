// Read file system and process CSV
const fs = require("fs");
const config = require("../package.json");
const csvParser = require("csv-parser");

let testScenarios = [];

// File system reading on user defined input csv from package.json
fs.createReadStream(config.exclaim.input)
  .pipe(csvParser())
  .on("data", (data) => testScenarios.push(data))
  .on("end", () => {
    // After generating the testScenarios we are creating a json file
    fs.writeFile(
      "./data/scenarios.json",
      JSON.stringify(testScenarios),
      "utf8",
      function (error) {
        console.log("Scenario JSON file generated successfully!");
      }
    );
  });
