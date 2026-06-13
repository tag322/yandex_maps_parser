const { chromium } = require('playwright');

(async () => {
    console.log('START');

    const browser = await chromium.launch({ headless: true });

    const page = await browser.newPage();

    console.log('GOING');

    await page.goto('https://yandex.ru/maps/org/116399574619/reviews/', {
        waitUntil: 'domcontentloaded'
    });

    console.log('OK');

    await browser.close();
})();