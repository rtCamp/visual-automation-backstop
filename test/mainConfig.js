const basicConfig = require("./basicConfig");
const THREE_SECONDS_IN_MS = 3000;
const scenarios = [];
const viewports = [];

basicConfig.relativeUrls.map(relativeUrl => {
  scenarios.push({
    label: relativeUrl,
    url: `${basicConfig.baseUrl}${relativeUrl}`,
    referenceUrl: `${basicConfig.referenceUrl}${relativeUrl}`,
    delay: THREE_SECONDS_IN_MS,
    requireSameDimensions: false
    // readyEvent: "page_loaded"
  });
  // console.log("reference url "+`${basicConfig.referenceUrl}${relativeUrl}`)
});

basicConfig.viewports.map(viewport => {
 
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
    bitmaps_reference: "test/backstop_data/bitmaps_reference",
    bitmaps_test: "test/backstop_data/bitmaps_test",
    html_report: "test/backstop_data/html_report"
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