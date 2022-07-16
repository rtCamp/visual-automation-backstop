const basicConfig = require("./basicConfig");

let project_id = '';

process.argv.forEach( function( value ) {
  if ( -1 !== value.indexOf('--project_id=' ) ) {
    project_id = value.replace('--project_id=', '' );
  }
} );

// Get user defined scenario json
const scenarioJSON = require('../data/scenarios-' + project_id + '.json');
const THREE_SECONDS_IN_MS = 3000;
const viewports = [];

let testScenarios = [];

scenarioJSON.map((s) => {
  const obj = {
    cookiePath: "backstop_data/"+project_id+"/engine_scripts/cookies.json",
    readyEvent: "",
    readySelector: "",
    delay: THREE_SECONDS_IN_MS,
    requireSameDimensions: false,
    hideSelectors: [],
    removeSelectors: [],
    hoverSelector: "",
    clickSelector: "",
    postInteractionWait: 0,
    selectors: [],
    selectorExpansion: true,
    expect: 0,
    misMatchThreshold: 0.1,
  };
  testScenarios.push({ ...obj, ...s }); // merging url,obj
});

basicConfig.viewports.map(viewport => {
  if (viewport === "phone") {
    pushViewport(viewport, 320, 480);
  }
  if (viewport === "tablet") {
    pushViewport(viewport, 1024, 768);
  }
  if (viewport === "desktop") {
    pushViewport(viewport, 1280, 1024);
  }
});

function pushViewport(viewport, width, height) {
    viewports.push({
      name: viewport,
      width,
      height,
    });
  }

module.exports = {
  id: project_id,
  viewports,
  scenarios: testScenarios,
  paths: {
    bitmaps_reference: "backstop_data/"+project_id+"/bitmaps_reference",
    bitmaps_test: "backstop_data/"+project_id+"/bitmaps_test",
    html_report: "backstop_data/"+project_id+"/html_report",
    engine_scripts: "test/engine_scripts"

  },
  report: ["CI"],
  engine: "puppeteer",
  engineOptions: {
    args: ["--no-sandbox"]
  },
  asyncCaptureLimit: 5,
  asyncCompareLimit: 50,
  puppeteerOffscreenCaptureFix: true
};