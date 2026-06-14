const { chromium } = require('playwright');

(async () => {

    const url = 'https://yandex.ru/maps/172/ufa/?ll=55.939096%2C54.724727&mode=poi&poi%5Bpoint%5D=55.939716%2C54.727237&poi%5Buri%5D=ymapsbm1%3A%2F%2Forg%3Foid%3D57899447703&z=16';
    
    const browser = await chromium.launch({
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-gpu'
        ]
    });

    const context = await browser.newContext({
        locale: 'ru-RU',
        userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
        viewport: { width: 1600, height: 900 }
    });

    const page = await context.newPage();

    const reviews = [];

    let firstBatchReceived = false;
    let stop = false;

    console.error('GOING'); //.log ломает stdout. Если что-то логгировать, то через .error

    // слушаем каждый запрос новых отзывов
    page.on('response', async (response) => {

        if (!response.url().includes('/fetchReviews?')) return;

        try {
        const json = await response.json();
        const batch = json?.data?.reviews || [];

        for (const r of batch) {
            reviews.push({
                author: r?.author?.name || null,
                date: r?.updatedTime || null,
                text: r?.text || null,
                rating: r?.rating || null
            });
        }

        } catch (e) {
            // игнорируем если что-то не так
        }
    });

    await page.goto(url, {
        waitUntil: 'domcontentloaded',
        timeout: 60000
    });

    // поздно рендерится, хотябы какой-то маркер готовности страницы
    await page.waitForSelector('.carousel__scrollable._smooth-scroll');

    const meta = await extractPlaceMeta(page);

    console.error(meta) 

    // кликаем на вкладку отзывов (ибо при переходе сразу на reviews они подгружаются сразу со страницей, без доп запроса)
    const reviewsTab = page.locator('[role="tab"]', { hasText: 'Отзывы' });

    await reviewsTab.waitFor({ timeout: 15000 });
    await reviewsTab.click();

    console.error('CLICKED REVIEWS');

    // ждём прогрузки вкладки отзывов
    await page.waitForSelector('[data-chunk="reviews"]');

    console.error('START SCROLL');

    let lastCount = 0;
    let stableTicks = 0;

    while (reviews.length < meta.reviews_count) {
        console.error(reviews.length)

        const scrollContainer = page.locator('.scroll__container');

        await scrollContainer.waitFor({ state: 'attached' });

        // await scrollContainer.evaluate(el => {
        //     el.scrollBy({
        //         top: 600,
        //         behavior: 'smooth'
        //     });
        // });

        await scrollContainer.evaluate(el => el.scrollTop = el.scrollHeight);

        await page.waitForTimeout(1000);

        if (reviews.length === lastCount) {
            stableTicks++;
        } else {
            stableTicks = 0;
            lastCount = reviews.length;
        }

        if (stableTicks >= 5) {
            console.error('No new reviews');
            break;
        }
    }

    console.error('DONE:', reviews.length);

    // console.error(JSON.stringify(reviews));

    await browser.close();

    await new Promise(r => setTimeout(r, 500));

    process.stdout.write(JSON.stringify({
        ...meta,
        reviews
    }));
    process.exit(0);
})();

async function extractPlaceMeta(page) {
    const title = await page
        .locator('.card-title-view__title-link')
        .textContent()
        .catch(() => null);

    const ratingText = await page
        .locator('.business-rating-badge-view__rating-text')
        .first()
        .textContent()
        .catch(() => null);

    const reviewsCountText = await page
        .locator('._name_reviews .tabs-select-view__counter')
        .first()
        .textContent()
        .catch(() => null);

    const ratingsCountText = await page
        .locator('.business-header-rating-view__text')
        .first()
        .textContent()
        .catch(() => null);

    const parseNumber = (str) => {
        if (!str) return null;
        return parseInt(str.replace(/\D/g, ''), 10) || null;
    };

    const parseRating = (str) => {
        if (!str) return null;
        return parseFloat(str.replace(',', '.')) || null;
    };

    return {
        title,
        rating: parseRating(ratingText),
        reviews_count: parseNumber(reviewsCountText),
        ratings_count: parseNumber(ratingsCountText),
    };
}