# Visual Automation with BackstopJS (test)
Script to do visual testing with [BackstopJS](https://github.com/garris/BackstopJS)

## Configuration

We have two options in this basic framework to run the tests. 

### CSV file for URLs (Recommended for big projects)
If you have thousands of URLs that you would like to test then you should configure the following files
1. First you should have one CSV file having a standard format, you can download the Sample CSV file from [here](https://drive.google.com/file/d/1Jw4EjXcY4yWTghEePDJ1cnT0d1rwGrbQ/view)
2. After downloading just replace the URLs in CSV file and keep this in `./data/` directory or whatever path you prefer. Just make sure to add the path in package.json under `input`

### Keep the URLs in code only (Recommended for small projects)
If you have only few URLs and don't want to maintain a seperate CSV file. You should configure mainConfig.js
1. Change the `baseUrl`, `referenceUrl` as per your requirement.  
2. Add the slugs in `relativeUrls` array. 

## Install following system dependencies for Ubuntu(Linux)
```
apt update && apt-get install -y ca-certificates fonts-liberation libappindicator3-1 libasound2 libatk-bridge2.0-0 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libglib2.0-0 libgtk-3-0 libnspr4 libnss3 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 lsb-release wget xdg-utils
```

## Provide node path
- In `index.php` line no. 15
  `define( 'VISUAL_NODE_DIRECTORY', '<your/system/node/directory>' ); // Directory which contains Node & npm.`

## Install the depedencies
`npm install`


## Run the tests without CSV
1. Create reference screenshot from source site (For expected results)
`backstop reference` or `npm run reference` 

2. Run the test(It will take the screenshot of the given site and will test against reference screenshot)
`backstop test` or `npm run test`

## Run the tests with CSV
1. Create reference screenshot from source site (For expected results)
 `npm run reference:csv` 

2. Run the test(It will take the screenshot of the given site and will test against reference screenshot)
 `npm run test:csv`


