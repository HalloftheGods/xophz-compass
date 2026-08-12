import{f as l,co as c,e as g,d as f,V as h,ca as w,cd as x,l as v,m as k}from"./vendor-C_wzOzOP.js";import{a as y}from"./index-D7MzGdJK.js";import"./vendor-core-jhTGTKns.js";import"./vendor-vuetify-BC38IVfl.js";import"./vendor-three-DbgnMstn.js";import"./vendor-echarts-DKcEROtP.js";const B=l({name:"PegasusBootsIframe",setup(){const n=c(),r=g(null);return{wpPage:f(()=>n.meta.wpPage||"wds_wizard"),iframeRef:r,injectStyles:()=>{const a=r.value?.$el||r.value;if(a)try{const t=a.contentDocument||a.contentWindow?.document;if(!t)return;t.documentElement.classList.add("compass-theme-transparent","compass-embed-mode"),t.body.classList.add("compass-theme-transparent","compass-embed-mode");const o=()=>{if(!a||!t.body)return;const u=t.querySelector(".sui-wrap"),d=t.getElementById("wpwrap");let e=t.documentElement.scrollHeight;u&&(e=Math.max(e,u.scrollHeight+50)),d&&(e=Math.max(e,d.scrollHeight+50)),a.style.height=`${e}px`};setTimeout(o,100),setTimeout(o,500),setTimeout(o,1500);const s=new ResizeObserver(o);s.observe(t.body);const p=t.querySelector(".sui-wrap");p&&s.observe(p),new MutationObserver(o).observe(t.body,{childList:!0,subtree:!0});let i=t.getElementById("compass-sui-overrides");i||(i=t.createElement("style"),i.id="compass-sui-overrides",i.innerHTML=`
            html, body, #wpwrap, #wpbody, #wpcontent, #wpbody-content,
            .wpmud-admin-wrap, .sui-wrap, .sui-app-wrapper, .sui-header,
            .notice, .update-nag, .sui-notice, .error, .updated { 
              background: transparent !important; 
              background-color: transparent !important; 
              box-shadow: none !important;
            }
            
            /* FORCE EVERYTHING TO OVERFLOW VISIBLE AND HEIGHT AUTO */
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

            .sui-wrap .sui-box {
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
            .sui-modal, .sui-modal.sui-active, .sui-dialog, .sui-modal-content, .sui-modal-slide, .sui-modal-slide.sui-active {
              border: none !important;
              background: transparent !important;
              box-shadow: none !important;
              backdrop-filter: none !important;
              -webkit-backdrop-filter: none !important;
            }

            /* Modal Overlay (Full Viewport Dark Mask - Strong Blur, No Text Bleed) */
            .sui-modal, .sui-dialog {
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
            .sui-modal .sui-box, .sui-dialog .sui-box {
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

            /* Hide custom scrollbars */
            ::-webkit-scrollbar { display: none !important; }
          `,t.head.appendChild(i))}catch(t){console.warn("[Pegasus Boots] Could not inject iframe styles",t)}}}}}),E={class:"pegasus-boots-iframe-container position-relative"};function _(n,r,m,b,a,t){const o=h("x-iframe");return w(),x("div",E,[v(o,{ref:"iframeRef",name:"compass-sub-app",class:"seo-iframe",src:`/wp-admin/admin.php?page=${n.wpPage}&compass_iframe=1&embed=1&theme=transparent`,onLoad:n.injectStyles},null,8,["src","onLoad"]),r[0]||(r[0]=k("div",{class:"browser-overlay pointer-events-none"},null,-1))])}const M=y(B,[["render",_],["__scopeId","data-v-97358aec"]]);export{M as default};
