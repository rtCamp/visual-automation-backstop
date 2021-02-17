const basicConfig = require("./basicConfig");
const THREE_SECONDS_IN_MS = 3000;
const scenarios = [];
const viewports = [];

basicConfig.config.map(config => {
  scenarios.push({
    label: config.relURL,
    url: `${basicConfig.baseUrl}${config.relURL}`,
    referenceUrl: `${basicConfig.referenceUrl}${config.relURL}`, //If you haven't set refrenceUrl in basicConfig.js, you don't need to add here too
    delay: THREE_SECONDS_IN_MS,
    requireSameDimensions: false,
    scrollToSelector: config.scrlSelector, //Use only If scrlSelector is set
    removeSelectors: [config.rmvSelector], //Use only If rmvSelector is set
    onReadyScript:  "onReadyScript.js"
    // readyEvent: "page_loaded"
  });
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
  scenarios,
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