var errors_mixin={data:function(){return{errors:[],has_errors:[]}},methods:{insertCustomErrors:function(r){var s=this;void 0!==this.has_errors[r]&&this.$nextTick(function(){s.insertErrors(s.has_errors[r])})},hasError:function(r){if(void 0!==this.has_errors[r])return!0;var s=!1,e=Object.getOwnPropertyNames(this.errors);for(var t in e)e[t].includes(r)&&(void 0===this.has_errors[r]&&(this.has_errors[r]=[]),this.has_errors[r][e[t]]=this.errors[e[t]],s=!0);return s},createErrorSpan:function(r){var s=document.createElement("SPAN");return s.className="has-error",s.appendChild(document.createTextNode(r)),s},findAncestor:function(r,s){for(var e=0,t=!1;e<=5;){if(r.classList.contains(s)){t=!0;break}r=r.parentElement,e++}return t?r:t},insertMessages:function(r,s){var e=Object.getOwnPropertyNames(r);if(Array.isArray(e))for(var t in e)if(this.$refs[e[t]]){var n=Array.isArray(this.$refs[e[t]])?this.$refs[e[t]].length>0&&this.$refs[e[t]][0]:this.$refs[e[t]];if(!1===n)break;n.classList.contains("underlined")||(n.className+=" underlined");var a=this.findAncestor(n,"form-group");if(!1!==a)a.classList.contains(s)||(a.className+=" "+s),Array.from(a.getElementsByClassName(s)).forEach(function(r){r.remove()});if(Array.isArray(r[e[t]]))for(var i in r[e[t]])r[e[t]].hasOwnProperty(i)&&n.parentElement.append(this.createErrorSpan(r[e[t]][i]));else n.parentElement.append(this.createErrorSpan(r[e[t]]));n.onfocus=this.focusEvent}},insertErrors:function(r){this.insertMessages(r,"has-error")},insertSuccess:function(r){this.insertMessages(r,"has-success")},focusEvent:function(r){this.removeMessages(r.srcElement)},removeMessages:function(r){r.classList.remove("underlined");var s=this.findAncestor(r,"form-group");!1!==s&&(s.classList.remove("has-success"),s.classList.remove("has-error"),Array.from(s.querySelectorAll("span.has-error")).forEach(function(r){r.remove()}));r.onfocus=null}}};
