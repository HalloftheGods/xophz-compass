<?php
  /**
   * The HTML entry point for compass
  *
  * @link       https://mycompassconsulting.com
  * @since      0.0.0
  *
  * @package    Xophz_Compass
  * @subpackage Xophz_Compass/admin/partials
  */
?>
<style>
  #app:empty,
  .compass-preloader {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    background: #070a12;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    font-family: 'Rajdhani', 'Orbitron', system-ui, -apple-system, sans-serif;
    color: #e2e8f0;
    margin: 0;
    padding: 0;
    overflow: hidden;
  }
  .compass-preloader__rings {
    position: relative;
    width: 110px;
    height: 110px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
  }
  .compass-preloader__ring {
    position: absolute;
    border-radius: 50%;
    border: 2px solid transparent;
  }
  .compass-preloader__ring--outer {
    inset: 0;
    border-top-color: #62c9ff;
    border-right-color: rgba(98, 201, 255, 0.2);
    animation: compass-spin 2.2s linear infinite;
    box-shadow: 0 0 20px rgba(98, 201, 255, 0.2);
  }
  .compass-preloader__ring--mid {
    inset: 12px;
    border-bottom-color: #38bdf8;
    border-left-color: rgba(56, 189, 248, 0.2);
    animation: compass-spin-rev 1.6s linear infinite;
  }
  .compass-preloader__ring--inner {
    inset: 24px;
    border-top-color: #93c5fd;
    border-left-color: rgba(147, 197, 253, 0.3);
    animation: compass-spin 1.1s linear infinite;
  }
  .compass-preloader__core {
    position: relative;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: compass-pulse 2s ease-in-out infinite;
  }
  .compass-preloader__core svg {
    width: 28px;
    height: 28px;
    fill: #62c9ff;
    filter: drop-shadow(0 0 8px #62c9ff);
  }
  .compass-preloader__title {
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.35em;
    color: #62c9ff;
    text-transform: uppercase;
    margin: 0 0 6px 0;
    text-shadow: 0 0 12px rgba(98, 201, 255, 0.5);
  }
  .compass-preloader__status {
    font-size: 11px;
    letter-spacing: 0.18em;
    color: rgba(226, 232, 240, 0.5);
    text-transform: uppercase;
    margin: 0;
    animation: compass-pulse 1.8s ease-in-out infinite;
  }
  @keyframes compass-spin {
    to { transform: rotate(360deg); }
  }
  @keyframes compass-spin-rev {
    to { transform: rotate(-360deg); }
  }
  @keyframes compass-pulse {
    0%, 100% { opacity: 0.6; transform: scale(0.96); }
    50% { opacity: 1; transform: scale(1.04); }
  }
</style>
<div id="app">
  <div class="compass-preloader" aria-label="Initializing Compass Systems">
    <div class="compass-preloader__rings">
      <div class="compass-preloader__ring compass-preloader__ring--outer"></div>
      <div class="compass-preloader__ring compass-preloader__ring--mid"></div>
      <div class="compass-preloader__ring compass-preloader__ring--inner"></div>
      <div class="compass-preloader__core">
        <svg viewBox="0 0 24 24">
          <polygon points="12,2 15,9 22,12 15,15 12,22 9,15 2,12 9,9" />
        </svg>
      </div>
    </div>
    <p class="compass-preloader__title">COMPASS</p>
    <p class="compass-preloader__status">INITIALIZING SYSTEMS...</p>
  </div>
</div>
