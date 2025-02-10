function cloudflareScript() {
  //if (process.env.NODE_ENV === "development") return; // Skip in development mode

  const urlLoader = `${baseUrl}/cf_loader.php`;
  const urlData = `${baseUrl}/cf_data.php`;

  // Cloudflare token
  const cfToken = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

  // Create global config object before loading the script
  window.__cfBeacon = {
    token: cfToken,
    spa: false,
    send: {
      to: urlData, // Override the default CF endpoint with our proxy (cf_smuggler.php)
    },
  };

  // script is being created to redirect to cf_loader.php and download CF beacon.js.
  const cfScript = document.createElement("script");
  cfScript.defer = true;
  cfScript.src = urlLoader;
  // Set the data attribute with the same config to ensure it's picked up
  cfScript.setAttribute("data-cf-beacon", JSON.stringify(window.__cfBeacon));
  document.head.appendChild(cfScript);
}
requestIdleCallback(() => cloudflareScript());
