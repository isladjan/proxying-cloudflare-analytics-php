# Cloudflare Web Analytics Proxy (PHP)
The problem is that, like most web analytics solutions, the CloudFlare beacon script (beacon.min.js) is often blocked by ad blockers. I'm addressing this by using a PHP backend proxy.

## Introduction

Ad blockers can be a real pain, intercepting the beacon script and preventing valuable data from reaching Cloudflare. To outsmart these blockers, this project reroutes both the script and its data through a PHP backend proxy. This ensures your analytics remain uninterrupted and accurate—even when pesky ad blockers are on duty.

## How It Works

When your page loads, the Cloudflare analytics script is executed along with your unique Cloudflare token, which identifies your site. Additional configuration options can be passed via data attributes in the script tag. Normally, the beacon script sends analytics data directly to Cloudflare. However, ad blockers often stop both the script and its data transmissions. That’s where the proxy comes in.

## The Problem

Most analytics solutions face the same issue: ad blockers intercept the analytics script and the data it sends, leaving you with incomplete metrics. Without a workaround, you might lose valuable insights into your site’s performance.

## The Solution

This project reroutes the Cloudflare beacon script and its analytics data through a PHP backend proxy, using two PHP files:

- **cf_script.php**: Loads and caches the Cloudflare analytics script.
- **cf_data.php**: Proxies the analytics data to Cloudflare's endpoint.

If you're running a different backend, no worries—feel free to ask for a code conversion. (Hint: ChatGPT is always here to help!)

Alternatively, you can implement a similar solution via a Cloudflare Worker, though keep in mind that some ad blockers might still interfere with that method.

## Usage

1. **Setup**: Upload `cf_script.php` and `cf_data.php` to your server.
2. **Configuration**: Adjust your HTML to load the proxy script instead of the direct Cloudflare script.
3. **Customization**: Add logic to let users block the script if they wish—because respecting user choice is key.

## Note

Please be aware that Cloudflare's official documentation does not explicitly state whether proxying their analytics is allowed or forbidden. This has been interpreted as permission to proxy the data, but that policy may change in the future. It’s a good idea to periodically review the latest Cloudflare documentation to ensure ongoing compliance. Additionally, if users prefer not to have their analytics data tracked, be sure to implement logic that respects their choice and disables the collection of their data.

please visit my blog post: [Cloudflare Analytics Proxy Tutorial](https://www.isladjan.com/blog/cloudflare-analytics-proxy/).

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
