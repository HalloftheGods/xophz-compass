import{f as b,co as c,e as f,d as g,V as h,ca as w,cd as x,l as v,m as k}from"./vendor-Dvu0Py7D.js";import{a as y}from"./index-AqZvfEsv.js";import"./vendor-core-jhTGTKns.js";import"./vendor-vuetify-CcyqBnvQ.js";import"./vendor-three-DbgnMstn.js";import"./vendor-echarts-CMoqborU.js";const B=b({name:"MagicFormulaIframe",setup(){const n=c(),r=f(null);return{wpPage:g(()=>n.meta.wpPage||"forminator"),iframeRef:r,injectStyles:()=>{const a=r.value?.$el||r.value;if(a)try{const o=a.contentDocument||a.contentWindow?.document;if(!o)return;o.documentElement.classList.add("compass-theme-transparent","compass-embed-mode"),o.body.classList.add("compass-theme-transparent","compass-embed-mode");const t=()=>{if(!a||!o.body)return;const p=o.querySelector(".sui-wrap, .forminator-ui"),m=o.getElementById("wpwrap");let e=o.documentElement.scrollHeight;p&&(e=Math.max(e,p.scrollHeight+50)),m&&(e=Math.max(e,m.scrollHeight+50)),a.style.height=`${e}px`};setTimeout(t,100),setTimeout(t,500),setTimeout(t,1500);const s=new ResizeObserver(t);s.observe(o.body);const u=o.querySelector(".sui-wrap, .forminator-ui");u&&s.observe(u),new MutationObserver(t).observe(o.body,{childList:!0,subtree:!0});let i=o.getElementById("compass-sui-overrides");i||(i=o.createElement("style"),i.id="compass-sui-overrides",i.innerHTML=`
            html, body, #wpwrap, #wpbody, #wpcontent, #wpbody-content,
            .wpmud-admin-wrap, .sui-wrap, .sui-app-wrapper, .sui-header,
            .notice, .update-nag, .sui-notice, .error, .updated { 
              background: transparent !important; 
              background-color: transparent !important; 
              box-shadow: none !important;
            }
            
            html, body, #wpwrap, #wpbody, #wpcontent, #wpbody-content,
            .wpmud-admin-wrap, .sui-wrap, .sui-app-wrapper {
              overflow: visible !important;
              height: auto !important;
              min-height: auto !important;
              max-height: none !important;
            }
            
            .sui-wrap, .sui-app-wrapper, .wpmud-admin-wrap { 
              margin: 0 !important;
              max-width: 100% !important;
            }

            .sui-wrap .sui-box, .forminator-ui .sui-box {
              background: rgba(13, 17, 23, 0.4) !important;
              backdrop-filter: blur(10px) !important;
              -webkit-backdrop-filter: blur(10px) !important;
              border: 1px solid rgba(90, 105, 172, 0.3) !important;
              box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2) !important;
            }
            
            .sui-wrap .sui-box-header, .sui-wrap .sui-box-body, .sui-wrap .sui-box-footer, .sui-wrap .sui-header {
              background: transparent !important;
              border-color: rgba(255, 255, 255, 0.05) !important;
            }
            
            /* Strip ALL outer wrappers of borders, backgrounds, and shadows to fix double-box */
            .sui-modal, .sui-modal.sui-active, .sui-dialog, .sui-modal-content, .sui-modal-slide, .sui-modal-slide.sui-active, .forminator-modal {
              border: none !important;
              background: transparent !important;
              box-shadow: none !important;
              backdrop-filter: none !important;
              -webkit-backdrop-filter: none !important;
            }

            /* Modal Overlay (Full Viewport Dark Mask - Strong Blur, No Text Bleed) */
            .sui-modal, .sui-dialog, .forminator-modal {
              position: fixed !important;
              inset: 0 !important;
              top: 0 !important;
              left: 0 !important;
              width: 100vw !important;
              height: 100vh !important;
              background: rgba(5, 8, 14, 0.94) !important;
              backdrop-filter: blur(28px) saturate(200%) !important;
              -webkit-backdrop-filter: blur(28px) saturate(200%) !important;
              z-index: 999999 !important;
            }

            /* Single Inner Modal Container (Solid Dark Background) */
            .sui-modal .sui-box, .sui-dialog .sui-box, .forminator-modal .sui-box {
              background: #0d121d !important;
              border: 1px solid rgba(98, 201, 255, 0.3) !important;
              border-radius: 16px !important;
              box-shadow: 0 24px 70px rgba(0, 0, 0, 0.95), 0 0 40px rgba(98, 201, 255, 0.15) !important;
              overflow: hidden !important;
              margin: auto !important;
              z-index: 1000000 !important;
            }

            /* Disable Header White Circular Graphic & Pseudoelements */
            .sui-box-header::before, .sui-box-header::after,
            .sui-box-banner::before, .sui-box-banner::after,
            .sui-box-header-image::before, .sui-box-header-image::after,
            .sui-modal-slide .sui-box-header::before, .sui-modal-slide .sui-box-header::after,
            .sui-modal-slide .sui-box-header.sui-flatten::before, .sui-modal-slide .sui-box-header.sui-flatten::after {
              display: none !important;
              content: none !important;
              background: none !important;
              background-image: none !important;
            }

            .sui-box-header, .sui-box-banner, .sui-box-header-image, .sui-modal .sui-box-header {
              background: transparent !important;
              background-image: none !important;
              box-shadow: none !important;
            }

            .sui-box-header .sui-box-banner img, .sui-box-header img, .sui-box-header-image img {
              background: transparent !important;
              filter: drop-shadow(0 0 12px rgba(98, 201, 255, 0.4)) !important;
            }

            /* Modal Close Button */
            .sui-modal .sui-button-icon[data-modal-close], .sui-dialog .sui-button-icon[data-modal-close], .sui-modal .sui-box-header .sui-button-icon, .sui-modal-close {
              position: absolute !important;
              top: 20px !important;
              right: 20px !important;
              width: 36px !important;
              height: 36px !important;
              border-radius: 8px !important;
              background: rgba(255, 255, 255, 0.08) !important;
              border: 1px solid rgba(255, 255, 255, 0.15) !important;
              color: #f8f8f2 !important;
              display: flex !important;
              align-items: center !important;
              justify-content: center !important;
              cursor: pointer !important;
              transition: all 0.2s ease !important;
              z-index: 10 !important;
            }

            .sui-modal .sui-button-icon[data-modal-close]:hover, .sui-dialog .sui-button-icon[data-modal-close]:hover, .sui-modal .sui-box-header .sui-button-icon:hover, .sui-modal-close:hover {
              background: rgba(255, 92, 92, 0.25) !important;
              border-color: #ff5c5c !important;
              color: #ff5c5c !important;
            }

            /* Radio Buttons & Labels in Modals - Fix text overlap */
            .sui-radio {
              display: flex !important;
              flex-wrap: wrap !important;
              align-items: center !important;
              margin-bottom: 16px !important;
            }

            .sui-radio .sui-description {
              display: block !important;
              width: 100% !important;
              margin-left: 28px !important;
              margin-top: 4px !important;
              color: rgba(248, 248, 242, 0.75) !important;
              position: static !important;
              line-height: 1.4 !important;
            }

            .sui-radio .sui-radio-label {
              color: #ffffff !important;
              font-weight: 600 !important;
            }

            /* Ghost / Outlined Gold CTA Buttons (Base) */
            .sui-button, .sui-button-ghost, .sui-wrap .sui-button-ghost, .sui-modal .sui-button-ghost {
              background: rgba(217, 190, 111, 0.08) !important;
              border: 1px solid var(--gold, #d9be6f) !important;
              color: var(--gold, #d9be6f) !important;
              font-weight: 700 !important;
              border-radius: 8px !important;
            }

            .sui-button:hover, .sui-button-ghost:hover, .sui-wrap .sui-button-ghost:hover, .sui-modal .sui-button-ghost:hover {
              background: rgba(98, 201, 255, 0.15) !important;
              border-color: var(--accent, #62c9ff) !important;
              color: var(--accent, #62c9ff) !important;
            }

            /* Solid Gold Primary CTA Buttons (Override Base) */
            .sui-button.sui-button-primary, .sui-button.sui-button-blue, .sui-wrap .sui-button.sui-button-primary, .sui-wrap .sui-button.sui-button-blue, .sui-modal .sui-button.sui-button-primary, .sui-modal .sui-button.sui-button-blue {
              background: var(--gold, #d9be6f) !important;
              background-color: var(--gold, #d9be6f) !important;
              border-color: var(--gold, #d9be6f) !important;
              color: #0d1117 !important;
              font-weight: 700 !important;
              text-transform: uppercase !important;
              letter-spacing: 0.5px !important;
              border-radius: 8px !important;
            }

            .sui-button.sui-button-primary:hover, .sui-button.sui-button-blue:hover, .sui-wrap .sui-button.sui-button-primary:hover, .sui-wrap .sui-button.sui-button-blue:hover, .sui-modal .sui-button.sui-button-primary:hover, .sui-modal .sui-button.sui-button-blue:hover {
              background: var(--accent, #62c9ff) !important;
              background-color: var(--accent, #62c9ff) !important;
              border-color: var(--accent, #62c9ff) !important;
              color: #0d1117 !important;
            }

            .sui-wrap, .sui-wrap h1, .sui-wrap h2, .sui-wrap h3, .sui-wrap h4, 
            .sui-wrap p, .sui-wrap span, .sui-wrap div, .sui-wrap label, .sui-wrap li {
              color: #f8f8f2 !important;
            }

            .sui-wrap .sui-button {
              border: 1px solid rgba(255, 255, 255, 0.2) !important;
            }

            ::-webkit-scrollbar { display: none !important; }
          `,o.head.appendChild(i))}catch(o){console.warn("[Magic Formula] Could not inject iframe styles",o)}}}}}),_={class:"magic-formula-iframe-container position-relative"};function M(n,r,d,l,a,o){const t=h("x-iframe");return w(),x("div",_,[v(t,{ref:"iframeRef",name:"compass-sub-app",class:"forminator-iframe",src:`/wp-admin/admin.php?page=${n.wpPage}&compass_iframe=1&embed=1&theme=transparent`,onLoad:n.injectStyles},null,8,["src","onLoad"]),r[0]||(r[0]=k("div",{class:"browser-overlay pointer-events-none"},null,-1))])}const $=y(B,[["render",M],["__scopeId","data-v-b76c5ffc"]]);export{$ as default};
