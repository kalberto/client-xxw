(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["novaSenha"],{1831:function(t,e,n){"use strict";var r=n("90dc"),a=n.n(r);a.a},"3a60":function(t,e,n){(function(e,n){t.exports=n()})(0,(function(){return function(t){function e(r){if(n[r])return n[r].exports;var a=n[r]={i:r,l:!1,exports:{}};return t[r].call(a.exports,a,a.exports,e),a.l=!0,a.exports}var n={};return e.m=t,e.c=n,e.i=function(t){return t},e.d=function(t,n,r){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:r})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p=".",e(e.s=10)}([function(t,e){t.exports={"#":{pattern:/\d/},X:{pattern:/[0-9a-zA-Z]/},S:{pattern:/[a-zA-Z]/},A:{pattern:/[a-zA-Z]/,transform:function(t){return t.toLocaleUpperCase()}},a:{pattern:/[a-zA-Z]/,transform:function(t){return t.toLocaleLowerCase()}},"!":{escape:!0}}},function(t,e,n){"use strict";function r(t){var e=document.createEvent("Event");return e.initEvent(t,!0,!0),e}var a=n(2),o=n(0),s=n.n(o);e.a=function(t,e){var o=e.value;if((Array.isArray(o)||"string"==typeof o)&&(o={mask:o,tokens:s.a}),"INPUT"!==t.tagName.toLocaleUpperCase()){var i=t.getElementsByTagName("input");if(1!==i.length)throw new Error("v-mask directive requires 1 input, found "+i.length);t=i[0]}t.oninput=function(e){if(e.isTrusted){var s=t.selectionEnd,i=t.value[s-1];for(t.value=n.i(a.a)(t.value,o.mask,!0,o.tokens);s<t.value.length&&t.value.charAt(s-1)!==i;)s++;t===document.activeElement&&(t.setSelectionRange(s,s),setTimeout((function(){t.setSelectionRange(s,s)}),0)),t.dispatchEvent(r("input"))}};var u=n.i(a.a)(t.value,o.mask,!0,o.tokens);u!==t.value&&(t.value=u,t.dispatchEvent(r("input")))}},function(t,e,n){"use strict";var r=n(6),a=n(5);e.a=function(t,e){var o=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],s=arguments[3];return Array.isArray(e)?n.i(a.a)(r.a,e,s)(t,e,o,s):n.i(r.a)(t,e,o,s)}},function(t,e,n){"use strict";function r(t){t.component(u.a.name,u.a),t.directive("mask",s.a)}Object.defineProperty(e,"__esModule",{value:!0});var a=n(0),o=n.n(a),s=n(1),i=n(7),u=n.n(i);n.d(e,"TheMask",(function(){return u.a})),n.d(e,"mask",(function(){return s.a})),n.d(e,"tokens",(function(){return o.a})),n.d(e,"version",(function(){return c}));var c="0.11.1";e.default=r,"undefined"!=typeof window&&window.Vue&&window.Vue.use(r)},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=n(1),a=n(0),o=n.n(a),s=n(2);e.default={name:"TheMask",props:{value:[String,Number],mask:{type:[String,Array],required:!0},masked:{type:Boolean,default:!1},tokens:{type:Object,default:function(){return o.a}}},directives:{mask:r.a},data:function(){return{lastValue:null,display:this.value}},watch:{value:function(t){t!==this.lastValue&&(this.display=t)},masked:function(){this.refresh(this.display)}},computed:{config:function(){return{mask:this.mask,tokens:this.tokens,masked:this.masked}}},methods:{onInput:function(t){t.isTrusted||this.refresh(t.target.value)},refresh:function(t){this.display=t;t=n.i(s.a)(t,this.mask,this.masked,this.tokens);t!==this.lastValue&&(this.lastValue=t,this.$emit("input",t))}}}},function(t,e,n){"use strict";function r(t,e,n){return e=e.sort((function(t,e){return t.length-e.length})),function(r,a){for(var o=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],s=0;s<e.length;){var i=e[s];s++;var u=e[s];if(!(u&&t(r,u,!0,n).length>i.length))return t(r,i,o,n)}return""}}e.a=r},function(t,e,n){"use strict";function r(t,e){var n=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],r=arguments[3];t=t||"",e=e||"";for(var a=0,o=0,s="";a<e.length&&o<t.length;){var i=e[a],u=r[i],c=t[o];u&&!u.escape?(u.pattern.test(c)&&(s+=u.transform?u.transform(c):c,a++),o++):(u&&u.escape&&(a++,i=e[a]),n&&(s+=i),c===i&&o++,a++)}for(var l="";a<e.length&&n;){i=e[a];if(r[i]){l="";break}l+=i,a++}return s+l}e.a=r},function(t,e,n){var r=n(8)(n(4),n(9),null,null);t.exports=r.exports},function(t,e){t.exports=function(t,e,n,r){var a,o=t=t||{},s=typeof t.default;"object"!==s&&"function"!==s||(a=t,o=t.default);var i="function"==typeof o?o.options:o;if(e&&(i.render=e.render,i.staticRenderFns=e.staticRenderFns),n&&(i._scopeId=n),r){var u=i.computed||(i.computed={});Object.keys(r).forEach((function(t){var e=r[t];u[t]=function(){return e}}))}return{esModule:a,exports:o,options:i}}},function(t,e){t.exports={render:function(){var t=this,e=t.$createElement;return(t._self._c||e)("input",{directives:[{name:"mask",rawName:"v-mask",value:t.config,expression:"config"}],attrs:{type:"text"},domProps:{value:t.display},on:{input:t.onInput}})},staticRenderFns:[]}},function(t,e,n){t.exports=n(3)}])}))},"3e54":function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("article",{attrs:{id:"novaSenha"}},[n("SvgIcon",{attrs:{data:t.LogoS,original:""}}),n("form",{on:{submit:function(e){return e.preventDefault(),t.send(e)}}},[n("p",[t._v("Preencha os dados abaixo:")]),n("div",{staticClass:"input"},[n("input",{directives:[{name:"model",rawName:"v-model",value:t.formulario.password,expression:"formulario.password"}],class:[t.errors.password?"has-error":""],attrs:{name:"input_password",id:"input_password",placeholder:"Nova Senha",type:"password",autocomplete:"off"},domProps:{value:t.formulario.password},on:{input:function(e){e.target.composing||t.$set(t.formulario,"password",e.target.value)}}}),t.errors.password?n("span",{staticClass:"error"},[t._v(t._s(t.errors.password[0]))]):t._e()]),n("div",{staticClass:"input"},[n("input",{directives:[{name:"model",rawName:"v-model",value:t.formulario.re_password,expression:"formulario.re_password"}],class:[t.errors.re_password?"has-error":""],attrs:{name:"input_re_password",id:"input_re_password",placeholder:"Confirme sua senha",type:"password",autocomplete:"off"},domProps:{value:t.formulario.re_password},on:{input:function(e){e.target.composing||t.$set(t.formulario,"re_password",e.target.value)}}}),t.errors.re_password?n("span",{staticClass:"error"},[t._v(t._s(t.errors.re_password[0]))]):t._e()]),n("div",{staticClass:"input"},[n("input",{directives:[{name:"model",rawName:"v-model",value:t.formulario.token,expression:"formulario.token"}],staticStyle:{display:"none"},attrs:{name:"input_token",id:"input_token",placeholder:"Token",type:"token"},domProps:{value:t.formulario.token},on:{input:function(e){e.target.composing||t.$set(t.formulario,"token",e.target.value)}}})]),n("div",{staticClass:"button"},[n("button",{attrs:{type:"submit",disabled:t.disabled},domProps:{innerHTML:t._s(t.button)}})])])],1)},a=[],o=n("96c9"),s=n.n(o),i=n("3a60"),u={name:"view-nova-senha",directives:{mask:i["mask"]},data:function(){return{LogoS:s.a,errors:{},button:"Nova Senha",disabled:!1,formulario:{token:"",password:"",re_password:""}}},created:function(){this.$route.query.token&&(this.formulario.token=this.$route.query.token)},methods:{send:function(){var t=this;this.disabled=!0,this.button="Enviando...";var e=Object.assign({},this.formulario);this.$auth.reset(e).then((function(){t.button="Sucesso",t.disabled=!0,setTimeout((function(){t.$router.push({name:"login"})}),3e3)})).catch((function(e){422==e.status&&(t.errors=e.data.errors),setTimeout((function(){t.button="Nova Senha",t.disabled=!1}),3e3)}))}}},c=u,l=(n("1831"),n("82f4"),n("2877")),d=Object(l["a"])(c,r,a,!1,null,"ff63011a",null);e["default"]=d.exports},"82f4":function(t,e,n){"use strict";var r=n("84f6"),a=n.n(r);a.a},"84f6":function(t,e,n){},"90dc":function(t,e,n){},"96c9":function(t,e){const n={name:"logo_s",data:{viewBox:"0 0 554.51 989.92",data:'<defs><linearGradient id="svgiconid_logo_s_a" x1="554.51" y1="494.96" x2="0" y2="494.96" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#ceaf8b"/><stop offset="1" stop-color="#b4874e"/></linearGradient></defs><g data-name="Camada 2"><path pid="0" d="M395.14 221.18C369 182.66 326.8 156.56 283.3 156.56c-22.36 0-43.49 5-62.12 16.16s-32.31 24.85-41 43.49c-8.7 17.39-11.18 36-8.7 55.91 3.73 19.88 13.67 37.28 27.34 53.43 14.91 16.16 28.58 22.37 67.1 46 29.82 18.64 60.89 34.79 94.43 54.67s64.62 41 98.17 70.83c16.15 14.91 32.3 31.06 47.21 50.94 13.67 21.13 26.1 43.49 34.8 67.1 8.69 24.85 12.42 49.71 13.66 74.56 1.25 23.6-1.24 47.21-5 68.34-6.21 48.46-26.09 95.67-59.64 134.19a276.05 276.05 0 01-123 82 278.87 278.87 0 01-142.9 11.19c-46-8.7-88.22-24.85-126.74-49.7-36-23.61-68.34-50.95-96.92-84.5l33.55-28.58a407.72 407.72 0 0087 74.56c33.55 21.12 70.82 37.27 110.59 43.48s82 5 121.77-8.69c41-13.67 75.8-36 101.89-68.34 28.58-33.55 44.73-72.07 50.94-111.84 6.22-38.52 6.22-82-7.45-121.77-13.67-41-41-74.55-69.58-100.65-29.83-26.09-60.89-46-91.95-64.61s-62.13-34.79-93.2-53.43l-23.61-13.67a244.73 244.73 0 01-53.43-41c-22.36-23.61-34.79-48.46-38.52-77-5-28.58-1.24-55.92 12.43-83.25 13.67-28.58 32.31-48.47 58.4-62.13a165.27 165.27 0 0184.49-22.37c58.41 0 121.78 34.79 149.11 85.74zm94.43-59.65c-6.21-9.94-12.42-16.15-18.64-24.85l-21.12-22.36c-16.15-14.91-32.31-27.34-48.46-36a220.58 220.58 0 00-118-33.55 247.6 247.6 0 00-60.88 7.46c-19.88 5-39.76 13.67-58.4 23.61C126.74 96.92 98.16 128 79.52 166.5s-24.85 80.77-18.64 124.26c7.46 43.49 26.1 79.52 55.92 110.59 14.91 16.15 29.82 29.82 46 39.76 44.73 29.82 93.19 52.19 139.16 80.77 58.4 34.79 114.32 75.8 133 130.47 8.7 26.09 9.94 54.67 5 88.22a161.36 161.36 0 01-36 79.53c-18.64 22.36-42.24 38.52-72.07 48.46-29.82 8.69-59.64 11.18-89.46 6.21-59.77-9.94-114.43-48.46-157.94-96.92L118 748c38.52 43.49 83.26 73.31 131.72 82s93.19-5 119.28-38.52c13.67-16.16 22.37-36 26.1-58.4 5-26.1 3.72-48.46-2.49-67.1-6.21-17.4-18.64-34.8-38.52-53.43q-31.68-28-74.55-52.19c-69.59-41-142.9-70.83-196.33-129.23C49.7 396.38 26.09 350.4 17.4 297c-8.7-51-1.25-104.4 22.36-150.38C63.37 99.41 96.92 63.37 141.65 37.28A274.46 274.46 0 01211.24 8.7c23.61-5 47.21-8.7 72.06-8.7 49.7 0 99.41 14.91 140.41 39.76C444.84 51 462.23 65.86 480.87 82c22.37 21.12 37.28 38.52 46 54.67z" _fill="url(#svgiconid_logo_s_a)" data-name="Layer 1"/></g>'}};t.exports=n}}]);
//# sourceMappingURL=novaSenha.78642e55.js.map