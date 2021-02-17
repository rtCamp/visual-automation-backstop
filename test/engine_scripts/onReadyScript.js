// onReady example (puppeteer engine)
const basicConfig = require("./../../test/basicConfig");
// tests/visual-tests/test/basicConfig.js
module.exports = async (page, scenario) => {
  console.log('SCENARIO onReadyScript > ' + scenario.label);
  
  //Should be used if there is lazy loading on site, it may slow down the tests
  await page.evaluate(async () => {
    await new Promise((resolve, reject) => {
      var totalHeight = 0;
      var distance = 10;
      var timer = setInterval(() => {
        var scrollHeight = document.body.scrollHeight;
        window.scrollBy(0, distance);
        totalHeight += distance;

        if(totalHeight >= scrollHeight){
          clearInterval(timer);
          resolve();
        }
      }, 60);
    });
  });
  await page.waitFor(50);



}
