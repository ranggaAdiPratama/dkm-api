(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["default-list"],{"3ad0":function(t,e,s){},"7c36":function(t,e,s){"use strict";s.r(e);var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("v-list",t._g(t._b({attrs:{expand:"",nav:""}},"v-list",t.$attrs,!1),t.$listeners),[t._l(t.items,(function(t,e){return[t.items?s("default-list-group",{key:"group-"+e,attrs:{item:t}}):s("default-list-item",{key:"item-"+e,attrs:{item:t}})]}))],2)},n=[],l=(s("d3b7"),{name:"DefaultList",components:{DefaultListGroup:function(){return s.e("chunk-1d5a56d6").then(s.bind(null,"47e8"))},DefaultListItem:function(){return s.e("chunk-71ebb521").then(s.bind(null,"d4cc"))}},props:{items:{type:Array,default:function(){return[]}}}}),a=l,o=s("2877"),r=s("6544"),u=s.n(r),d=s("8860"),h=Object(o["a"])(a,i,n,!1,null,null,null);e["default"]=h.exports;u()(h,{VList:d["a"]})},8860:function(t,e,s){"use strict";s("3ad0");var i=s("8dd9");e["a"]=i["a"].extend().extend({name:"v-list",provide(){return{isInList:!0,list:this}},inject:{isInMenu:{default:!1},isInNav:{default:!1}},props:{dense:Boolean,disabled:Boolean,expand:Boolean,flat:Boolean,nav:Boolean,rounded:Boolean,subheader:Boolean,threeLine:Boolean,twoLine:Boolean},data:()=>({groups:[]}),computed:{classes(){return{...i["a"].options.computed.classes.call(this),"v-list--dense":this.dense,"v-list--disabled":this.disabled,"v-list--flat":this.flat,"v-list--nav":this.nav,"v-list--rounded":this.rounded,"v-list--subheader":this.subheader,"v-list--two-line":this.twoLine,"v-list--three-line":this.threeLine}}},methods:{register(t){this.groups.push(t)},unregister(t){const e=this.groups.findIndex(e=>e._uid===t._uid);e>-1&&this.groups.splice(e,1)},listClick(t){if(!this.expand)for(const e of this.groups)e.toggle(t)}},render(t){const e={staticClass:"v-list",class:this.classes,style:this.styles,attrs:{role:this.isInNav||this.isInMenu?void 0:"list",...this.attrs$}};return t(this.tag,this.setBackgroundColor(this.color,e),[this.$slots.default])}})}}]);
//# sourceMappingURL=default-list.7f3ed361.js.map