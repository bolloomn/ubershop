!function(t){function e(i){if(n[i])return n[i].exports;var r=n[i]={i:i,l:!1,exports:{}};return t[i].call(r.exports,r,r.exports,e),r.l=!0,r.exports}var n={};e.m=t,e.c=n,e.d=function(t,n,i){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:i})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=61)}({0:function(t,e,n){"use strict";var i=function(){function t(t,e){void 0===e&&(e={}),this.varName=t,this.defaults=e,this.settings={};var n=void 0!==window[t]?window[t]:{};Object.assign(this.settings,e,n)}return t.prototype.get=function(t){if(void 0!==this.settings[t])return this.settings[t]},t.prototype.getAll=function(){return this.settings},t.prototype.delete=function(t){this.settings.hasOwnProperty(t)&&delete this.settings[t]},t}();e.a=i},1:function(t,e,n){"use strict";n.d(e,"a",function(){return i});var i={delayTimer:0,delay:function(t,e){clearTimeout(this.delayTimer),this.delayTimer=setTimeout(t,e)},filterQuery:function(t,e){for(var n=t.split("&"),i=0;i<n.length;i++){var r=n[i].split("=");if(r[0]===e)return r[1]}return!1},filterByData:function(t,e,n){return void 0===n?t.filter(function(t,n){return void 0!==$(n).data(e)}):t.filter(function(t,i){return $(i).data(e)==n})},addNotice:function(t,e){var n=$('<div class="'+t+' notice is-dismissible"><p><strong>'+e+"</strong></p></div>").hide(),i=$("<button />",{type:"button",class:"notice-dismiss"}),r=$(".wp-header-end");r.siblings(".notice").remove(),r.before(n.append(i)),n.slideDown(100),i.on("click.wp-dismiss-notice",function(t){t.preventDefault(),n.fadeTo(100,0,function(){n.slideUp(100,function(){n.remove()})})})},imagesLoaded:function(t){var e=t.find('img[src!=""]');if(!e.length)return $.Deferred().resolve().promise();var n=[];return e.each(function(){var t=$.Deferred(),e=new Image;n.push(t),e.onload=function(){t.resolve()},e.onerror=function(){t.resolve()},e.src=this.src}),$.when.apply($,n)},getUrlParameter:function(t){if("undefined"!=typeof URLSearchParams){return new URLSearchParams(window.location.search).get(t)}t=t.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]");var e=new RegExp("[\\?&]"+t+"=([^&#]*)"),n=e.exec(window.location.search);return null===n?"":decodeURIComponent(n[1].replace(/\+/g," "))}}},61:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=n(0),r=n(62);window.$=window.jQuery,jQuery(function(t){var e=new i.a("atumExport");new r.a(e)})},62:function(t,e,n){"use strict";var i=n(1),r=function(){function t(t){var e=this;this.settings=t,this.$pageWrapper=$("#wpbody-content"),this.$tabContentWrapper=$("#screen-meta"),this.$tabsWrapper=$("#screen-meta-links"),this.createExportTab(),this.$pageWrapper.on("submit","#atum-export-settings",function(t){t.preventDefault(),e.downloadReport()}).on("change","#disableMaxLength",function(t){var e=$(t.currentTarget),n=e.parent().siblings("input[type=number]");e.is(":checked")?n.prop("disabled",!0):n.prop("disabled",!1)})}return t.prototype.createExportTab=function(){var t=this.$tabsWrapper.find("#screen-options-link-wrap").clone(),e=this.$tabContentWrapper.find("#screen-options-wrap").clone();if(e.attr({id:"atum-export-wrap","aria-label":this.settings.get("tabTitle")}),e.find("form").attr("id","atum-export-settings"),e.find(".screen-options").remove(),e.find("input[type=submit]").val(this.settings.get("submitTitle")),e.find("#screenoptionnonce").remove(),void 0!==this.settings.get("productTypes")){var n=$('<fieldset class="product-type" />');n.append("<legend>"+this.settings.get("productTypesTitle")+"</legend>"),n.append(this.settings.get("productTypes")),n.insertAfter(e.find("fieldset").last())}if(void 0!==this.settings.get("categories")){var i=$('<fieldset class="product-category" />');i.append("<legend>"+this.settings.get("categoriesTitle")+"</legend>"),i.append(this.settings.get("categories")),i.insertAfter(e.find("fieldset").last())}var r=$('<fieldset class="title-length" />');r.append("<legend>"+this.settings.get("titleLength")+"</legend>"),r.append('<input type="number" step="1" min="0" name="title_max_length" value="'+this.settings.get("maxLength")+'"> '),r.append('<label><input type="checkbox" id="disableMaxLength" value="yes">'+this.settings.get("disableMaxLength")+"</label>"),r.insertAfter(e.find("fieldset").last());var s=$('<fieldset class="output-format" />');s.append("<legend>"+this.settings.get("outputFormatTitle")+"</legend>"),$.each(this.settings.get("outputFormats"),function(t,e){s.append('<label><input type="radio" name="output-format" value="'+t+'">'+e+"</label>")}),s.find("input[name=output-format]").first().prop("checked",!0),s.insertAfter(e.find("fieldset").last()),e.find(".submit").before('<div class="clear"></div>'),t.attr("id","atum-export-link-wrap").find("button").attr({id:"show-export-settings-link","aria-controls":"atum-export-wrap"}).text(this.settings.get("tabTitle")),this.$tabContentWrapper.append(e),this.$tabsWrapper.prepend(t),$("#show-export-settings-link").click(window.screenMeta.toggleEvent),this.$exportForm=this.$pageWrapper.find("#atum-export-settings")},t.prototype.downloadReport=function(){window.open(window.ajaxurl+"?action=atum_export_data&page="+i.a.getUrlParameter("page")+"&screen="+this.settings.get("screen")+"&token="+this.settings.get("exportNonce")+"&"+this.$exportForm.serialize(),"_blank")},t}();e.a=r}});
//# sourceMappingURL=atum-data-export.js.map