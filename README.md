# Visual Automation Framework with BackstopJS
A script to conduct visual testing with [BackstopJS](https://github.com/garris/BackstopJS). This script can give you the confidence of delivering a bug free UI and can save you from monotonus work of regression testing. This Visual Automation framework is a quick and efficient tool to identify visual changes without vetting through the whole application manually. 


## Changelog ##
### v1.1 [June 7, 2023] ###

* NEW FEATURES

    * Support for Sitemap.xml

## Configuration

We have three options in this basic framework to run the tests. 

### CSV file for URLs (Recommended for big projects)
If you have a large number of URLs (>1000) that you would like to test then you should configure the following files
1. First, you should have one CSV file having a standard format. You can download the Sample CSV file from [here](https://drive.google.com/file/d/1Jw4EjXcY4yWTghEePDJ1cnT0d1rwGrbQ/view)
2. After downloading just replace the URLs in the CSV file and keep this in `./data/` directory or any path of your choice. Just make sure to add the path in package.json under `input`

### Keep the URLs in code only (Recommended for small projects)
If you have only a few URLs and don't want to maintain a seperate CSV file, you should configure mainConfig.js
1. Change the `baseUrl`, `referenceUrl` as per your requirement.  
2. Add the slugs in the `relativeUrls` array. 

### Sitemap XML Feature (Recommended for big projects)
If your site have large number of URL's that you would like to test without adding those URLs to CSV or manually adding them we recommend to use Sitemap XML Feature with which you the site URLs will be automatically fetched and you can perform testing on the fetched URLs. You should configure the following files for using Sitemap XML Feature:
1. Create a `.env` file and add the `baseUrl` and the `referenceUrl` to this file.
2. [Install](#install-the-dependencies) the dependencies and Execute the [run](#Run-the-tests-without-CSV) command
3. Create reference screenshot from reference site (For expected results) `npm run reference:xml`
4. Run the test(It will take the screenshot of the given site and will test against reference screenshot) `npm run test:xml`


## Install the dependencies
`npm install`


## Run the tests without CSV
1. Create reference screenshot from the source site (For expected results)
`backstop reference` or `npm run reference` 

2. Run the test(It will take the screenshot of the given site and will test it against the reference screenshot)
`backstop test` or `npm run test`

## Run the tests with CSV
1. Create reference screenshot from source site (For expected results)
 `npm run reference:csv` 

2. Run the test(It will take the screenshot of the given site and will test against reference screenshot)
 `npm run test:csv`

## BTW, We're Hiring!

<a href="https://rtcamp.com/"><img src="https://rtcamp.com/wp-content/uploads/sites/2/2019/04/github-banner@2x.png" alt="Join us at rtCamp, we specialize in providing high performance enterprise WordPress solutions"></a>
