/*! For license information please see menus.js.LICENSE.txt */
!function(){"use strict";var e={react:function(e){e.exports=window.React},"@elementor/locations":function(e){e.exports=window.elementorV2.locations}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,n),i.exports}n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var r={};!function(){n.r(r),n.d(r,{createMenu:function(){return i}});var e=n("@elementor/locations"),t=n("react");function o(e,n){return({id:r,group:o="default",priority:i=10,overwrite:u=!1,props:c,useProps:s})=>{if(!(o in e))return;const a=n,p=s||(()=>c);e[o].inject({id:r,component:()=>{const e=p();return t.createElement(a,{...e})},options:{priority:i,overwrite:u}})}}function i({groups:n=[],components:r}){const i=function(t){return t.reduce(((t,n)=>(t[n]=(0,e.createLocation)(),t)),{})}([...n,"default"]),u=function(e,t){return Object.entries(t).reduce(((t,[n,r])=>{var i;return{...t,[`register${i=n,i.charAt(0).toUpperCase()+i.slice(1)}`]:o(e,r)}}),{})}(i,r),c=function(e){return()=>(0,t.useMemo)((()=>Object.entries(e).reduce(((e,[t,n])=>({...e,[t]:n.getInjections().map((e=>({id:e.id,MenuItem:e.component})))})),{})),[])}(i);return{useMenuItems:c,...u}}}(),(window.elementorV2=window.elementorV2||{}).menus=r}();