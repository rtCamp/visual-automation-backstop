const baseUrl = "https://example.com" // Replace the value "https://example.com" by the base URL of the website you want to test.
const referenceUrl = "https://reference.com" //Optional URL, Replace the value "https://reference.com" by the reference URL of the website you want to compare with.

const projectId = "sample project"; // Replace the value "sample project" by the id of your project. It can be any string (e.g., "my-website").

let config = []; 

// Replace the values of the below array with the relative URLs of your website. E.g., "/about", "/contact", "/pricing", etc.
// Use just "/" to test the homepage of your website.
// Add as many relative URLs as you need.
const relativeUrls = [
  "/slug1",
  "/slug2"
];

//If you need to add any selector specific to some URLs, you may add here 
relativeUrls.map(relativeUrl => {
  if (relativeUrl === "/slug1" || relativeUrl === "/slug1/?amp") {
    scrollToSelector = "a.wp-block-button__link"; 
    config.push({
      relURL: relativeUrl,
      scrlSelector: scrollToSelector, //To scroll to some specific selector
      rmvSelector: ".is-style-image-banner" //To remove any non stable selector from page
     })
  }
  else if (relativeUrl === "/slug2") {
    config.push({
      relURL: relativeUrl,
      rmvSelector: ".div.ytp-impression-link-content" //To remove any non stable selector from page 
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

// Leave the below array as is if you want to test your website using the viewports listed below.
// The suported viewports are: phone (320px X 480px), tablet (1024px X 768px), and desktop (1280px X 1024px).
// No other viewports are supported.
// You can remove the viewports that you don't need, but at least one of them is required.
const viewports = [
  "phone",
  "tablet",
  "desktop",
];

module.exports = {
  baseUrl,
  projectId,
  viewports,
  referenceUrl, //Optional
  config 
};