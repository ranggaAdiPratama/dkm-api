(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["views-Orders-Reguler-NewOrderView"],{"21b8":function(e,t,r){"use strict";r.r(t);var l=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("material-card",{attrs:{color:"primary",icon:"mdi-cart-plus",flat:""},scopedSlots:e._u([{key:"title",fn:function(){return[e._v(" Create New Order ")]},proxy:!0}])},[r("v-form",[r("v-container",{staticClass:"py-0"},[r("v-row",[r("v-col",{attrs:{cols:"12",md:"3"}},[r("v-select",{attrs:{items:e.data,"item-text":"name","item-value":"id",label:"Select User",outlined:"",clearable:""},on:{change:function(t){return e.pickupFee()}},model:{value:e.form.selectedUser,callback:function(t){e.$set(e.form,"selectedUser",t)},expression:"form.selectedUser"}}),r("v-text-field",{attrs:{label:"Receiver Name",outlined:"",clearable:""},model:{value:e.form.receiverName,callback:function(t){e.$set(e.form,"receiverName",t)},expression:"form.receiverName"}}),r("v-text-field",{attrs:{label:"Receiver Phone",outlined:"",clearable:""},model:{value:e.form.receiverPhone,callback:function(t){e.$set(e.form,"receiverPhone",t)},expression:"form.receiverPhone"}}),r("v-text-field",{attrs:{label:"Receiver Phone 2",outlined:"",clearable:""},model:{value:e.form.receiverPhone2,callback:function(t){e.$set(e.form,"receiverPhone2",t)},expression:"form.receiverPhone2"}}),r("v-select",{attrs:{items:e.city,"item-text":"nama","item-value":"city_id",label:"Select City",outlined:"","persistent-hint":""},on:{change:function(t){return e.filterDistrict()}},model:{value:e.form.selectedCity,callback:function(t){e.$set(e.form,"selectedCity",t)},expression:"form.selectedCity"}}),r("v-select",{attrs:{items:e.filteredDistrict,"item-text":"nama","item-value":"id",label:"Select District",disabled:null===e.form.selectedCity,outlined:"","persistent-hint":"",clearable:""},on:{change:function(t){return e.filterVillage()}},model:{value:e.form.selectedDistrict,callback:function(t){e.$set(e.form,"selectedDistrict",t)},expression:"form.selectedDistrict"}})],1),r("v-col",{attrs:{cols:"12",md:"3"}},[null!==e.form.selectedDistrict?r("v-select",{attrs:{items:e.filteredVillage,"item-text":"nama","item-value":"id",label:"Select Village",outlined:"","persistent-hint":"",clearable:""},on:{change:function(t){return e.deliveryFee()}},model:{value:e.form.selectedVillage,callback:function(t){e.$set(e.form,"selectedVillage",t)},expression:"form.selectedVillage"}}):r("v-select",{attrs:{items:e.filteredVillage,"item-text":"nama","item-value":"id",label:"Select Village",disabled:"",outlined:"","persistent-hint":""},on:{change:function(t){return e.deliveryFee()}},model:{value:e.form.selectedVillage,callback:function(t){e.$set(e.form,"selectedVillage",t)},expression:"form.selectedVillage"}}),r("v-textarea",{attrs:{label:"Receiver Address",rows:"5",outlined:"",clearable:""},model:{value:e.form.receiverAddress,callback:function(t){e.$set(e.form,"receiverAddress",t)},expression:"form.receiverAddress"}}),r("v-select",{attrs:{items:e.methods,"item-text":"method","item-value":"id",label:"Select Payment Method",outlined:"",clearable:""},model:{value:e.form.selectedMethod,callback:function(t){e.$set(e.form,"selectedMethod",t)},expression:"form.selectedMethod"}}),r("v-text-field",{attrs:{label:"Product Name",outlined:"",clearable:""},model:{value:e.form.productName,callback:function(t){e.$set(e.form,"productName",t)},expression:"form.productName"}}),r("v-text-field",{attrs:{label:"Weight",type:"number",outlined:"",clearable:""},on:{input:e.countFee},model:{value:e.form.weight,callback:function(t){e.$set(e.form,"weight",t)},expression:"form.weight"}})],1),r("v-col",{attrs:{cols:"12",md:"3"}},[r("v-text-field",{attrs:{label:"Volume",type:"number",outlined:"",clearable:""},model:{value:e.form.volume,callback:function(t){e.$set(e.form,"volume",t)},expression:"form.volume"}}),r("vuetify-money",{attrs:{label:"Delivery Fee",readonly:"",outlined:e.outlined,options:e.options},model:{value:e.form.delivery_fee,callback:function(t){e.$set(e.form,"delivery_fee",t)},expression:"form.delivery_fee"}}),r("vuetify-money",{attrs:{label:e.label,readonly:e.readonly,disabled:e.disabled,outlined:e.outlined,clearable:e.clearable,options:e.options},model:{value:e.form.price,callback:function(t){e.$set(e.form,"price",t)},expression:"form.price"}}),r("v-textarea",{attrs:{label:"Description",type:"number",rows:"4",outlined:"",clearable:""},model:{value:e.form.description,callback:function(t){e.$set(e.form,"description",t)},expression:"form.description"}}),r("v-select",{attrs:{items:e.drivers,"item-text":"name","item-value":"id",label:"Select Delivery Driver",outlined:"",clearable:""},model:{value:e.form.delivery_driver,callback:function(t){e.$set(e.form,"delivery_driver",t)},expression:"form.delivery_driver"}})],1),r("v-col",{attrs:{cols:"12",md:"3"}},[r("v-file-input",{attrs:{accept:"image/jpeg","prepend-icon":"",label:"Photo",clearable:"",outlined:""},on:{change:function(t){return e.preview(t)}},model:{value:e.form.photo,callback:function(t){e.$set(e.form,"photo",t)},expression:"form.photo"}}),r("v-card",{staticClass:"d-flex justify-space-around pa-4",staticStyle:{border:"dashed","border-color":"gray"}},[e.imgUrl?r("v-img",{staticClass:"preview",attrs:{src:e.imgUrl}}):r("v-card-text",{staticClass:"text-center",staticStyle:{height:"185px"}},[e._v(" Image Preview Area ")])],1),r("v-btn",{staticClass:"float-right mt-5px",attrs:{color:"primary","min-width":"150"},on:{click:e.saveOrder}},[e._v(" Create Order ")])],1)],1)],1)],1)],1)},i=[],o=(r("a4d3"),r("e01a"),r("4de4"),r("4160"),r("d3b7"),r("3ca3"),r("159b"),r("ddb0"),r("2b3d"),r("bc3a")),a=r.n(o),s={_props:["data","district","village","methods","del_fee_list","category_id","city"],get props(){return this._props},set props(e){this._props=e},data:function(){return{imgUrl:null,filteredVillage:[],filteredDistrict:[],form:{selectedUser:null,selectedCity:null,selectedDistrict:null,selectedVillage:null,selectedMethod:null,productName:null,weight:null,volume:null,price:null,description:null,photo:null,receiverName:null,receiverAddress:null,descriptionAddress:null,receiverPhone:null,receiverPhone2:null,delivery_fee:0,delivery_driver:null},value:0,label:"Price",readonly:!1,additional_fee:0,additional_fee2:0,disabled:!1,outlined:!0,drivers:[],clearable:!0,options:{locale:"pt-BR",prefix:"Rp ",suffix:" ",length:12,precision:0}}},mounted:function(){this.getDrivers()},methods:{preview:function(e){null!==this.form.photo?(this.form.photo=e,this.imgUrl=URL.createObjectURL(this.form.photo)):this.imgUrl=null},getDrivers:function(){var e=this;a.a.get("admin/driver").then((function(t){e.drivers=t.data.driver}))},countFee:function(){var e=this;this.form.delivery_fee=0;var t=this.form.weight,r=this.del_fee_list;r.forEach((function(r){t>parseInt(r.from_weight)&&t<=parseInt(r.to_weight)&&(e.form.delivery_fee=parseInt(r.price)+parseInt(e.additional_fee)+parseInt(e.additional_fee2))}))},saveOrder:function(){var e=this;this.$swal.showLoading();var t=new FormData;t.append("name",this.form.productName),t.append("weight",this.form.weight),t.append("volume",this.form.volume),t.append("price",this.form.price),t.append("description_order",this.form.description),t.append("photo",this.form.photo),t.append("receiver_name",this.form.receiverName),t.append("receiver_phone",this.form.receiverPhone),t.append("receiver_phone2",this.form.receiverPhone),t.append("receiver_address",this.form.receiverAddress),t.append("description_address",this.form.descriptionAddress),t.append("city",this.form.selectedCity),t.append("district",this.form.selectedDistrict),t.append("village",this.form.selectedVillage),t.append("payment_method",this.form.selectedMethod),t.append("user_id",this.form.selectedUser),t.append("delivery_fee",this.form.delivery_fee),t.append("category_id",this.category_id),t.append("delivery_driver",this.form.delivery_driver),a.a.post("admin/order",t).then((function(){e.$swal("Data Created Successfully","","success"),e.form.productName=null,e.form.weight=0,e.form.volume=0,e.form.price=0,e.form.description=null,e.form.photo=null,e.form.receiverPhone=null,e.form.receiverPhone2=null,e.form.receiverName=null,e.form.receiverAddress=null,e.form.descriptionAddress=null,e.form.selectedCity=null,e.form.selectedDistrict=null,e.form.selectedVillage=null,e.form.selectedMethod=null,e.form.selectedUser=null,e.form.delivery_driver=null})).catch((function(e){console.log(e)}))},filterVillage:function(){var e=this;this.filteredVillage=[],this.filteredVillage=this.village.filter((function(t){return t.kecamatan_id===e.form.selectedDistrict}))},filterDistrict:function(){var e=this;this.filteredDistrict=[],this.filteredDistrict=this.district.filter((function(t){return t.kabupaten_id===e.form.selectedCity}))},deliveryFee:function(){var e=this;"7371"!==this.form.selectedCity?(a.a.get("admin/special-delivery-fee/"+this.form.selectedVillage).then((function(t){e.additional_fee=t.data})),this.countFee()):(this.additional_fee=0,this.countFee())},pickupFee:function(){var e=this;a.a.get("admin/special-pickup-fee/"+this.form.selectedUser).then((function(t){e.additional_fee2=t.data})),this.countFee()}}},n=s,c=(r("bcb8"),r("2877")),d=r("6544"),m=r.n(d),f=r("8336"),u=r("b0af"),p=r("99d9"),v=r("62ad"),h=r("a523"),b=r("23a7"),g=r("4bd4"),y=r("adda"),_=r("0fd9"),x=r("b974"),w=r("8654"),V=r("a844"),k=Object(c["a"])(n,l,i,!1,null,null,null);t["default"]=k.exports;m()(k,{VBtn:f["a"],VCard:u["a"],VCardText:p["b"],VCol:v["a"],VContainer:h["a"],VFileInput:b["a"],VForm:g["a"],VImg:y["a"],VRow:_["a"],VSelect:x["a"],VTextField:w["a"],VTextarea:V["a"]})},"62d9":function(e,t,r){},bcb8:function(e,t,r){"use strict";r("62d9")}}]);
//# sourceMappingURL=views-Orders-Reguler-NewOrderView.685d168d.js.map