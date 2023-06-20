const Sitemapper = require("sitemapper");
const fs = require('fs');
const dotenv = require('dotenv');
dotenv.config();

const sitemap = new Sitemapper();

var siteURLS = [];


sitemap.fetch(process.env.referenceUrl+ "/sitemap_index.xml").then(function (sites) {
  const urlValue = Object.values(sites);
  
  for (var i = 0; i < urlValue.length; i++) {
    if (Array.isArray(urlValue[i])) {
      var innerArray = urlValue[i];
      for (var j = 0; j < innerArray.length; j++) {
        if (typeof innerArray[j] === "string") {
          var urlParts = innerArray[j].split("/"); // Split the URL by '/'
          var slugParts = urlParts.slice(3); // Exclude the domain and first two path segments
          var fullSlug = slugParts.join("/"); // Join the slug parts back together with '/'
          siteURLS.push(fullSlug);

        }
      }
    }
  }
  const numberOfURL = siteURLS.length; // Add the number of URLs here. 
  const userdefineURLSize = siteURLS.slice(0,numberOfURL);

  const csvContent = userdefineURLSize.join(',');

fs.writeFile('data/output.csv', csvContent, function(err) {
  if (err) {
    console.error('Error writing CSV file:', err);
  } else {
    console.log('CSV file generated successfully!');
  }
});


})


