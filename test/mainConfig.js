const basicConfig = require("./basicConfig");
const THREE_SECONDS_IN_MS = 3000;
const dotenv = require('dotenv');
dotenv.config();
const scenarios = [];
const viewports = [];
const baseUrl =  "https://example.com" // Replace the value "https://example.com" by the base URL of the website you want to test. It could be your development site and you want to make sure recent changes didn't break the UI
const referenceUrl = "https://example.com" //Optional URL, Replace the value "https://reference.com" by the reference URL of the website you want to compare with. It could be your production site.

let config = []; 

// Replace the values of the below array with the relative URLs of your website. E.g., "/about", "/contact", "/pricing", etc.
// Use just "/" to test the homepage of your website.
// Add as many relative URLs as you need.


//If you need to add any selector specific to some URLs, you may add here 
//Kept the "slug2" so that it doesn't look for placeholder selctor
relativeUrls.map(relativeUrl => {
  if (relativeUrl === "/slug2" || relativeUrl === "/slug2/?amp") {
    scrollToSelector = ".someselector"; 
    rmvSelector = ".someselector1"
    config.push({
      relURL: relativeUrl,
      scrlSelector: scrollToSelector, //To scroll to some specific selector
      rmvSelector: rmvSelector //To remove any non stable selector from page
     })
  }
  else if (relativeUrl === "/slug3") {
    rmvSelector = ".someselector3"
    config.push({
      relURL: relativeUrl,
      rmvSelector: rmvSelector //To remove any non stable selector from page 
     })
  }
  else {
    //If you don't need any specific selector to be removed or any other condition
    // then just add below block and remove other code
    config.push({
      relURL: relativeUrl
     })
  }
  
});

config.map(config => {
  
  scenarios.push({
    label: config.relURL,
    url: `${baseUrl}${config.relURL}`,
    referenceUrl: `${referenceUrl}${config.relURL}`, //If you haven't set refrenceUrl in basicConfig.js, you don't need to add here too  
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