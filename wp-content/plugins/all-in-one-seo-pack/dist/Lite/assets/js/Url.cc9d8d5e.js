import{u as a,g as b}from"./index.5a710757.js";import{g as f}from"./runtime-dom.esm-bundler.6789c400.js";const R=()=>{const d=f(!1),g=f(!1),h=t=>{if(!t)return"";let r=t;if(e(t)){const o=t.split("#")[1].split(":");r=a().aioseo.urls.aio.dashboard.replace("page=aioseo",`page=${o[0]}#/${o[1]}`)}return r},m=t=>e(t)?null:"_blank",e=t=>t.startsWith("http://route#")||t.startsWith("https://route#"),c=t=>t.startsWith("http://action#")||t.startsWith("https://action#"),y=t=>c(t)?"button":"a",_=(t,r)=>{if(c(t))return i(t);if(e(t))return u(t)},i=(t,r)=>{const s=/[^#?]*/gm,o=t.match(s),n=o[2];b().processButtonAction(n).then(()=>{if(o[4]&&o[4].startsWith("redirect=")){const A=a(),l=o[4].replace("redirect=","").split(":"),p=A.aioseo.urls.aio.dashboard.replace("page=aioseo",`page=${l[0]}#/${l[1]}`);if(p===window.location.href){window.location.reload();return}window.location.href=p}else o[4]&&o[4].startsWith("refresh")&&window.location.reload()})},u=t=>{const r=a(),s=/[^#?]*/gm,n=t.match(s)[2].split(":");r.aioseo.urls.aio.dashboard.replace("page=aioseo",`page=${n[0]}#/${n[1]}`)===window.location.href&&window.location.reload()};return{button1Loading:d,button2Loading:g,getHref:h,getTagType:y,getTarget:m,processAction:i,processButtonClick:_,processRoute:u,decodeUrl:t=>{if(!t)return null;try{return decodeURIComponent(t)}catch{return t}}}};export{R as u};
