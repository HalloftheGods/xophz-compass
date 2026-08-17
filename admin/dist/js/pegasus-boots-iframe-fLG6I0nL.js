import{f as x,co as v,e as k,d as y,V as B,ca as M,cd as _,l as C,m as S}from"./vendor-BYnpyi9e.js";import{a as T}from"./index-BDMgGDiV.js";import"./vendor-core-jhTGTKns.js";import"./vendor-vuetify-BTxFokL4.js";import"./vendor-three-DbgnMstn.js";import"./vendor-echarts-Cc1PmGFJ.js";const L=x({name:"PegasusBootsIframe",setup(){const u=v(),i=k(null);return{wpPage:y(()=>u.meta.wpPage||"wds_wizard"),iframeRef:i,injectStyles:()=>{const e=i.value?.$el||i.value;if(e)try{const t=e.contentDocument||e.contentWindow?.document;if(!t)return;t.documentElement.classList.add("compass-theme-transparent","compass-embed-mode","compass-wpmudev-iframe"),t.body.classList.add("compass-theme-transparent","compass-embed-mode","compass-wpmudev-iframe");const o=()=>{if(!e||!t.body)return;const p=t.querySelector(".sui-wrap"),d=t.getElementById("wpbody-content"),c=t.getElementById("wpwrap");let r=0;if(p){const a=p.getBoundingClientRect();r=Math.max(r,p.scrollHeight,Math.ceil(a.height))}if(d){const a=d.getBoundingClientRect();r=Math.max(r,d.scrollHeight,Math.ceil(a.height))}if(c&&(r=Math.max(r,c.scrollHeight)),r===0&&t.body.children.length>0)for(let a=0;a<t.body.children.length;a++){const s=t.body.children[a];if(s&&s.tagName!=="SCRIPT"&&s.tagName!=="STYLE"){const g=s.offsetTop+s.offsetHeight;g>r&&(r=g)}}const w=Math.max(r+40,600);e.style.height=`${w}px`};o(),setTimeout(o,100),setTimeout(o,300),setTimeout(o,600),setTimeout(o,1200),setTimeout(o,2500);const m=new ResizeObserver(o),b=t.querySelector(".sui-wrap");b&&m.observe(b);const l=t.getElementById("wpbody-content");l&&m.observe(l),new MutationObserver(o).observe(t.body,{childList:!0,subtree:!0,attributes:!0});let n=t.getElementById("compass-sui-overrides");n||(n=t.createElement("style"),n.id="compass-sui-overrides",n.innerHTML=`
            html, body {
              overflow-x: hidden !important;
              overflow-y: auto !important;
              background: transparent !important;
              background-color: transparent !important;
              box-shadow: none !important;
              height: auto !important;
              min-height: 100% !important;
              max-height: none !important;
              margin: 0 !important;
              padding: 0 !important;
            }
            
            #wpwrap, #wpbody, #wpcontent, #wpbody-content,
            .wpmud-admin-wrap, .sui-wrap, .sui-app-wrapper, .sui-header,
            .notice, .update-nag, .sui-notice, .error, .updated { 
              background: transparent !important; 
              background-color: transparent !important; 
              box-shadow: none !important;
            }
            
            #wpwrap, #wpbody, #wpcontent, #wpbody-content,
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
            .sui-modal-slide, .sui-modal-slide.sui-active, .sui-modal-content, .sui-dialog-content {
              border: none !important;
              background: transparent !important;
              background-color: transparent !important;
              box-shadow: none !important;
              backdrop-filter: none !important;
              -webkit-backdrop-filter: none !important;
            }

            /* Modal Overlay (Full Viewport Dark Mask) */
            .sui-modal, .sui-dialog, .sui-modal-overlay, .sui-dialog-overlay {
              position: fixed !important;
              inset: 0 !important;
              top: 0 !important;
              left: 0 !important;
              width: 100vw !important;
              height: 100vh !important;
              background: rgba(5, 8, 14, 0.88) !important;
              backdrop-filter: blur(16px) saturate(180%) !important;
              -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
              z-index: 999999 !important;
            }

            /* Single Inner Modal Container (Solid Dark Glass Background) */
            .sui-modal .sui-box, .sui-dialog .sui-box, .sui-modal-content .sui-box, [class*="wds-"] .sui-modal .sui-box, [class*="wds-"] .sui-dialog .sui-box {
              background: rgba(13, 18, 29, 0.96) !important;
              backdrop-filter: blur(20px) !important;
              -webkit-backdrop-filter: blur(20px) !important;
              border: 1px solid rgba(98, 201, 255, 0.3) !important;
              border-radius: 16px !important;
              box-shadow: 0 24px 70px rgba(0, 0, 0, 0.95), 0 0 30px rgba(98, 201, 255, 0.12) !important;
              color: #ffffff !important;
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
            .sui-button, .sui-button-ghost, .sui-wrap .sui-button, .sui-wrap .sui-button-ghost, .sui-modal .sui-button, .sui-modal .sui-button-ghost, .sui-dialog .sui-button, .sui-dialog .sui-button-ghost {
              background: rgba(217, 190, 111, 0.08) !important;
              border: 1px solid var(--gold, #d9be6f) !important;
              color: var(--gold, #d9be6f) !important;
              font-weight: 700 !important;
              border-radius: 8px !important;
            }

            .sui-button:hover, .sui-button-ghost:hover, .sui-wrap .sui-button:hover, .sui-wrap .sui-button-ghost:hover, .sui-modal .sui-button:hover, .sui-modal .sui-button-ghost:hover, .sui-dialog .sui-button:hover, .sui-dialog .sui-button-ghost:hover {
              background: rgba(98, 201, 255, 0.15) !important;
              border-color: var(--accent, #62c9ff) !important;
              color: var(--accent, #62c9ff) !important;
            }

            /* Solid Gold Primary CTA Buttons (Override Base) */
            .sui-button.sui-button-primary, .sui-button.sui-button-blue, .sui-wrap .sui-button.sui-button-primary, .sui-wrap .sui-button.sui-button-blue, .sui-modal .sui-button.sui-button-primary, .sui-modal .sui-button.sui-button-blue, .sui-dialog .sui-button.sui-button-primary, .sui-dialog .sui-button.sui-button-blue {
              background: var(--gold, #d9be6f) !important;
              background-color: var(--gold, #d9be6f) !important;
              border: 1px solid var(--gold, #d9be6f) !important;
              color: #ffffff !important;
              text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6) !important;
              font-weight: 700 !important;
              text-transform: uppercase !important;
              letter-spacing: 0.5px !important;
              border-radius: 8px !important;
            }

            .sui-button.sui-button-primary:hover, .sui-button.sui-button-blue:hover, .sui-wrap .sui-button.sui-button-primary:hover, .sui-wrap .sui-button.sui-button-blue:hover, .sui-modal .sui-button.sui-button-primary:hover, .sui-modal .sui-button.sui-button-blue:hover, .sui-dialog .sui-button.sui-button-primary:hover, .sui-dialog .sui-button.sui-button-blue:hover {
              background: var(--accent, #62c9ff) !important;
              background-color: var(--accent, #62c9ff) !important;
              border-color: var(--accent, #62c9ff) !important;
              color: #ffffff !important;
              text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6) !important;
            }

            .sui-wrap, .sui-wrap h1, .sui-wrap h2, .sui-wrap h3, .sui-wrap h4, 
            .sui-wrap p, .sui-wrap span, .sui-wrap div, .sui-wrap label, .sui-wrap li {
              color: #f8f8f2 !important;
            }

            .sui-wrap .sui-button {
              border: 1px solid rgba(255, 255, 255, 0.2) !important;
            }

            ::-webkit-scrollbar { width: 8px !important; height: 8px !important; }
            ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.2) !important; }
            ::-webkit-scrollbar-thumb { background: rgba(98, 201, 255, 0.4) !important; border-radius: 4px !important; }
            ::-webkit-scrollbar-thumb:hover { background: rgba(98, 201, 255, 0.7) !important; }
          `,t.head.appendChild(n))}catch(t){console.warn("[Pegasus Boots] Could not inject iframe styles",t)}}}}}),E={class:"pegasus-boots-iframe-container position-relative"};function H(u,i,h,f,e,t){const o=B("x-iframe");return M(),_("div",E,[C(o,{ref:"iframeRef",name:"compass-sub-app",class:"seo-iframe",src:`/wp-admin/admin.php?page=${u.wpPage}&compass_iframe=1&embed=1&theme=transparent`,onLoad:u.injectStyles},null,8,["src","onLoad"]),i[0]||(i[0]=S("div",{class:"browser-overlay pointer-events-none"},null,-1))])}const G=T(L,[["render",H],["__scopeId","data-v-3bd7ceb0"]]);export{G as default};
