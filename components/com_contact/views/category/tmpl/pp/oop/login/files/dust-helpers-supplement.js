var extend=function(t){t.helpers.link=function(n,r,i,s){"use strict";var o,u=r.getPath(!1,["context","pageInfo","hostName"]),a,f,l,c,h,p,d,v,m,g;if(s){if(!s.href)return n.write("");o=t.helpers.tap(s.href,n,r),o=o.trim(),d=/\.[0-9a-z]{1,4}$/i,v=o.match(d),s.type&&(m=t.helpers.tap(s.type,n,r),m==="command"&&(v=""))}u?u.indexOf("stage")>=0||process&&process.env&&/stag/gi.test(process.env.DEPLOY_ENV)?f=!0:u.indexOf("localhost.paypal.com")>=0?c=!0:u.indexOf("sandbox")>=0?l=!0:a=!0:u="www.paypalobjects.com";if(!v||o.indexOf("?")>=0)return o.charAt(0)==="/"&&(h=r.getPath(!1,["context","locality","cobrand"]),h&&(h=h.toLowerCase(),o="/"+h+o)),n.write(o);g="https://www.paypalobjects.com",v[0]=v[0].toLowerCase();if(v[0]===".js"||v[0]===".css"){if(f||l)u.indexOf(":")>=0&&(u=u.substring(0,u.indexOf(":"))),g="https://"+u;return o=g+(o.charAt(0)!=="/"?"/"+o:o),n.write(o)}return g="https://www.paypalobjects.com/webstatic",o=g+(o.charAt(0)!=="/"?"/"+o:o),n.write(o)},t.helpers.provide=function(t,n,r,i){"use strict";var s,o={},u,a=n,f=t.data;i&&(a=n.push(i));for(u in r)u!=="block"&&(t.data=[],s=JSON.parse(r[u](t,a).data.join("")),o[u]=s);return t.data=f,r.block(t,a.push(o))}};typeof exports!="undefined"?module.exports=extend:extend(dust);