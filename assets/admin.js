import{n as l,V as c}from"./assets/_plugin-vue2_normalizer-5d9fa191.js";const m={name:"App",data(){return{url:admin_settings.options.url,description:admin_settings.options.description}},methods:{}};var d=function(){var e=this,a=e._self._c;return a("div",{staticClass:"mt-6 mb-6 bg-white"},[a("div",{staticClass:"mb-6"},[a("label",{staticClass:"block text-gray-700 text-sm font-bold mb-2",attrs:{for:"description"}},[e._v("Project Description")]),a("textarea",{directives:[{name:"model",rawName:"v-model",value:e.description,expression:"description"}],staticClass:"p-2 shadow appearance-none border rounded w-3/6 text-gray-700 leading-tight focus:outline-none focus:shadow-outline",attrs:{id:"description",name:"description",rows:"4",cols:"50"},domProps:{value:e.description},on:{input:function(r){r.target.composing||(e.description=r.target.value)}}})]),a("div",{staticClass:"mb-6"},[a("label",{staticClass:"block text-gray-700 text-sm font-bold mb-2",attrs:{for:"url"}},[e._v("External URL")]),a("input",{directives:[{name:"model",rawName:"v-model",value:e.url,expression:"url"}],staticClass:"shadow appearance-none border rounded w-3/6 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline",attrs:{id:"url",name:"url",type:"url",placeholder:"https://example.com"},domProps:{value:e.url},on:{input:function(r){r.target.composing||(e.url=r.target.value)}}})]),e._m(0)])},u=[function(){var t=this,e=t._self._c;return e("div",[e("label",{staticClass:"block text-gray-700 text-sm font-bold mb-2",attrs:{for:"images"}},[t._v("Project Images")]),e("input",{staticClass:"bg-black text-white pl-6 pr-6 p-3",attrs:{type:"button",id:"add-gallery-image",value:"Add Image"}})])}],p=l(m,d,u,!1,null,null,null,null);const g=p.exports;jQuery(document).ready(function(t){t("#add-gallery-image").on("click",function(){var e=wp.media({title:"Select Project Images",library:{type:"image"},multiple:!0});e.on("select",function(){var a=e.state().get("selection").toJSON(),r=t("#gallery-images");t.each(a,function(v,i){var n=i.sizes.thumbnail.url,o=i.id,s='<li class="mr-8 mb-8"><img class="project-image" src="'+n+'" alt="Gallery Image">';s+='<input type="hidden" name="gallery_images[]" value="'+o+'">',s+='<span class="remove-image">Remove</span></li>',r.append(s)})}),e.open()}),t("body").on("click",".remove-image",function(){t(this).closest("li").remove()})});new c({el:"#wppool_fields",render:t=>t(g)});
