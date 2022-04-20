
const projectId = "sample-project"; // Replace the value "sample-project" by the id of your project. It can be any string (e.g., "my-website").

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
  projectId,
  viewports
};
