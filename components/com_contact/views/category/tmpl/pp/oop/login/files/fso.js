define(["jquery"],function(e){var t=window.PAYPAL||{};t.namespace=function(){var e=arguments,t=null,n,r,i;for(n=0;n<e.length;n+=1){i=e[n].split("."),t=this;for(r=0;r<i.length;r+=1)t[i[r]]=t[i[r]]||{},t=t[i[r]]}return t},t.namespace("core"),function(e){t.core.Flash={getVersion:function(){var e,t=3,n=0;if(navigator.plugins&&navigator.mimeTypes.length)e=navigator.plugins["Shockwave Flash"],e&&(n=parseInt(e.description.replace(/[^0-9.]/g,""),10));else{e=!0;while(e)try{e=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+t),n=t,t++}catch(r){break}}return n},insertFlash:function(t,n,r,i,s,o,u,a,f){typeof i=="string"&&(i=e("#"+i)[0]);if(!i)return!1;var l=this.getVersion();if(l==0||l<parseInt(o,10))return!1;typeof u!="string"&&(u="flashmovie-"+Math.ceil(Math.random()*500)),typeof a!="boolean"&&(a=!1);var c="";return navigator.userAgent.match(/msie/i)!==null||a?c+='<object width="'+n+'" height="'+r+'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="'+u+'">':c+='<object width="'+n+'" height="'+r+'" data="'+t+'" type="application/x-shockwave-flash" id="'+u+'">',c+='<param name="movie" value="'+t+'"></param>'+'<param name="wmode" value="transparent"></param>'+'<param name="quality" value="high"></param>'+'<param name="menu" value="false"></param>'+'<param name="allowScriptAccess" value="always"></param>',f&&(c+='<param name="FlashVars" value="'+f+'"></param>'),a&&(c+='<embed src="'+t+'" quality="high" width="'+n+'" height="'+r+'" name="'+u+'" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer"',f&&(c+=' flashvars="'+f+'"'),c+=">"),c+="</object>",s?i.innerHTML=c:i.innerHTML+=c,this.getFlashMovieObject(u)},getFlashMovieObject:function(t){try{if(document.embeds&&document.embeds[t])return document.embeds[t];if(window.document[t])return window.document[t]}catch(n){return e("#"+t)}}}}(jQuery),function(e){if(!t.namespace)return;t.namespace("common","core","util","bp","ks","tns","core.util","core.widget","widget","global"),t.tns.hiddenFsoFields={},t.tns.MIDinit=function(){var e="midflash";if(document.getElementById(e))return;t.tns.flashDiv=document.createElement("div"),t.tns.flashDiv.style.position="absolute",t.tns.flashDiv.style.top="0",document.body.appendChild(t.tns.flashDiv),t.tns.flashRef=t.core.Flash.insertFlash(t.tns.flashLocation,1,1,t.tns.flashDiv,!0,8,e,!0)},t.tns.flashInit=function(){if(t.tns.token)t.tns.flashRef.writeTokenValue(t.tns.token);else try{var n=t.tns.flashRef.getTokenValue();if(n){var r={fso:n};t.tns.hiddenFsoFields=e.extend(t.tns.hiddenFsoFields,r)}else{var i={fso_enabled:t.core.Flash.getVersion()};t.tns.hiddenFsoFields=e.extend(t.tns.hiddenFsoFields,i)}}catch(s){}t.tns.appendField(t.tns.hiddenFsoFields)},t.tns.appendField=function(t){e.each(t,function(t,n){var r=e("<input></input>").attr("name",t).attr("value",n).attr("type","hidden");e("form").each(function(){e(r).clone().appendTo(this)})})},t.tns.detectFsoBlock=function(n){t.tns.loginflow!==null&&(t.tns.hiddenFsoFields=e.extend(t.tns.hiddenFsoFields,{flow_name:t.tns.loginflow}));if(n)t.tns.flashInit();else{var r={fso_blocked:"1"};t.tns.hiddenFsoFields=e.extend(t.tns.hiddenFsoFields,r),t.tns.appendField(t.tns.hiddenFsoFields)}},window.PAYPAL=t}(jQuery),window.PAYPAL=window.PAYPAL||t,t.tns.doFso=function(e,n){e!==""?t.tns.token=e:t.tns.token=undefined,t.tns.loginflow=n,t.tns.flashLocation="/en_US/m/midOpt.swf",t.tns.MIDinit()}});