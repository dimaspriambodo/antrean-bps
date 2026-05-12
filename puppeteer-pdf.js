const express = require("express");
const bodyParser = require("body-parser");
const { Cluster } = require("puppeteer-cluster");
const fs = require("fs");
const path = require("path");
const os = require("os");
const { execSync } = require("child_process");

/**
 * 1. PATH ABSOLUT (Disesuaikan berdasarkan Properties folder Anda)
 * Alamat ini memastikan Node.js menaruh file tepat di folder yang dibaca oleh PHP.
 */
const WRITABLE_TEMP_PATH = "C:\\Users\\mafif\\Downloads\\pectk-antrean-main\\pectk-antrean-main\\writable\\temp";

// 🔍 Fungsi pencari Google Chrome secara otomatis
function findChromePath() {
  const platform = os.platform();
  if (platform === "win32") {
    const candidates = [
      "C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe",
      "C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe",
      process.env.LOCALAPPDATA + "\\Google\\Chrome\\Application\\chrome.exe",
    ];
    for (const chromePath of candidates) {
      if (fs.existsSync(chromePath)) return chromePath;
    }
  }
  return null;
}

const chromePath = findChromePath();

if (!chromePath) {
  console.error("❌ ERROR: Google Chrome tidak ditemukan. Silakan install Chrome terlebih dahulu.");
  process.exit(1);
}

(async () => {
  const app = express();
  app.use(bodyParser.json({ limit: "20mb" })); // Mengantisipasi data HTML yang besar

  // Inisialisasi Cluster Puppeteer
  const cluster = await Cluster.launch({
    concurrency: Cluster.CONCURRENCY_CONTEXT,
    maxConcurrency: 2, // Batasan agar laptop tidak berat
    puppeteerOptions: {
      headless: "new",
      executablePath: chromePath,
      args: ["--no-sandbox", "--disable-setuid-sandbox", "--disable-dev-shm-usage"],
    },
  });

  // Logika pembuatan PDF
  await cluster.task(async ({ page, data }) => {
    const { html, outputFilename, paper = {} } = data;
    await page.setContent(html, { waitUntil: "networkidle0" });

    const pdfOptions = {
      path: outputFilename,
      printBackground: true,
      margin: { top: '0px', right: '0px', bottom: '0px', left: '0px' }
    };

    // Setting ukuran kertas thermal (Default: 45mm x 150mm)
    pdfOptions.width = paper.width || '45mm';
    pdfOptions.height = paper.height || '150mm';

    await page.pdf(pdfOptions);
  });

  // Endpoint API untuk menerima request dari CodeIgniter
  app.post("/generate-pdf", async (req, res) => {
    const { html, filename, paper } = req.body;
    
    // Menggabungkan path folder temp dengan nama file unik
    const fullOutputPath = path.join(WRITABLE_TEMP_PATH, filename);

    try {
      // Pastikan folder temp tersedia secara fisik
      if (!fs.existsSync(WRITABLE_TEMP_PATH)) {
        fs.mkdirSync(WRITABLE_TEMP_PATH, { recursive: true });
      }

      // Eksekusi pembuatan PDF melalui cluster
      await cluster.execute({ html, outputFilename: fullOutputPath, paper });

      console.log(`✅ File PDF Berhasil Dibuat: ${fullOutputPath}`);
      res.json({ 
        success: true, 
        file: filename 
      });

    } catch (err) {
      console.error("🛑 Gagal konversi PDF:", err.message);
      res.status(500).json({ success: false, error: err.message });
    }
  });

  const PORT = 3002;
  app.listen(PORT, () => {
    console.log(`🚀 PDF Worker AKTIF di port ${PORT}`);
    console.log(`📂 Jalur Penyimpanan: ${WRITABLE_TEMP_PATH}`);
  });
})();