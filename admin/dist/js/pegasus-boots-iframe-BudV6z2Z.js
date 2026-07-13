import{f as l,cq as b,e as w,d as f,V as h,cc as g,cf as y,l as x,m as v}from"./vendor-CFT-b7LE.js";import{a as k}from"./index-C9kUF5is.js";import"./vendor-core-C1HcRbWD.js";import"./vendor-vuetify-DhVtP5FX.js";import"./vendor-three-DbgnMstn.js";import"./vendor-echarts-CEu8zpoU.js";const E=l({name:"PegasusBootsIframe",setup(){const i=b(),r=w(null);return{wpPage:f(()=>i.meta.wpPage||"wds_wizard"),iframeRef:r,injectStyles:()=>{const a=r.value?.$el||r.value;if(a)try{const e=a.contentDocument||a.contentWindow?.document;if(!e)return;e.documentElement.classList.add("compass-theme-transparent","compass-embed-mode"),e.body.classList.add("compass-theme-transparent","compass-embed-mode");const t=()=>{if(!a||!e.body)return;const m=e.querySelector(".sui-wrap"),u=e.getElementById("wpwrap");let s=e.documentElement.scrollHeight;m&&(s=Math.max(s,m.scrollHeight+50)),u&&(s=Math.max(s,u.scrollHeight+50)),a.style.height=`${s}px`};setTimeout(t,100),setTimeout(t,500),setTimeout(t,1500);const n=new ResizeObserver(t);n.observe(e.body);const p=e.querySelector(".sui-wrap");p&&n.observe(p),new MutationObserver(t).observe(e.body,{childList:!0,subtree:!0});let o=e.getElementById("compass-sui-overrides");o||(o=e.createElement("style"),o.id="compass-sui-overrides",o.innerHTML=`
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
            
            .sui-modal-content, .sui-modal-slide {
              background: rgba(18, 30, 50, 0.85) !important;
              backdrop-filter: blur(20px) !important;
              -webkit-backdrop-filter: blur(20px) !important;
              border: 1px solid rgba(90, 105, 172, 0.3) !important;
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
          `,e.head.appendChild(o))}catch(e){console.warn("[Pegasus Boots] Could not inject iframe styles",e)}}}}}),_={class:"pegasus-boots-iframe-container position-relative"};function H(i,r,d,c,a,e){const t=h("x-iframe");return g(),y("div",_,[x(t,{ref:"iframeRef",name:"compass-sub-app",class:"seo-iframe",src:`/wp-admin/admin.php?page=${i.wpPage}&compass_iframe=1&embed=1&theme=transparent`,onLoad:i.injectStyles},null,8,["src","onLoad"]),r[0]||(r[0]=v("div",{class:"browser-overlay pointer-events-none"},null,-1))])}const P=k(E,[["render",H],["__scopeId","data-v-bed07883"]]);export{P as default};
