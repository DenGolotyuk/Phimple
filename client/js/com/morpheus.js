/*!
  * Morpheus - A Brilliant Animator
  * https://github.com/ded/morpheus - (c) Dustin Diaz 2011
  * License MIT
  */
!function(a,b){typeof define=="function"?define(b):typeof module!="undefined"?module.exports=b():this[a]=b()}("morpheus",function(){function s(a,b,c){if(Array.prototype.indexOf)return a.indexOf(b);for(c=0;c<a.length;++c)if(a[c]===b)return c}function t(a){var b,c=r.length;for(b=c;b--;)r[b](a);r.length&&q(t)}function u(a){r.push(a)===1&&q(t)}function v(a){var b,c,d=s(r,a);d>=0&&(c=r.slice(d+1),r.length=d,r=r.concat(c))}function w(a,b){var c={},d;if(d=a.match(i))c.rotate=G(d[1],b?b.rotate:null);if(d=a.match(j))c.scale=G(d[1],b?b.scale:null);if(d=a.match(k))c.skewx=G(d[1],b?b.skewx:null),c.skewy=G(d[3],b?b.skewy:null);if(d=a.match(l))c.translatex=G(d[1],b?b.translatex:null),c.translatey=G(d[3],b?b.translatey:null);return c}function x(a){var b="";return"rotate"in a&&(b+="rotate("+a.rotate+"deg) "),"scale"in a&&(b+="scale("+a.scale+") "),"translatex"in a&&(b+="translate("+a.translatex+"px,"+a.translatey+"px) "),"skewx"in a&&(b+="skew("+a.skewx+"deg,"+a.skewy+"deg)"),b}function y(a,b,c){return"#"+(1<<24|a<<16|b<<8|c).toString(16).slice(1)}function z(a){var b=/rgba?\((\d+),\s*(\d+),\s*(\d+)/.exec(a);return(b?y(b[1],b[2],b[3]):a).replace(/#(\w)(\w)(\w)$/,"#$1$1$2$2$3$3")}function A(a){return a.replace(/-(.)/g,function(a,b){return b.toUpperCase()})}function B(a){return typeof a=="function"}function C(a,b,c,d,f,g){function n(a){var e=a-k;if(e>h||l)return g=isFinite(g)?g:1,l?m&&b(g):b(g),v(n),c&&c.apply(i);isFinite(g)?b(j*d(e/h)+f):b(d(e/h))}d=B(d)?d:H.easings[d]||function(a){return Math.sin(a*Math.PI/2)};var h=a||e,i=this,j=g-f,k=+(new Date),l=0,m=0;return u(n),{stop:function(a){l=1,m=a,a||(c=null)}}}function D(a,b){var c=a.length,d=[],e,f;for(e=0;e<c;++e)d[e]=[a[e][0],a[e][1]];for(f=1;f<c;++f)for(e=0;e<c-f;++e)d[e][0]=(1-b)*d[e][0]+b*d[parseInt(e+1,10)][0],d[e][1]=(1-b)*d[e][1]+b*d[parseInt(e+1,10)][1];return[d[0][0],d[0][1]]}function E(a,b,c){var d=[],e,f,g,h;for(e=0;e<6;e++)g=Math.min(15,parseInt(b.charAt(e),16)),h=Math.min(15,parseInt(c.charAt(e),16)),f=Math.floor((h-g)*a+g),f=f>15?15:f<0?0:f,d[e]=f.toString(16);return"#"+d.join("")}function F(a,b,c,d,f,g,h){if(f=="transform"){h={};for(var i in c[g][f])h[i]=i in d[g][f]?Math.round(((d[g][f][i]-c[g][f][i])*a+c[g][f][i])*e)/e:c[g][f][i];return h}return typeof c[g][f]=="string"?E(a,c[g][f],d[g][f]):(h=Math.round(((d[g][f]-c[g][f])*a+c[g][f])*e)/e,f in m||(h+=b[g][f]||"px"),h)}function G(a,b,c,d,e){return(c=g.exec(a))?(e=parseFloat(c[2]))&&b+(c[1]=="+"?1:-1)*e:parseFloat(a)}function H(a,b){var c=a?c=isFinite(a.length)?a:[a]:[],d,e=b.complete,g=b.duration,i=b.easing,j=b.bezier,k=[],l=[],m=[],q=[],r,s;delete b.complete,delete b.duration,delete b.easing,delete b.bezier,j&&(r=b.left,s=b.top,delete b.right,delete b.bottom,delete b.left,delete b.top);for(d=c.length;d--;){k[d]={},l[d]={},m[d]={};if(j){var t=p(c[d],"left"),u=p(c[d],"top"),v=[G(B(r)?r(c[d]):r||0,parseFloat(t)),G(B(s)?s(c[d]):s||0,parseFloat(u))];q[d]=B(j)?j(c[d],v):j,q[d].push(v),q[d].unshift([parseInt(t,10),parseInt(u,10)])}for(var y in b){var E=p(c[d],y),H,I=B(b[y])?b[y](c[d]):b[y];if(typeof I=="string"&&f.test(I)&&!f.test(E)){delete b[y];continue}k[d][y]=y=="transform"?w(E):typeof I=="string"&&f.test(I)?z(E).slice(1):parseFloat(E),l[d][y]=y=="transform"?w(I,k[d][y]):typeof I=="string"&&I.charAt(0)=="#"?z(I).slice(1):G(I,parseFloat(E)),typeof I=="string"&&(H=I.match(h))&&(m[d][y]=H[1])}}return C.apply(c,[g,function(a,e,f){for(d=c.length;d--;){j&&(f=D(q[d],a),c[d].style.left=f[0]+"px",c[d].style.top=f[1]+"px");for(var g in b)e=F(a,m,k,l,g,d),g=="transform"?c[d].style[n]=x(e):g=="opacity"&&!o?c[d].style.filter="alpha(opacity="+e*100+")":c[d].style[A(g)]=e}},e,i])}var a=this,b=document,c=window,d=b.documentElement,e=1e3,f=/^rgb\(|#/,g=/^([+\-])=([\d\.]+)/,h=/^(?:[\+\-]=)?\d+(?:\.\d+)?(%|in|cm|mm|em|ex|pt|pc|px)$/,i=/rotate\(((?:[+\-]=)?([\-\d\.]+))deg\)/,j=/scale\(((?:[+\-]=)?([\d\.]+))\)/,k=/skew\(((?:[+\-]=)?([\-\d\.]+))deg, ?((?:[+\-]=)?([\-\d\.]+))deg\)/,l=/translate\(((?:[+\-]=)?([\-\d\.]+))px, ?((?:[+\-]=)?([\-\d\.]+))px\)/,m={lineHeight:1,zoom:1,zIndex:1,opacity:1,transform:1},n=function(){var a=b.createElement("a").style,c=["webkitTransform","MozTransform","OTransform","msTransform","Transform"],d;for(d=0;d<c.length;d++)if(c[d]in a)return c[d]}(),o=function(){return typeof b.createElement("a").style.opacity!="undefined"}(),p=b.defaultView&&b.defaultView.getComputedStyle?function(a,c){c=c=="transform"?n:c;var d=null,e=b.defaultView.getComputedStyle(a,"");return e&&(d=e[A(c)]),a.style[c]||d}:d.currentStyle?function(a,b){b=A(b);if(b=="opacity"){var c=100;try{c=a.filters["DXImageTransform.Microsoft.Alpha"].opacity}catch(d){try{c=a.filters("alpha").opacity}catch(e){}}return c/100}var f=a.currentStyle?a.currentStyle[b]:null;return a.style[b]||f}:function(a,b){return a.style[A(b)]},q=function(){return c.requestAnimationFrame||c.webkitRequestAnimationFrame||c.mozRequestAnimationFrame||c.oRequestAnimationFrame||c.msRequestAnimationFrame||function(a){c.setTimeout(function(){a(+(new Date))},11)}}(),r=[];return H.tween=C,H.getStyle=p,H.bezier=D,H.transform=n,H.parseTransform=w,H.formatTransform=x,H.easings={},H})