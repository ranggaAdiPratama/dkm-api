(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["views-Orders-Express-DetailOrderDialog"],{"0503":function(t,e,i){"use strict";i.r(e);var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-dialog",{attrs:{persistent:"",width:"1000"},model:{value:this.$store.state.dialog,callback:function(e){t.$set(this.$store.state,"dialog",e)},expression:"this.$store.state.dialog"}},[i("v-card",[i("v-card-title",{staticClass:"primary",staticStyle:{color:"white"}},[t._v(" Detail Order ")]),i("v-card-text",{staticClass:"mt-6",staticStyle:{color:"black"}},[i("v-row",[i("v-col",[i("div",{staticClass:"text-h4"},[t._v(" From ")]),i("table",[i("tr",[i("td",[t._v("Client Name")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.name))])]),i("tr",[i("td",[t._v("Phone")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.sender_phone))])]),i("tr",[i("td",[t._v("District")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.sender_district))])]),i("tr",[i("td",[t._v("Village")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.sender_village))])]),i("tr",[i("td",[t._v("Address")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.sender_address))])])])]),i("v-col",[i("div",{staticClass:"text-h4"},[t._v(" Deliver to ")]),i("table",[i("tr",[i("td",[t._v("Receiver Name")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.receiver_name))])]),i("tr",[i("td",[t._v("Phone")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.receiver_phone))])]),i("tr",[i("td",[t._v("District")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.receiver_district))])]),i("tr",[i("td",[t._v("Address")]),i("td",[t._v(":")]),i("td",[t._v(t._s(t.data.receiver_address))])])])]),i("v-col",[i("div",{staticClass:"text-h4"},[t._v(" Pick Up Driver ")]),t._v(" Name : "+t._s(t.data.driver_name)+" ")])],1),i("div",{staticClass:"text-h4 mt-6"},[t._v(" Product ")]),i("v-simple-table",{scopedSlots:t._u([{key:"default",fn:function(){return[i("thead",[i("tr",[i("th",{staticClass:"text-left"},[t._v(" Name ")]),i("th",{staticClass:"text-left"},[t._v(" Weight (Kg) ")]),i("th",{staticClass:"text-left"},[t._v(" Volume (Cm3) ")]),i("th",{staticClass:"text-left"},[t._v(" Price (Rp) ")]),i("th",{staticClass:"text-left"},[t._v(" Delivery Fee (Rp) ")]),i("th",{staticClass:"text-left"},[t._v(" Payment Method ")])])]),i("tbody",[i("tr",[i("td",[t._v(t._s(t.data.product_name))]),i("td",[t._v(t._s(t.data.weight))]),i("td",[t._v(t._s(t.data.volume))]),i("td",[t._v(" "+t._s(t.formatThousands(t.data.price,{separator:","}))+" ")]),i("td",[t._v(" "+t._s(t.formatThousands(t.data.delivery_fee,{separator:","}))+" ")]),i("td",[t._v(t._s(t.data.method))])])])]},proxy:!0}])})],1),i("v-divider"),i("v-card-actions",[i("v-spacer"),i("v-btn",{attrs:{color:"primary",text:""},on:{click:function(e){return t.$store.commit("dialog",!1)}}},[t._v(" Close ")])],1)],1)],1)},a=[],n=i("033f"),o={props:["data"],data:function(){return{formatThousands:n,dialog:!1}}},r=o,l=i("2877"),c=i("6544"),d=i.n(c),h=i("8336"),v=i("b0af"),u=i("99d9"),m=i("62ad"),p=i("169a"),f=i("ce7e"),y=i("0fd9"),g=i("1f4f"),b=i("2fa4"),_=Object(l["a"])(r,s,a,!1,null,null,null);e["default"]=_.exports;d()(_,{VBtn:h["a"],VCard:v["a"],VCardActions:u["a"],VCardText:u["b"],VCardTitle:u["c"],VCol:m["a"],VDialog:p["a"],VDivider:f["a"],VRow:y["a"],VSimpleTable:g["a"],VSpacer:b["a"]})},"169a":function(t,e,i){"use strict";i("368e");var s=i("480e"),a=i("4ad4b"),n=i("b848"),o=i("75eb"),r=i("e707"),l=i("e4d3"),c=i("21be"),d=i("f2e7"),h=i("a293"),v=i("58df"),u=i("d9bd"),m=i("80d2");const p=Object(v["a"])(a["a"],n["a"],o["a"],r["a"],l["a"],c["a"],d["a"]);e["a"]=p.extend({name:"v-dialog",directives:{ClickOutside:h["a"]},props:{dark:Boolean,disabled:Boolean,fullscreen:Boolean,light:Boolean,maxWidth:{type:[String,Number],default:"none"},noClickAnimation:Boolean,origin:{type:String,default:"center center"},persistent:Boolean,retainFocus:{type:Boolean,default:!0},scrollable:Boolean,transition:{type:[String,Boolean],default:"dialog-transition"},width:{type:[String,Number],default:"auto"}},data(){return{activatedBy:null,animate:!1,animateTimeout:-1,isActive:!!this.value,stackMinZIndex:200,previousActiveElement:null}},computed:{classes(){return{[("v-dialog "+this.contentClass).trim()]:!0,"v-dialog--active":this.isActive,"v-dialog--persistent":this.persistent,"v-dialog--fullscreen":this.fullscreen,"v-dialog--scrollable":this.scrollable,"v-dialog--animated":this.animate}},contentClasses(){return{"v-dialog__content":!0,"v-dialog__content--active":this.isActive}},hasActivator(){return Boolean(!!this.$slots.activator||!!this.$scopedSlots.activator)}},watch:{isActive(t){var e;t?(this.show(),this.hideScroll()):(this.removeOverlay(),this.unbind(),null==(e=this.previousActiveElement)||e.focus())},fullscreen(t){this.isActive&&(t?(this.hideScroll(),this.removeOverlay(!1)):(this.showScroll(),this.genOverlay()))}},created(){this.$attrs.hasOwnProperty("full-width")&&Object(u["e"])("full-width",this)},beforeMount(){this.$nextTick(()=>{this.isBooted=this.isActive,this.isActive&&this.show()})},beforeDestroy(){"undefined"!==typeof window&&this.unbind()},methods:{animateClick(){this.animate=!1,this.$nextTick(()=>{this.animate=!0,window.clearTimeout(this.animateTimeout),this.animateTimeout=window.setTimeout(()=>this.animate=!1,150)})},closeConditional(t){const e=t.target;return!(this._isDestroyed||!this.isActive||this.$refs.content.contains(e)||this.overlay&&e&&!this.overlay.$el.contains(e))&&this.activeZIndex>=this.getMaxZIndex()},hideScroll(){this.fullscreen?document.documentElement.classList.add("overflow-y-hidden"):r["a"].options.methods.hideScroll.call(this)},show(){!this.fullscreen&&!this.hideOverlay&&this.genOverlay(),this.$nextTick(()=>{this.$nextTick(()=>{this.previousActiveElement=document.activeElement,this.$refs.content.focus(),this.bind()})})},bind(){window.addEventListener("focusin",this.onFocusin)},unbind(){window.removeEventListener("focusin",this.onFocusin)},onClickOutside(t){this.$emit("click:outside",t),this.persistent?this.noClickAnimation||this.animateClick():this.isActive=!1},onKeydown(t){if(t.keyCode===m["y"].esc&&!this.getOpenDependents().length)if(this.persistent)this.noClickAnimation||this.animateClick();else{this.isActive=!1;const t=this.getActivator();this.$nextTick(()=>t&&t.focus())}this.$emit("keydown",t)},onFocusin(t){if(!t||!this.retainFocus)return;const e=t.target;if(e&&![document,this.$refs.content].includes(e)&&!this.$refs.content.contains(e)&&this.activeZIndex>=this.getMaxZIndex()&&!this.getOpenDependentElements().some(t=>t.contains(e))){const t=this.$refs.content.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'),e=[...t].find(t=>!t.hasAttribute("disabled"));e&&e.focus()}},genContent(){return this.showLazyContent(()=>[this.$createElement(s["a"],{props:{root:!0,light:this.light,dark:this.dark}},[this.$createElement("div",{class:this.contentClasses,attrs:{role:"document",tabindex:this.isActive?0:void 0,...this.getScopeIdAttrs()},on:{keydown:this.onKeydown},style:{zIndex:this.activeZIndex},ref:"content"},[this.genTransition()])])])},genTransition(){const t=this.genInnerContent();return this.transition?this.$createElement("transition",{props:{name:this.transition,origin:this.origin,appear:!0}},[t]):t},genInnerContent(){const t={class:this.classes,ref:"dialog",directives:[{name:"click-outside",value:{handler:this.onClickOutside,closeConditional:this.closeConditional,include:this.getOpenDependentElements}},{name:"show",value:this.isActive}],style:{transformOrigin:this.origin}};return this.fullscreen||(t.style={...t.style,maxWidth:"none"===this.maxWidth?void 0:Object(m["g"])(this.maxWidth),width:"auto"===this.width?void 0:Object(m["g"])(this.width)}),this.$createElement("div",t,this.getContentSlot())}},render(t){return t("div",{staticClass:"v-dialog__container",class:{"v-dialog__container--attached":""===this.attach||!0===this.attach||"attach"===this.attach},attrs:{role:"dialog"}},[this.genActivator(),this.genContent()])}})},"1f4f":function(t,e,i){"use strict";i("8b37");var s=i("80d2"),a=i("7560"),n=i("58df");e["a"]=Object(n["a"])(a["a"]).extend({name:"v-simple-table",props:{dense:Boolean,fixedHeader:Boolean,height:[Number,String]},computed:{classes(){return{"v-data-table--dense":this.dense,"v-data-table--fixed-height":!!this.height&&!this.fixedHeader,"v-data-table--fixed-header":this.fixedHeader,"v-data-table--has-top":!!this.$slots.top,"v-data-table--has-bottom":!!this.$slots.bottom,...this.themeClasses}}},methods:{genWrapper(){return this.$slots.wrapper||this.$createElement("div",{staticClass:"v-data-table__wrapper",style:{height:Object(s["g"])(this.height)}},[this.$createElement("table",this.$slots.default)])}},render(t){return t("div",{staticClass:"v-data-table",class:this.classes},[this.$slots.top,this.genWrapper(),this.$slots.bottom])}})},"21be":function(t,e,i){"use strict";var s=i("2b0e"),a=i("80d2");e["a"]=s["default"].extend().extend({name:"stackable",data(){return{stackElement:null,stackExclude:null,stackMinZIndex:0,isActive:!1}},computed:{activeZIndex(){if("undefined"===typeof window)return 0;const t=this.stackElement||this.$refs.content,e=this.isActive?this.getMaxZIndex(this.stackExclude||[t])+2:Object(a["u"])(t);return null==e?e:parseInt(e)}},methods:{getMaxZIndex(t=[]){const e=this.$el,i=[this.stackMinZIndex,Object(a["u"])(e)],s=[...document.getElementsByClassName("v-menu__content--active"),...document.getElementsByClassName("v-dialog__content--active")];for(let n=0;n<s.length;n++)t.includes(s[n])||i.push(Object(a["u"])(s[n]));return Math.max(...i)}}})},"368e":function(t,e,i){},"3c93":function(t,e,i){},"4ad4b":function(t,e,i){"use strict";var s=i("16b7"),a=i("f2e7"),n=i("58df"),o=i("80d2"),r=i("d9bd");const l=Object(n["a"])(s["a"],a["a"]);e["a"]=l.extend({name:"activatable",props:{activator:{default:null,validator:t=>["string","object"].includes(typeof t)},disabled:Boolean,internalActivator:Boolean,openOnHover:Boolean,openOnFocus:Boolean},data:()=>({activatorElement:null,activatorNode:[],events:["click","mouseenter","mouseleave","focus"],listeners:{}}),watch:{activator:"resetActivator",openOnFocus:"resetActivator",openOnHover:"resetActivator"},mounted(){const t=Object(o["t"])(this,"activator",!0);t&&["v-slot","normal"].includes(t)&&Object(r["b"])('The activator slot must be bound, try \'<template v-slot:activator="{ on }"><v-btn v-on="on">\'',this),this.addActivatorEvents()},beforeDestroy(){this.removeActivatorEvents()},methods:{addActivatorEvents(){if(!this.activator||this.disabled||!this.getActivator())return;this.listeners=this.genActivatorListeners();const t=Object.keys(this.listeners);for(const e of t)this.getActivator().addEventListener(e,this.listeners[e])},genActivator(){const t=Object(o["s"])(this,"activator",Object.assign(this.getValueProxy(),{on:this.genActivatorListeners(),attrs:this.genActivatorAttributes()}))||[];return this.activatorNode=t,t},genActivatorAttributes(){return{role:"button","aria-haspopup":!0,"aria-expanded":String(this.isActive)}},genActivatorListeners(){if(this.disabled)return{};const t={};return this.openOnHover?(t.mouseenter=t=>{this.getActivator(t),this.runDelay("open")},t.mouseleave=t=>{this.getActivator(t),this.runDelay("close")}):t.click=t=>{const e=this.getActivator(t);e&&e.focus(),t.stopPropagation(),this.isActive=!this.isActive},this.openOnFocus&&(t.focus=t=>{this.getActivator(t),t.stopPropagation(),this.isActive=!this.isActive}),t},getActivator(t){if(this.activatorElement)return this.activatorElement;let e=null;if(this.activator){const t=this.internalActivator?this.$el:document;e="string"===typeof this.activator?t.querySelector(this.activator):this.activator.$el?this.activator.$el:this.activator}else if(1===this.activatorNode.length||this.activatorNode.length&&!t){const t=this.activatorNode[0].componentInstance;e=t&&t.$options.mixins&&t.$options.mixins.some(t=>t.options&&["activatable","menuable"].includes(t.options.name))?t.getActivator():this.activatorNode[0].elm}else t&&(e=t.currentTarget||t.target);return this.activatorElement=e,this.activatorElement},getContentSlot(){return Object(o["s"])(this,"default",this.getValueProxy(),!0)},getValueProxy(){const t=this;return{get value(){return t.isActive},set value(e){t.isActive=e}}},removeActivatorEvents(){if(!this.activator||!this.activatorElement)return;const t=Object.keys(this.listeners);for(const e of t)this.activatorElement.removeEventListener(e,this.listeners[e]);this.listeners={}},resetActivator(){this.removeActivatorEvents(),this.activatorElement=null,this.getActivator(),this.addActivatorEvents()}}})},"75eb":function(t,e,i){"use strict";var s=i("9d65"),a=i("80d2"),n=i("58df"),o=i("d9bd");function r(t){const e=typeof t;return"boolean"===e||"string"===e||t.nodeType===Node.ELEMENT_NODE}e["a"]=Object(n["a"])(s["a"]).extend({name:"detachable",props:{attach:{default:!1,validator:r},contentClass:{type:String,default:""}},data:()=>({activatorNode:null,hasDetached:!1}),watch:{attach(){this.hasDetached=!1,this.initDetach()},hasContent(){this.$nextTick(this.initDetach)}},beforeMount(){this.$nextTick(()=>{if(this.activatorNode){const t=Array.isArray(this.activatorNode)?this.activatorNode:[this.activatorNode];t.forEach(t=>{if(!t.elm)return;if(!this.$el.parentNode)return;const e=this.$el===this.$el.parentNode.firstChild?this.$el:this.$el.nextSibling;this.$el.parentNode.insertBefore(t.elm,e)})}})},mounted(){this.hasContent&&this.initDetach()},deactivated(){this.isActive=!1},beforeDestroy(){try{if(this.$refs.content&&this.$refs.content.parentNode&&this.$refs.content.parentNode.removeChild(this.$refs.content),this.activatorNode){const t=Array.isArray(this.activatorNode)?this.activatorNode:[this.activatorNode];t.forEach(t=>{t.elm&&t.elm.parentNode&&t.elm.parentNode.removeChild(t.elm)})}}catch(t){console.log(t)}},methods:{getScopeIdAttrs(){const t=Object(a["p"])(this.$vnode,"context.$options._scopeId");return t&&{[t]:""}},initDetach(){if(this._isDestroyed||!this.$refs.content||this.hasDetached||""===this.attach||!0===this.attach||"attach"===this.attach)return;let t;t=!1===this.attach?document.querySelector("[data-app]"):"string"===typeof this.attach?document.querySelector(this.attach):this.attach,t?(t.appendChild(this.$refs.content),this.hasDetached=!0):Object(o["c"])("Unable to locate target "+(this.attach||"[data-app]"),this)}}})},"8b37":function(t,e,i){},"9d65":function(t,e,i){"use strict";var s=i("d9bd"),a=i("2b0e");e["a"]=a["default"].extend().extend({name:"bootable",props:{eager:Boolean},data:()=>({isBooted:!1}),computed:{hasContent(){return this.isBooted||this.eager||this.isActive}},watch:{isActive(){this.isBooted=!0}},created(){"lazy"in this.$attrs&&Object(s["e"])("lazy",this)},methods:{showLazyContent(t){return this.hasContent&&t?t():[this.$createElement()]}}})},a293:function(t,e,i){"use strict";function s(){return!0}function a(t,e,i){const a="function"===typeof i.value?i.value:i.value.handler,n="object"===typeof i.value&&i.value.closeConditional||s;if(!t||!1===n(t))return;const o=("object"===typeof i.value&&i.value.include||(()=>[]))();o.push(e),!o.some(e=>e.contains(t.target))&&setTimeout(()=>{n(t)&&a&&a(t)},0)}const n={inserted(t,e){const i=i=>a(i,t,e),s=document.querySelector("[data-app]")||document.body;s.addEventListener("click",i,!0),t._clickOutside=i},unbind(t){if(!t._clickOutside)return;const e=document.querySelector("[data-app]")||document.body;e&&e.removeEventListener("click",t._clickOutside,!0),delete t._clickOutside}};e["a"]=n},b848:function(t,e,i){"use strict";var s=i("58df");function a(t){const e=[];for(let i=0;i<t.length;i++){const s=t[i];s.isActive&&s.isDependent?e.push(s):e.push(...a(s.$children))}return e}e["a"]=Object(s["a"])().extend({name:"dependent",data(){return{closeDependents:!0,isActive:!1,isDependent:!0}},watch:{isActive(t){if(t)return;const e=this.getOpenDependents();for(let i=0;i<e.length;i++)e[i].isActive=!1}},methods:{getOpenDependents(){return this.closeDependents?a(this.$children):[]},getOpenDependentElements(){const t=[],e=this.getOpenDependents();for(let i=0;i<e.length;i++)t.push(...e[i].getClickableDependentElements());return t},getClickableDependentElements(){const t=[this.$el];return this.$refs.content&&t.push(this.$refs.content),this.overlay&&t.push(this.overlay.$el),t.push(...this.getOpenDependentElements()),t}}})},e4d3:function(t,e,i){"use strict";var s=i("2b0e");e["a"]=s["default"].extend({name:"returnable",props:{returnValue:null},data:()=>({isActive:!1,originalValue:null}),watch:{isActive(t){t?this.originalValue=this.returnValue:this.$emit("update:return-value",this.originalValue)}},methods:{save(t){this.originalValue=t,setTimeout(()=>{this.isActive=!1})}}})},e707:function(t,e,i){"use strict";i("3c93");var s=i("a9ad"),a=i("7560"),n=i("f2e7"),o=i("58df"),r=Object(o["a"])(s["a"],a["a"],n["a"]).extend({name:"v-overlay",props:{absolute:Boolean,color:{type:String,default:"#212121"},dark:{type:Boolean,default:!0},opacity:{type:[Number,String],default:.46},value:{default:!0},zIndex:{type:[Number,String],default:5}},computed:{__scrim(){const t=this.setBackgroundColor(this.color,{staticClass:"v-overlay__scrim",style:{opacity:this.computedOpacity}});return this.$createElement("div",t)},classes(){return{"v-overlay--absolute":this.absolute,"v-overlay--active":this.isActive,...this.themeClasses}},computedOpacity(){return Number(this.isActive?this.opacity:0)},styles(){return{zIndex:this.zIndex}}},methods:{genContent(){return this.$createElement("div",{staticClass:"v-overlay__content"},this.$slots.default)}},render(t){const e=[this.__scrim];return this.isActive&&e.push(this.genContent()),t("div",{staticClass:"v-overlay",class:this.classes,style:this.styles},e)}}),l=r,c=i("80d2"),d=i("2b0e");e["a"]=d["default"].extend().extend({name:"overlayable",props:{hideOverlay:Boolean,overlayColor:String,overlayOpacity:[Number,String]},data(){return{animationFrame:0,overlay:null}},watch:{hideOverlay(t){this.isActive&&(t?this.removeOverlay():this.genOverlay())}},beforeDestroy(){this.removeOverlay()},methods:{createOverlay(){const t=new l({propsData:{absolute:this.absolute,value:!1,color:this.overlayColor,opacity:this.overlayOpacity}});t.$mount();const e=this.absolute?this.$el.parentNode:document.querySelector("[data-app]");e&&e.insertBefore(t.$el,e.firstChild),this.overlay=t},genOverlay(){if(this.hideScroll(),!this.hideOverlay)return this.overlay||this.createOverlay(),this.animationFrame=requestAnimationFrame(()=>{this.overlay&&(void 0!==this.activeZIndex?this.overlay.zIndex=String(this.activeZIndex-1):this.$el&&(this.overlay.zIndex=Object(c["u"])(this.$el)),this.overlay.value=!0)}),!0},removeOverlay(t=!0){this.overlay&&(Object(c["a"])(this.overlay.$el,"transitionend",()=>{this.overlay&&this.overlay.$el&&this.overlay.$el.parentNode&&!this.overlay.value&&(this.overlay.$el.parentNode.removeChild(this.overlay.$el),this.overlay.$destroy(),this.overlay=null)}),cancelAnimationFrame(this.animationFrame),this.overlay.value=!1),t&&this.showScroll()},scrollListener(t){if("keydown"===t.type){if(["INPUT","TEXTAREA","SELECT"].includes(t.target.tagName)||t.target.isContentEditable)return;const e=[c["y"].up,c["y"].pageup],i=[c["y"].down,c["y"].pagedown];if(e.includes(t.keyCode))t.deltaY=-1;else{if(!i.includes(t.keyCode))return;t.deltaY=1}}(t.target===this.overlay||"keydown"!==t.type&&t.target===document.body||this.checkPath(t))&&t.preventDefault()},hasScrollbar(t){if(!t||t.nodeType!==Node.ELEMENT_NODE)return!1;const e=window.getComputedStyle(t);return["auto","scroll"].includes(e.overflowY)&&t.scrollHeight>t.clientHeight},shouldScroll(t,e){return 0===t.scrollTop&&e<0||t.scrollTop+t.clientHeight===t.scrollHeight&&e>0},isInside(t,e){return t===e||null!==t&&t!==document.body&&this.isInside(t.parentNode,e)},checkPath(t){const e=t.path||this.composedPath(t),i=t.deltaY;if("keydown"===t.type&&e[0]===document.body){const t=this.$refs.dialog,e=window.getSelection().anchorNode;return!(t&&this.hasScrollbar(t)&&this.isInside(e,t))||this.shouldScroll(t,i)}for(let s=0;s<e.length;s++){const t=e[s];if(t===document)return!0;if(t===document.documentElement)return!0;if(t===this.$refs.content)return!0;if(this.hasScrollbar(t))return this.shouldScroll(t,i)}return!0},composedPath(t){if(t.composedPath)return t.composedPath();const e=[];let i=t.target;while(i){if(e.push(i),"HTML"===i.tagName)return e.push(document),e.push(window),e;i=i.parentElement}return e},hideScroll(){this.$vuetify.breakpoint.smAndDown?document.documentElement.classList.add("overflow-y-hidden"):(Object(c["b"])(window,"wheel",this.scrollListener,{passive:!1}),window.addEventListener("keydown",this.scrollListener))},showScroll(){document.documentElement.classList.remove("overflow-y-hidden"),window.removeEventListener("wheel",this.scrollListener),window.removeEventListener("keydown",this.scrollListener)}}})}}]);
//# sourceMappingURL=views-Orders-Express-DetailOrderDialog.227f169f.js.map