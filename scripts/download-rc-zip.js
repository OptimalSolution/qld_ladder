require('dotenv').config({ path: '../.env' });
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

(async () => {
  console.log('Launching browser...');
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({
    acceptDownloads: true
  });

  const page = await context.newPage();
  console.log('Navigating to login page...');
  await page.goto('https://www.ratingscentral.com/Support.php');

  const loginID = process.env.RC_LOGIN_ID;
  const loginPassword = process.env.RC_LOGIN_PASSWORD;
  const downloadPathEnv = process.env.RC_DOWNLOAD_PATH;

  console.log('Filling in login credentials...');
  await page.fill('input[name="LoginID"]', loginID);
  await page.fill('input[name="LoginPassword"]', loginPassword);
  console.log('Submitting login form...');
  await page.click('button[type="submit"]');

  console.log('Waiting for post-login page to load...');
  await page.waitForLoadState('networkidle');

  console.log('Clicking the Version 6 download link...');
  const [download] = await Promise.all([
    page.waitForEvent('download'),
    page.click('body > div.MainContainer > main > ul:nth-child(2) > li:nth-child(1) > ul > li:nth-child(1) > a')
  ]);

  const resolvedPath = path.resolve(__dirname, '../', downloadPathEnv || 'storage/app/rc/version6.zip');
  console.log('Saving download to:', resolvedPath);
  await download.saveAs(resolvedPath);

  console.log('Download complete:', resolvedPath);

  await browser.close();
  console.log('Browser closed.');
})();
