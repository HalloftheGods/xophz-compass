function c(t=1500,o=Date.now){let e="",a=0;return{accept(r){const n=o(),s=r===e&&n-a<t;return e=r,a=n,!s},reset(){e="",a=0}}}const l=t=>"$"+(Number(t)||0).toFixed(2);export{c,l as f};
