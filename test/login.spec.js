import { expect, test } from "@playwright/test";

test("halaman login berfungsi", async ({ page }) => {
  await page.goto("http://localhost:8000/users/login");

  // Isi form login
  await page.fill("#email", "whyddoni@gmail.com");
  await page.fill("#password", "12121212");
  await page.click('button[type="submit"]');

  // Pastikan redirect atau teks tertentu muncul
  await page.goto("http://localhost:8000/dashboard");
  await page.waitForSelector("h1", { timeout: 10000 });
  await expect(page.locator("h1")).toContainText("Dashboard");
});
