const basicConfig = require("./basicConfig");
// Get user defined scenario json
const scenarioJSON = require("../data/scenarios.json");
const THREE_SECONDS_IN_MS = 3000;
const viewports = [];



let testScenarios = [];

scenarioJSON.map((s) => {
  const obj = {
    cookiePath: "backstop_data/engine_scripts/cookies.json",
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
  id: basicConfig.projectId,
  viewports,
  scenarios: testScenarios,
  paths: {
    bitmaps_reference: "backstop_data/bitmaps_reference",
    bitmaps_test: "backstop_data/bitmaps_test",
    html_report: "backstop_data/html_report",
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