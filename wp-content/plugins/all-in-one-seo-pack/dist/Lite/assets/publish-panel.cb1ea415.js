import{v as P,o as c,c as l,a as n,F as Z,J as G,k as K,G as U,q as j,x as L,t as m,C as b,b as f,Y as ue}from"./js/runtime-dom.esm-bundler.6789c400.js";import{l as de}from"./js/index.3eb8f806.js";import{l as pe}from"./js/index.01e1b194.js";import{l as me}from"./js/index.1ca84f0e.js";import{b as ge,e as he,k as fe,i as H,B as _e,c as q,u as ve}from"./js/index.5a710757.js";import{a as Se}from"./js/allowed.b971be4c.js";import{i as be}from"./js/helpers.d58a8f25.js";import{v as ye}from"./js/helpers.f95d5840.js";import{u as Pe}from"./js/Image.20261278.js";import{u as we}from"./js/Tags.8234a3d8.js";import{C as xe}from"./js/GoogleSearchPreview.b2f44fb4.js";import{c as Ee,e as ke,f as Ae}from"./js/Caret.662da1f3.js";import{S as Ce}from"./js/Exclamation.bf79561a.js";import{S as Te}from"./js/External.c84e4310.js";import"./js/translations.12335a6a.js";import{_}from"./js/_plugin-vue_export-helper.249dac1d.js";import{_ as r,s as Re,a as Oe}from"./js/default-i18n.54b5d8cd.js";import{S as Ie}from"./js/Twitter.faae4f4e.js";import{s as Me}from"./js/metabox.1128ddb8.js";import{l as Ue}from"./js/loadTruSeo.dfb0c3ea.js";import{e as Le}from"./js/elemLoaded.5e0343ce.js";import{t as J}from"./js/toString.1401d490.js";import{u as Ve}from"./js/upperFirst.96c04516.js";import{d as De}from"./js/cleanForSlug.f9ffe7db.js";import"./js/_baseSet.32e7a763.js";import"./js/regex.f8017116.js";import"./js/constants.2019bcb3.js";import"./js/Url.cc9d8d5e.js";import"./js/_stringToArray.08127ca9.js";import"./js/_baseTrim.940c51cf.js";function $e(e){return Ve(J(e).toLowerCase())}function ze(e,t,o,s){for(var a=-1,p=e==null?0:e.length;++a<p;)o=t(o,e[a],a,e);return o}var Be=/[^\x00-\x2f\x3a-\x40\x5b-\x60\x7b-\x7f]+/g;function Ne(e){return e.match(Be)||[]}var Fe=/[a-z][A-Z]|[A-Z]{2}[a-z]|[0-9][a-zA-Z]|[a-zA-Z][0-9]|[^a-zA-Z0-9 ]/;function We(e){return Fe.test(e)}var Y="\\ud800-\\udfff",Ze="\\u0300-\\u036f",Ge="\\ufe20-\\ufe2f",Ke="\\u20d0-\\u20ff",je=Ze+Ge+Ke,Q="\\u2700-\\u27bf",X="a-z\\xdf-\\xf6\\xf8-\\xff",He="\\xac\\xb1\\xd7\\xf7",qe="\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf",Je="\\u2000-\\u206f",Ye=" \\t\\x0b\\f\\xa0\\ufeff\\n\\r\\u2028\\u2029\\u1680\\u180e\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u202f\\u205f\\u3000",ee="A-Z\\xc0-\\xd6\\xd8-\\xde",Qe="\\ufe0e\\ufe0f",te=He+qe+Je+Ye,oe="['’]",V="["+te+"]",Xe="["+je+"]",se="\\d+",et="["+Q+"]",re="["+X+"]",ne="[^"+Y+te+se+Q+X+ee+"]",tt="\\ud83c[\\udffb-\\udfff]",ot="(?:"+Xe+"|"+tt+")",st="[^"+Y+"]",ie="(?:\\ud83c[\\udde6-\\uddff]){2}",ae="[\\ud800-\\udbff][\\udc00-\\udfff]",h="["+ee+"]",rt="\\u200d",D="(?:"+re+"|"+ne+")",nt="(?:"+h+"|"+ne+")",$="(?:"+oe+"(?:d|ll|m|re|s|t|ve))?",z="(?:"+oe+"(?:D|LL|M|RE|S|T|VE))?",ce=ot+"?",le="["+Qe+"]?",it="(?:"+rt+"(?:"+[st,ie,ae].join("|")+")"+le+ce+")*",at="\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])",ct="\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])",lt=le+ce+it,ut="(?:"+[et,ie,ae].join("|")+")"+lt,dt=RegExp([h+"?"+re+"+"+$+"(?="+[V,h,"$"].join("|")+")",nt+"+"+z+"(?="+[V,h+D,"$"].join("|")+")",h+"?"+D+"+"+$,h+"+"+z,ct,at,se,ut].join("|"),"g");function pt(e){return e.match(dt)||[]}function mt(e,t,o){return e=J(e),t=t,t===void 0?We(e)?pt(e):Ne(e):e.match(t)||[]}var gt="['’]",ht=RegExp(gt,"g");function ft(e){return function(t){return ze(mt(De(t).replace(ht,"")),e,"")}}var B=ft(function(e,t,o){return t=t.toLowerCase(),e+(o?$e(t):t)});const i="all-in-one-seo-pack",_t={setup(){const{imageUrl:e,setImageUrl:t}=Pe(),{parseTags:o}=we({separator:void 0});return{imageUrl:e,optionsStore:ge(),parseTags:o,postEditorStore:he(),setImageUrl:t,settingsStore:fe(),tagsStore:H()}},components:{CoreGoogleSearchPreview:xe,SvgCircleCheck:Ee,SvgCircleClose:ke,SvgCircleExclamation:Ce,SvgExternal:Te,SvgPencil:Ae},data(){return{allowed:Se,socialImage:null,socialImageKey:0,strings:{serpPreview:r("SERP Preview",i),canonicalUrl:r("Canonical URL",i)}}},computed:{tips(){let e=[{label:r("Visibility",i),name:"visibility",access:"aioseo_page_advanced_settings"},{label:r("SEO Analysis",i),name:"seoAnalysis",access:"aioseo_page_analysis"},{label:r("Readability",i),name:"readabilityAnalysis",access:"aioseo_page_analysis"},{label:r("Focus Keyphrase",i),name:"focusKeyphrase",access:"aioseo_page_analysis"},{label:r("Social",i),name:"social",access:"aioseo_page_social_settings"}].filter(t=>this.allowed(t.access)&&(t.access!=="aioseo_page_analysis"||be()));return!this.optionsStore.options.social.facebook.general.enable&&!this.optionsStore.options.social.twitter.general.enable&&(e=e.filter(t=>t.name!=="social")),e.forEach((t,o)=>{e[o]={...t,...this.getData(t.name)}}),e},canImprove(){return this.tips.some(e=>e.type==="error")}},methods:{getIcon(e){switch(e){case"error":return"svg-circle-close";case"warning":return"svg-circle-exclamation";case"success":default:return"svg-circle-check"}},getData(e){const t={};if(e==="visibility"&&(t.value=r("Good!",i),t.type="success",(this.postEditorStore.currentPost.default?this.optionsStore.dynamicOptions.searchAppearance.postTypes[this.postEditorStore.currentPost.postType]&&!this.optionsStore.dynamicOptions.searchAppearance.postTypes[this.postEditorStore.currentPost.postType].advanced.robotsMeta.default&&this.optionsStore.dynamicOptions.searchAppearance.postTypes[this.postEditorStore.currentPost.postType].advanced.robotsMeta.noindex:this.postEditorStore.currentPost.noindex)&&(t.value=r("Blocked!",i),t.type="error")),e==="seoAnalysis"){t.value=r("N/A",i),t.type="error";const o=this.postEditorStore.currentPost.seo_score;Number.isInteger(o)&&(t.value=o+"/100",t.type=80<o?"success":50<o?"warning":"error")}if(e==="readabilityAnalysis"){t.value=r("Good!",i),t.type="success";const o=this.postEditorStore.currentPost.page_analysis.analysis.readability.errors;o&&0<o&&(t.value=Re(Oe("%1$s error found!","%1$s errors found!",o,i),o),t.type="error")}if(e==="focusKeyphrase"){t.value=r("No focus keyphrase!",i),t.type="error";const o=this.postEditorStore.currentPost.keyphrases.focus;o&&o.keyphrase&&(t.value=o.score+"/100",t.type=80<o.score?"success":50<o.score?"warning":"error")}if(e==="social"){t.value=r("Good!",i),t.type="success",this.socialImageKey;const o=this.parseTags(this.postEditorStore.currentPost.og_title||this.postEditorStore.currentPost.title||this.postEditorStore.currentPost.tags.title).trim(),s=this.parseTags(this.postEditorStore.currentPost.og_description||this.postEditorStore.currentPost.description||this.postEditorStore.currentPost.tags.description).trim(),a=this.socialImage;(!o||!s||!a)&&(t.value=r("Missing social markup!",i),t.type="error")}return{...t,icon:this.getIcon(t.type)}},openSidebar(e){const{openGeneralSidebar:t}=window.wp.data.dispatch("core/edit-post"),{closePublishSidebar:o}=window.wp.data.dispatch(ye(window.aioseo.wpVersion,"6.6","<")?"core/edit-post":"core/editor");o(),t("aioseo-post-settings-sidebar/aioseo-post-settings-sidebar");const s={};switch(e){case"canonical":case"visibility":s.tab="advanced";break;case"seoAnalysis":s.tab="general",s.card="basicseo";break;case"readabilityAnalysis":s.tab="general",s.card="readability";break;case"focusKeyphrase":s.tab="general",s.card="focus";break;case"social":s.tab="social";break}this.settingsStore.changeTabSettings({setting:"mainSidebar",value:s})}},async mounted(){await this.setImageUrl().then(()=>{this.socialImage=this.imageUrl}),window.aioseoBus.$on("updateSocialImagePreview",e=>{this.socialImage=e.image,this.socialImageKey++}),this.$nextTick(()=>{const e=document.querySelector(".aioseo-pre-publish .editor-post-publish-panel__link");e&&(e.innerHTML=this.canImprove?r("Your post needs improvement!",i):r("You're good to go!",i))})}},vt={key:0,class:"seo-overview"},St={class:"pre-publish-checklist"},bt={class:"icon"},yt=["onClick"],Pt={key:0,class:"snippet-preview"},wt={class:"title"},xt={key:1,class:"canonical-url"},Et={class:"title"},kt=["href"];function At(e,t,o,s,a,p){const d=P("svg-pencil"),g=P("core-google-search-preview"),v=P("svg-external");return s.postEditorStore.currentPost.id?(c(),l("div",vt,[n("ul",St,[(c(!0),l(Z,null,G(p.tips,(u,S)=>(c(),l("li",{key:S},[n("span",bt,[(c(),K(j(u.icon),{class:U(u.type)},null,8,["class"]))]),n("span",null,[L(m(u.label)+": ",1),n("span",{class:U(["result",u.value.endsWith("/100")?u.type:null])},m(u.value),3)]),s.optionsStore.dynamicOptions.searchAppearance.postTypes[s.postEditorStore.currentPost.postType]&&s.optionsStore.dynamicOptions.searchAppearance.postTypes[s.postEditorStore.currentPost.postType].advanced.showMetaBox?(c(),l("span",{key:0,class:"edit",onClick:y=>p.openSidebar(u.name)},[b(d)],8,yt)):f("",!0)]))),128))]),a.allowed("aioseo_page_analysis")?(c(),l("div",Pt,[n("p",wt,m(a.strings.serpPreview)+":",1),b(g,{url:s.tagsStore.liveTags.permalink,title:s.parseTags(s.postEditorStore.currentPost.title||s.postEditorStore.currentPost.tags.title||"#post_title #separator_sa #site_title"),description:s.parseTags(s.postEditorStore.currentPost.description||s.postEditorStore.currentPost.tags.description||"#post_content")},null,8,["url","title","description"])])):f("",!0),a.allowed("aioseo_page_analysis")&&s.postEditorStore.currentPost.canonicalUrl?(c(),l("div",xt,[n("p",Et,[L(m(a.strings.canonicalUrl)+": ",1),n("span",{class:"edit",onClick:t[0]||(t[0]=u=>p.openSidebar("canonical"))},[b(d)])]),n("a",{href:s.postEditorStore.currentPost.canonicalUrl,target:"_blank",rel:"noopener noreferrer"},[n("span",null,m(s.postEditorStore.currentPost.canonicalUrl),1),b(v)],8,kt)])):f("",!0)])):f("",!0)}const N=_(_t,[["render",At]]),Ct={},Tt={width:"32",height:"32",fill:"none",class:"aioseo-facebook-rounded",xmlns:"http://www.w3.org/2000/svg"},Rt=n("circle",{cx:"16",cy:"16",r:"16",fill:"currentColor"},null,-1),Ot=n("path",{d:"M24 16c0-4.4-3.6-8-8-8s-8 3.6-8 8c0 4 2.9 7.3 6.7 7.9v-5.6h-2V16h2v-1.8c0-2 1.2-3.1 3-3.1.9 0 1.8.2 1.8.2v2h-1c-1 0-1.3.6-1.3 1.2V16h2.2l-.4 2.3h-1.9V24c4-.6 6.9-4 6.9-8z",fill:"#fff"},null,-1),It=[Rt,Ot];function Mt(e,t){return c(),l("svg",Tt,It)}const Ut=_(Ct,[["render",Mt]]),Lt={},Vt={width:"32",height:"32",fill:"none",class:"aioseo-linkedin-rounded",xmlns:"http://www.w3.org/2000/svg"},Dt=n("circle",{cx:"16",cy:"16",r:"16",fill:"currentColor"},null,-1),$t=n("path",{d:"M11.6 24H8.2V13.3h3.4V24zM9.9 11.8C8.8 11.8 8 11 8 9.9 8 8.8 8.9 8 9.9 8c1.1 0 1.9.8 1.9 1.9 0 1.1-.8 1.9-1.9 1.9zM24 24h-3.4v-5.8c0-1.7-.7-2.2-1.7-2.2s-2 .8-2 2.3V24h-3.4V13.3h3.2v1.5c.3-.7 1.5-1.8 3.2-1.8 1.9 0 3.9 1.1 3.9 4.4V24h.2z",fill:"#fff"},null,-1),zt=[Dt,$t];function Bt(e,t){return c(),l("svg",Vt,zt)}const Nt=_(Lt,[["render",Bt]]),Ft={},Wt={width:"32",height:"32",fill:"none",class:"aioseo-pinterest-rounded",xmlns:"http://www.w3.org/2000/svg"},Zt=n("circle",{cx:"16",cy:"16",r:"16",fill:"currentColor"},null,-1),Gt=n("path",{d:"M16.056 6.583c-5.312 0-9.658 4.346-9.658 9.658a9.581 9.581 0 005.795 8.813c0-.724 0-1.448.12-2.173.242-.845 1.207-5.312 1.207-5.312s-.362-.604-.362-1.57c0-1.448.846-2.535 1.811-2.535.845 0 1.328.604 1.328 1.45 0 .844-.603 2.172-.845 3.38-.241.965.483 1.81 1.57 1.81 1.81 0 3.018-2.293 3.018-5.19 0-2.174-1.449-3.743-3.984-3.743-2.898 0-4.709 2.173-4.709 4.587 0 .845.242 1.449.604 1.932.12.241.242.241.12.483 0 .12-.12.603-.24.724-.121.242-.242.362-.483.242-1.329-.604-1.932-2.053-1.932-3.743 0-2.777 2.294-6.036 6.881-6.036 3.743 0 6.157 2.656 6.157 5.553 0 3.743-2.052 6.64-5.19 6.64-1.087 0-2.053-.603-2.415-1.207 0 0-.604 2.173-.725 2.656a10.702 10.702 0 01-.966 2.052c.846.242 1.811.363 2.777.363 5.312 0 9.658-4.347 9.658-9.659.121-4.829-4.225-9.175-9.537-9.175z",fill:"#fff"},null,-1),Kt=[Zt,Gt];function jt(e,t){return c(),l("svg",Wt,Kt)}const Ht=_(Ft,[["render",jt]]),F="all-in-one-seo-pack",qt={setup(){return{tagsStore:H()}},components:{SvgFacebookRounded:Ut,SvgLinkedinRounded:Nt,SvgPinterestRounded:Ht,SvgIconTwitter:Ie},data(){return{strings:{title:r("Get out the word!",F),description:r("Share your content on your favorite social media platforms to drive engagement and increase your SEO.",F)}}},computed:{socialNetworks(){return[{icon:"svg-icon-twitter",link:"https://x.com/share?url="},{icon:"svg-facebook-rounded",link:"https://www.facebook.com/sharer/sharer.php?u="},{icon:"svg-pinterest-rounded",link:"https://pinterest.com/pin/create/button/?url="},{icon:"svg-linkedin-rounded",link:"https://www.linkedin.com/shareArticle?url="}].map(e=>({...e,link:e.link+this.tagsStore.liveTags.permalink}))}}},Jt={key:0,class:"aioseo-post-publish"},Yt={class:"title"},Qt={class:"description"},Xt={class:"links"},eo=["href"];function to(e,t,o,s,a,p){return s.tagsStore.liveTags.permalink?(c(),l("div",Jt,[n("h2",Yt,m(a.strings.title),1),n("p",Qt,m(a.strings.description),1),n("div",Xt,[(c(!0),l(Z,null,G(p.socialNetworks,(d,g)=>(c(),l("a",{class:"link",target:"_blank",key:g,href:d.link},[(c(),K(j(d.icon)))],8,eo))),128))])])):f("",!0)}const oo=_(qt,[["render",to]]);(function(e){var g,v,u,S,y,w,x,E,k,A,C,T,R,O,I,M;if(!_e()||!Me()||!((v=(g=window.wp)==null?void 0:g.editor)!=null&&v.PluginDocumentSettingPanel)&&!((S=(u=window.wp)==null?void 0:u.editPost)!=null&&S.PluginDocumentSettingPanel))return;const t=((w=(y=window==null?void 0:window.wp)==null?void 0:y.editor)==null?void 0:w.PluginDocumentSettingPanel)||((E=(x=window.wp)==null?void 0:x.editPost)==null?void 0:E.PluginDocumentSettingPanel),o=((A=(k=window==null?void 0:window.wp)==null?void 0:k.editor)==null?void 0:A.PluginPrePublishPanel)||((T=(C=window.wp)==null?void 0:C.editPost)==null?void 0:T.PluginPrePublishPanel),s=((O=(R=window==null?void 0:window.wp)==null?void 0:R.editor)==null?void 0:O.PluginPostPublishPanel)||((M=(I=window.wp)==null?void 0:I.editPost)==null?void 0:M.PluginPostPublishPanel),a=e.plugins.registerPlugin;q();const d=ve().aioseo.user;!d.capabilities.aioseo_page_analysis&&!d.capabilities.aioseo_page_general_settings&&!d.capabilities.aioseo_page_advanced_settings||a("aioseo-publish-panel",{render:()=>e.element.createElement(e.element.Fragment,{},e.element.createElement(t,{title:"AIOSEO",className:"aioseo-document-setting aioseo-seo-overview",icon:e.element.Fragment},e.element.createElement("div",{},e.element.createElement("div",{id:"aioseo-document-setting"}))),e.element.createElement(o,{title:["AIOSEO",":",e.element.createElement("span",{key:"scoreDescription",className:"editor-post-publish-panel__link"})],className:"aioseo-pre-publish aioseo-seo-overview",initialOpen:!0,icon:e.element.Fragment},e.element.createElement("div",{},e.element.createElement("div",{id:"aioseo-pre-publish"}))),e.element.createElement(s,{title:"AIOSEO",initialOpen:!0,icon:e.element.Fragment},e.element.createElement("div",{},e.element.createElement("div",{id:"aioseo-post-publish"}))))})})(window.wp);const W=e=>{let t=ue({...e.component,name:"Standalone/PublishPanel"});return t=de(t),t=pe(t),t=me(t),q(t),t.mount("#"+e.id),window.addEventListener("load",()=>{Ue(t,!1)}),t};window.aioseo.currentPost&&window.aioseo.currentPost.context==="post"&&[{id:"aioseo-pre-publish",component:N},{id:"aioseo-document-setting",component:N},{id:"aioseo-post-publish",component:oo}].forEach(t=>{document.getElementById(t.id)?W(t):(Le("#"+t.id,B(t.id)),document.addEventListener("animationstart",function(o){B(t.id)===o.animationName&&W(t)},{passive:!0}))});
