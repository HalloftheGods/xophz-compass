import{f as i,cj as p,e as d,d as c,V as m,c5 as u,c6 as l,l as b,m as w}from"./vendor-JOM5PyOK.js";import{a as f}from"./index-t2kUdy60.js";import"./vendor-core-C4-i1vzd.js";import"./vendor-vuetify-C19UQkbj.js";import"./vendor-three-DbgnMstn.js";import"./vendor-echarts-DHMG2Jr9.js";const g=i({name:"MagicWandIframe",setup(){const t=p(),a=d(null);return{wpPage:c(()=>t.meta.wpPage||"wds_wizard"),iframeRef:a,injectStyles:()=>{if(a.value)try{const r=a.value.contentDocument||a.value.contentWindow?.document;if(!r)return;r.documentElement.classList.add("compass-theme-transparent","compass-embed-mode"),r.body.classList.add("compass-theme-transparent","compass-embed-mode");let e=r.getElementById("compass-sui-overrides");e||(e=r.createElement("style"),e.id="compass-sui-overrides",e.innerHTML=`
            /* Core transparent backings */
            html, body, #wpwrap, #wpbody, #wpcontent, #wpbody-content,
            .wpmud-admin-wrap, .sui-wrap, .sui-app-wrapper, .sui-header,
            .notice, .update-nag, .sui-notice, .error, .updated { 
              background: transparent !important; 
              background-color: transparent !important; 
              box-shadow: none !important;
            }
            
            /* Strip SUI core wrappers */
            .sui-wrap, .sui-app-wrapper, .wpmud-admin-wrap { 
              margin: 0 !important;
              max-width: 100% !important;
            }

            /* Convert SUI Cards to Glass */
            .sui-wrap .sui-box {
              background: rgba(13, 17, 23, 0.4) !important;
              backdrop-filter: blur(10px) !important;
              -webkit-backdrop-filter: blur(10px) !important;
              border: 1px solid rgba(90, 105, 172, 0.3) !important;
              box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2) !important;
            }
            
            /* Strip nested box backgrounds */
            .sui-wrap .sui-box-header, .sui-wrap .sui-box-body, .sui-wrap .sui-box-footer, .sui-wrap .sui-header {
              background: transparent !important;
              border-color: rgba(255, 255, 255, 0.05) !important;
            }
            
            /* Modals */
            .sui-modal-content, .sui-modal-slide {
              background: rgba(18, 30, 50, 0.85) !important;
              backdrop-filter: blur(20px) !important;
              -webkit-backdrop-filter: blur(20px) !important;
              border: 1px solid rgba(90, 105, 172, 0.3) !important;
            }

            /* Force Legible Text (Light Mode Text -> Dark Mode Text) */
            .sui-wrap, .sui-wrap h1, .sui-wrap h2, .sui-wrap h3, .sui-wrap h4, 
            .sui-wrap p, .sui-wrap span, .sui-wrap div, .sui-wrap label, .sui-wrap li {
              color: #f8f8f2 !important;
            }

            /* Fix Buttons for Glass Theme */
            .sui-wrap .sui-button {
              border: 1px solid rgba(255, 255, 255, 0.2) !important;
            }

            /* Custom Scrollbars */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
          `,r.head.appendChild(e))}catch(r){console.warn("[Magic Wand] Could not inject iframe styles",r)}}}}}),x={class:"magic-wand-iframe-container position-relative"};function h(t,a,o,s,r,e){const n=m("x-iframe");return u(),l("div",x,[b(n,{ref:"iframeRef",name:"compass-sub-app",class:"seo-iframe",src:`/wp-admin/admin.php?page=${t.wpPage}&compass_iframe=1&embed=1&theme=transparent`,onLoad:t.injectStyles},null,8,["src","onLoad"]),a[0]||(a[0]=w("div",{class:"browser-overlay pointer-events-none"},null,-1))])}const L=f(g,[["render",h],["__scopeId","data-v-1390a9a5"]]);export{L as default};
