# Visual Automation with BackstopJS (test)
Script to do visual testing with [BackstopJS](https://github.com/garris/BackstopJS)

## Run the tests
1. Install depedencies 
`npm install`

2. Create reference screenshot from source site (For expected results)
`backstop reference` or `npm run reference` 

3. Run the test(It will take the screenshot of the given site and will test against reference screenshot)
`backstop test` or `npm run test`

NOTE: If you are using the basic framework then `npm run referece & npm run test` commands will work for creating reference screenshots & running the test respectively. 

If you are using the default BacksstopJS installation with backstop.json file then `backstop reference & backstop test` commands will work. 
