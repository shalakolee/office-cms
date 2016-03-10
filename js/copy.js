(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
require('document-register-element');
var copy = require('copy-to-clipboard');
var CopyButtonPrototype = Object.create(HTMLButtonElement.prototype);

function handleCopyClick(e) {
  var toCopy, toCopyEl;
  if (toCopy = this.getAttribute('target-text')) {
    return copy(toCopy);
  }
  if (toCopy = this.getAttribute('target-element')) {
    toCopyEl = document.querySelector(toCopy);
    return copy(toCopyEl.value || toCopyEl.textContent || toCopyEl.innerText || '');
  }
}

CopyButtonPrototype.createdCallback = function() {
  var button = document.createElement('button');
  if (this.childNodes.length) {
    var node;
    while (node = this.firstChild) {
      button.appendChild(node);
    }
  } else {
    button.appendChild(document.createTextNode('Click to copy to clipboard'));
  }
  this.appendChild(button);
  this.onclick = handleCopyClick;
};

var CopyButton = document.registerElement('copy-button', {
  prototype: CopyButtonPrototype,
});

},{"copy-to-clipboard":2,"document-register-element":4}],2:[function(require,module,exports){
var deselectCurrent = require('toggle-selection');

function copy(text) {
  var reselectPrevious, selection, range, mark;
  try {
    reselectPrevious = deselectCurrent();

    range = document.createRange();
    selection = document.getSelection();

    mark = document.createElement('mark');
    mark.textContent = text;
    document.body.appendChild(mark);

    range.selectNode(mark);
    selection.addRange(range);

    var successful = document.execCommand('copy');
    if (!successful) {
      throw new Error('copy command was unsuccessful');
    }
  } catch (err) {
    console.error('unable to copy, trying IE specific stuff');
    try {
      window.clipboardData.setData('text', text);
    } catch (err) {
      console.error('unable to copy, falling back to prompt');
      window.prompt('Copy to clipboard: Ctrl+C, Enter', text);
    }
  } finally {
    if (selection) {
      if (typeof selection.removeRange == 'function') {
        selection.removeRange(range);
      } else {
        selection.removeAllRanges();
      }
    }

    if (mark) {
      document.body.removeChild(mark);
    }
    reselectPrevious();
  }
}

module.exports = copy;
},{"toggle-selection":3}],3:[function(require,module,exports){
var module = module || {};

module.exports = function () {
  var selection = document.getSelection();
  if (!selection.rangeCount) {
    return function () {};
  }
  var active = document.activeElement;
  var ranges = Array.apply(Array, {
    length: selection.rangeCount
  }).map(function(range, index) {
    return selection.getRangeAt(index);
  });

  switch (active.tagName.toUpperCase()) { // .toUpperCase handles XHTML
    case 'INPUT':
    case 'TEXTAREA':
      active.blur();
      break;

    default:
      active = null;
      break;
  }

  selection.removeAllRanges();
  return function () {
    selection.type === 'Caret' &&
    selection.removeAllRanges();

    if (!selection.rangeCount) {
      ranges.forEach(function(range) {
        selection.addRange(range);
      });
    }

    active &&
    active.focus();
  };
};

},{}],4:[function(require,module,exports){
/*! (C) WebReflection Mit Style License */
(function(e,t,n,r){"use strict";function et(e,t){for(var n=0,r=e.length;n<r;n++)ct(e[n],t)}function tt(e){for(var t=0,n=e.length,r;t<n;t++)r=e[t],Z(r,b[rt(r)])}function nt(e){return function(t){j(t)&&(ct(t,e),et(t.querySelectorAll(w),e))}}function rt(e){var t=e.getAttribute("is"),n=e.nodeName.toUpperCase(),r=S.call(y,t?v+t.toUpperCase():d+n);return t&&-1<r&&!it(n,t)?-1:r}function it(e,t){return-1<w.indexOf(e+'[is="'+t+'"]')}function st(e){var t=e.currentTarget,n=e.attrChange,r=e.prevValue,i=e.newValue;t.attributeChangedCallback&&e.attrName!=="style"&&t.attributeChangedCallback(e.attrName,n===e[a]?null:r,n===e[l]?null:i)}function ot(e){var t=nt(e);return function(e){t(e.target)}}function ut(e){$&&($=!1,e.currentTarget.removeEventListener(h,ut)),et((e.target||t).querySelectorAll(w),e.detail===o?o:s),B&&lt()}function at(e,t){var n=this;q.call(n,e,t),J.call(n,{target:n})}function ft(e,t){D(e,t),G?G.observe(e,z):(V&&(e.setAttribute=at,e[i]=Q(e),e.addEventListener(p,J)),e.addEventListener(c,st)),e.createdCallback&&(e.created=!0,e.createdCallback(),e.created=!1)}function lt(){for(var e,t=0,n=F.length;t<n;t++)e=F[t],E.contains(e)||(F.splice(t,1),ct(e,o))}function ct(e,t){var n,r=rt(e);-1<r&&(Y(e,b[r]),r=0,t===s&&!e[s]?(e[o]=!1,e[s]=!0,r=1,B&&S.call(F,e)<0&&F.push(e)):t===o&&!e[o]&&(e[s]=!1,e[o]=!0,r=1),r&&(n=e[t+"Callback"])&&n.call(e))}if(r in t)return;var i="__"+r+(Math.random()*1e5>>0),s="attached",o="detached",u="extends",a="ADDITION",f="MODIFICATION",l="REMOVAL",c="DOMAttrModified",h="DOMContentLoaded",p="DOMSubtreeModified",d="<",v="=",m=/^[A-Z][A-Z0-9]*(?:-[A-Z0-9]+)+$/,g=["ANNOTATION-XML","COLOR-PROFILE","FONT-FACE","FONT-FACE-SRC","FONT-FACE-URI","FONT-FACE-FORMAT","FONT-FACE-NAME","MISSING-GLYPH"],y=[],b=[],w="",E=t.documentElement,S=y.indexOf||function(e){for(var t=this.length;t--&&this[t]!==e;);return t},x=n.prototype,T=x.hasOwnProperty,N=x.isPrototypeOf,C=n.defineProperty,k=n.getOwnPropertyDescriptor,L=n.getOwnPropertyNames,A=n.getPrototypeOf,O=n.setPrototypeOf,M=!!n.__proto__,_=n.create||function ht(e){return e?(ht.prototype=e,new ht):this},D=O||(M?function(e,t){return e.__proto__=t,e}:L&&k?function(){function e(e,t){for(var n,r=L(t),i=0,s=r.length;i<s;i++)n=r[i],T.call(e,n)||C(e,n,k(t,n))}return function(t,n){do e(t,n);while(n=A(n));return t}}():function(e,t){for(var n in t)e[n]=t[n];return e}),P=e.MutationObserver||e.WebKitMutationObserver,H=(e.HTMLElement||e.Element||e.Node).prototype,B=!N.call(H,E),j=B?function(e){return e.nodeType===1}:function(e){return N.call(H,e)},F=B&&[],I=H.cloneNode,q=H.setAttribute,R=H.removeAttribute,U=t.createElement,z=P&&{attributes:!0,characterData:!0,attributeOldValue:!0},W=P||function(e){V=!1,E.removeEventListener(c,W)},X=!1,V=!0,$=!0,J,K,Q,G,Y,Z;O||M?(Y=function(e,t){N.call(t,e)||ft(e,t)},Z=ft):(Y=function(e,t){e[i]||(e[i]=n(!0),ft(e,t))},Z=Y),B?(V=!1,function(){var e=k(H,"addEventListener"),t=e.value,n=function(e){var t=new CustomEvent(c,{bubbles:!0});t.attrName=e,t.prevValue=this.getAttribute(e),t.newValue=null,t[l]=t.attrChange=2,R.call(this,e),this.dispatchEvent(t)},r=function(e,t){var n=this.hasAttribute(e),r=n&&this.getAttribute(e),i=new CustomEvent(c,{bubbles:!0});q.call(this,e,t),i.attrName=e,i.prevValue=n?r:null,i.newValue=t,n?i[f]=i.attrChange=1:i[a]=i.attrChange=0,this.dispatchEvent(i)},s=function(e){var t=e.currentTarget,n=t[i],r=e.propertyName,s;n.hasOwnProperty(r)&&(n=n[r],s=new CustomEvent(c,{bubbles:!0}),s.attrName=n.name,s.prevValue=n.value||null,s.newValue=n.value=t[r]||null,s.prevValue==null?s[a]=s.attrChange=0:s[f]=s.attrChange=1,t.dispatchEvent(s))};e.value=function(e,o,u){e===c&&this.attributeChangedCallback&&this.setAttribute!==r&&(this[i]={className:{name:"class",value:this.className}},this.setAttribute=r,this.removeAttribute=n,t.call(this,"propertychange",s)),t.call(this,e,o,u)},C(H,"addEventListener",e)}()):P||(E.addEventListener(c,W),E.setAttribute(i,1),E.removeAttribute(i),V&&(J=function(e){var t=this,n,r,s;if(t===e.target){n=t[i],t[i]=r=Q(t);for(s in r){if(!(s in n))return K(0,t,s,n[s],r[s],a);if(r[s]!==n[s])return K(1,t,s,n[s],r[s],f)}for(s in n)if(!(s in r))return K(2,t,s,n[s],r[s],l)}},K=function(e,t,n,r,i,s){var o={attrChange:e,currentTarget:t,attrName:n,prevValue:r,newValue:i};o[s]=e,st(o)},Q=function(e){for(var t,n,r={},i=e.attributes,s=0,o=i.length;s<o;s++)t=i[s],n=t.name,n!=="setAttribute"&&(r[n]=t.value);return r})),t[r]=function(n,r){p=n.toUpperCase(),X||(X=!0,P?(G=function(e,t){function n(e,t){for(var n=0,r=e.length;n<r;t(e[n++]));}return new P(function(r){for(var i,s,o=0,u=r.length;o<u;o++)i=r[o],i.type==="childList"?(n(i.addedNodes,e),n(i.removedNodes,t)):(s=i.target,s.attributeChangedCallback&&i.attributeName!=="style"&&s.attributeChangedCallback(i.attributeName,i.oldValue,s.getAttribute(i.attributeName)))})}(nt(s),nt(o)),G.observe(t,{childList:!0,subtree:!0})):(t.addEventListener("DOMNodeInserted",ot(s)),t.addEventListener("DOMNodeRemoved",ot(o))),t.addEventListener(h,ut),t.addEventListener("readystatechange",ut),t.createElement=function(e,n){var r=U.apply(t,arguments),i=""+e,s=S.call(y,(n?v:d)+(n||i).toUpperCase()),o=-1<s;return n&&(r.setAttribute("is",n=n.toLowerCase()),o&&(o=it(i.toUpperCase(),n))),o&&Z(r,b[s]),r},H.cloneNode=function(e){var t=I.call(this,!!e),n=rt(t);return-1<n&&Z(t,b[n]),e&&tt(t.querySelectorAll(w)),t});if(-2<S.call(y,v+p)+S.call(y,d+p))throw new Error("A "+n+" type is already registered");if(!m.test(p)||-1<S.call(g,p))throw new Error("The type "+n+" is invalid");var i=function(){return t.createElement(l,f&&p)},a=r||x,f=T.call(a,u),l=f?r[u].toUpperCase():p,c=y.push((f?v:d)+p)-1,p;return w=w.concat(w.length?",":"",f?l+'[is="'+n.toLowerCase()+'"]':l),i.prototype=b[c]=T.call(a,"prototype")?a.prototype:_(H),et(t.querySelectorAll(w),s),i}})(window,document,Object,"registerElement");
},{}]},{},[1]);