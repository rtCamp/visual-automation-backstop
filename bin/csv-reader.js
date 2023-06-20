const fs = require('fs');

// Read the CSV file
const csvFile = fs.readFileSync('data/output.csv', 'utf-8');

// Split the CSV content by newlines and commas to create a 2D array
const csvArray = csvFile.split('\n').map(row => row.split(','));

const csvArray1D = csvArray.flat();

// // Export the csvArray so that it can be used in other files
module.exports = csvArray1D;
