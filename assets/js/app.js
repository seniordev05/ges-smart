//~ $('#job_apply').attr("disabled", true);
	
// job application form purpose        
$(document).ready(function(){
$('#applyjobfrm').submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);
	var urls =  $('#applyjobfrm').attr('action');
       $.ajax({
		   
        url: urls,
        dataType:'json',
        type: 'POST',
        data: formData,
        success: function (data) {
			$('.error_i').text('');
            if(data.response=='failed')
            {
				$('#'+data.errid).text(data.msg).css('color','red');
			}
			if(data.response=='success')
			{
				$("#applyjobfrm").trigger("reset");
			 window.location.href=data.url;
			}
        },
        cache: false,
        contentType: false,
        processData: false
    });
  });
  });
// job application form purpose        


var output = '';
var closest_hidden_grade = '';

(function(h,j,e){var a="placeholder"in j.createElement("input");var f="placeholder"in j.createElement("textarea");var k=e.fn;var d=e.valHooks;var b=e.propHooks;var m;var l;if(a&&f){l=k.placeholder=function(){return this};l.input=l.textarea=true}else{l=k.placeholder=function(){var n=this;n.filter((a?"textarea":":input")+"[placeholder]").not(".placeholder").bind({"focus.placeholder":c,"blur.placeholder":g}).data("placeholder-enabled",true).trigger("blur.placeholder");return n};l.input=a;l.textarea=f;m={get:function(o){var n=e(o);var p=n.data("placeholder-password");if(p){return p[0].value}return n.data("placeholder-enabled")&&n.hasClass("placeholder")?"":o.value},set:function(o,q){var n=e(o);var p=n.data("placeholder-password");if(p){return p[0].value=q}if(!n.data("placeholder-enabled")){return o.value=q}if(q==""){o.value=q;if(o!=j.activeElement){g.call(o)}}else{if(n.hasClass("placeholder")){c.call(o,true,q)||(o.value=q)}else{o.value=q}}return n}};if(!a){d.input=m;b.value=m}if(!f){d.textarea=m;b.value=m}e(function(){e(j).delegate("form","submit.placeholder",function(){var n=e(".placeholder",this).each(c);setTimeout(function(){n.each(g)},10)})});e(h).bind("beforeunload.placeholder",function(){e(".placeholder").each(function(){this.value=""})})}function i(o){var n={};var p=/^jQuery\d+$/;e.each(o.attributes,function(r,q){if(q.specified&&!p.test(q.name)){n[q.name]=q.value}});return n}function c(o,p){var n=this;var q=e(n);if(n.value==q.attr("placeholder")&&q.hasClass("placeholder")){if(q.data("placeholder-password")){q=q.hide().next().show().attr("id",q.removeAttr("id").data("placeholder-id"));if(o===true){return q[0].value=p}q.focus()}else{n.value="";q.removeClass("placeholder");n==j.activeElement&&n.select()}}}function g(){var r;var n=this;var q=e(n);var p=this.id;if(n.value==""){if(n.type=="password"){if(!q.data("placeholder-textinput")){try{r=q.clone().attr({type:"text"})}catch(o){r=e("<input>").attr(e.extend(i(this),{type:"text"}))}r.removeAttr("name").data({"placeholder-password":q,"placeholder-id":p}).bind("focus.placeholder",c);q.data({"placeholder-textinput":r,"placeholder-id":p}).before(r)}q=q.removeAttr("id").hide().prev().attr("id",p).show()}q.addClass("placeholder");q[0].value=q.attr("placeholder")}else{q.removeClass("placeholder")}}}(this,document,jQuery));;window.Modernizr=function(a,b,c){function w(a){j.cssText=a}function x(a,b){return w(m.join(a+";")+(b||""))}function y(a,b){return typeof a===b}function z(a,b){return!!~(""+a).indexOf(b)}function A(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:y(f,"function")?f.bind(d||b):f}return!1}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n={},o={},p={},q=[],r=q.slice,s,t=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},u={}.hasOwnProperty,v;!y(u,"undefined")&&!y(u.call,"undefined")?v=function(a,b){return u.call(a,b)}:v=function(a,b){return b in a&&y(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=r.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(r.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(r.call(arguments)))};return e}),n.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:t(["@media (",m.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c};for(var B in n)v(n,B)&&(s=B.toLowerCase(),e[s]=n[B](),q.push((e[s]?"":"no-")+s));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)v(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},w(""),i=k=null,e._version=d,e._prefixes=m,e.testStyles=t,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+q.join(" "):""),e}(this,this.document);Modernizr.addTest('android',function(){return!!navigator.userAgent.match(/Android/i)});Modernizr.addTest('chrome',function(){return!!navigator.userAgent.match(/Chrome/i)});Modernizr.addTest('firefox',function(){return!!navigator.userAgent.match(/Firefox/i)});Modernizr.addTest('iemobile',function(){return!!navigator.userAgent.match(/IEMobile/i)});Modernizr.addTest('ie',function(){return!!navigator.userAgent.match(/MSIE/i)});Modernizr.addTest('ie10',function(){return!!navigator.userAgent.match(/MSIE 10/i)});Modernizr.addTest('ios',function(){return!!navigator.userAgent.match(/iPhone|iPad|iPod/i)});(function(a,b){"use strict";var c="undefined"!=typeof Element&&"ALLOW_KEYBOARD_INPUT"in Element,d=function(){for(var a,c,d=[["requestFullscreen","exitFullscreen","fullscreenElement","fullscreenEnabled","fullscreenchange","fullscreenerror"],["webkitRequestFullscreen","webkitExitFullscreen","webkitFullscreenElement","webkitFullscreenEnabled","webkitfullscreenchange","webkitfullscreenerror"],["webkitRequestFullScreen","webkitCancelFullScreen","webkitCurrentFullScreenElement","webkitCancelFullScreen","webkitfullscreenchange","webkitfullscreenerror"],["mozRequestFullScreen","mozCancelFullScreen","mozFullScreenElement","mozFullScreenEnabled","mozfullscreenchange","mozfullscreenerror"]],e=0,f=d.length,g={};f>e;e++)if(a=d[e],a&&a[1]in b){for(e=0,c=a.length;c>e;e++)g[d[0][e]]=a[e];return g}return!1}(),e={request:function(a){var e=d.requestFullscreen;a=a||b.documentElement,/5\.1[\.\d]* Safari/.test(navigator.userAgent)?a[e]():a[e](c&&Element.ALLOW_KEYBOARD_INPUT)},exit:function(){b[d.exitFullscreen]()},toggle:function(a){this.isFullscreen?this.exit():this.request(a)},onchange:function(){},onerror:function(){},raw:d};return d?(Object.defineProperties(e,{isFullscreen:{get:function(){return!!b[d.fullscreenElement]}},element:{enumerable:!0,get:function(){return b[d.fullscreenElement]}},enabled:{enumerable:!0,get:function(){return!!b[d.fullscreenEnabled]}}}),b.addEventListener(d.fullscreenchange,function(a){e.onchange.call(e,a)}),b.addEventListener(d.fullscreenerror,function(a){e.onerror.call(e,a)}),a.screenfull=e,void 0):a.screenfull=!1})(window,document);+function($){"use strict";var Shift=function(element){this.$element=$(element)
this.$prev=this.$element.prev()
!this.$prev.length&&(this.$parent=this.$element.parent())}
Shift.prototype={constructor:Shift,init:function(){var $el=this.$element,method=$el.data()['toggle'].split(':')[1],$target=$el.data('target')
$el.hasClass('in')||$el[method]($target).addClass('in')},reset:function(){this.$parent&&this.$parent['prepend'](this.$element)
!this.$parent&&this.$element['insertAfter'](this.$prev)
this.$element.removeClass('in')}}
$.fn.shift=function(option){return this.each(function(){var $this=$(this),data=$this.data('shift')
if(!data)$this.data('shift',(data=new Shift(this)))
if(typeof option=='string')data[option]()})}
$.fn.shift.Constructor=Shift}(jQuery);Date.now=Date.now||function(){return+new Date;};+function($){$(function(){$(document).on('click',"[data-toggle=fullscreen]",function(e){if(screenfull.enabled){screenfull.request();}});$('input[placeholder], textarea[placeholder]').placeholder();$("[data-toggle=popover]").popover();$(document).on('click','.popover-title .close',function(e){var $target=$(e.target),$popover=$target.closest('.popover').prev();$popover&&$popover.popover('hide');});$(document).on('click','[data-toggle="ajaxModal"]',function(e){$('#ajaxModal').remove();e.preventDefault();var $this=$(this),$remote=$this.data('remote')||$this.attr('href'),$modal=$('<div class="modal fade" id="ajaxModal"><div class="modal-body"></div></div>');$('body').append($modal);$modal.modal();$modal.load($remote);});$.fn.dropdown.Constructor.prototype.change=function(e){e.preventDefault();var $item=$(e.target),$select,$checked=false,$menu,$label;!$item.is('a')&&($item=$item.closest('a'));$menu=$item.closest('.dropdown-menu');$label=$menu.parent().find('.dropdown-label');$labelHolder=$label.text();$select=$item.find('input');$checked=$select.is(':checked');if($select.is(':disabled'))return;if($select.attr('type')=='radio'&&$checked)return;if($select.attr('type')=='radio')$menu.find('li').removeClass('active');$item.parent().removeClass('active');!$checked&&$item.parent().addClass('active');$select.prop("checked",!$select.prop("checked"));$items=$menu.find('li > a > input:checked');if($items.length){$text=[];$items.each(function(){var $str=$(this).parent().text();$str&&$text.push($.trim($str));});$text=$text.length<4?$text.join(', '):$text.length+' selected';$label.html($text);}else{$label.html($label.data('placeholder'));}}
$(document).on('click.dropdown-menu','.dropdown-select > li > a',$.fn.dropdown.Constructor.prototype.change);$("[data-toggle=tooltip]").tooltip();$(document).on('click','[data-toggle^="class"]',function(e){e&&e.preventDefault();var $this=$(e.target),$class,$target,$tmp,$classes,$targets;!$this.data('toggle')&&($this=$this.closest('[data-toggle^="class"]'));$class=$this.data()['toggle'];$target=$this.data('target')||$this.attr('href');$class&&($tmp=$class.split(':')[1])&&($classes=$tmp.split(','));$target&&($targets=$target.split(','));$targets&&$targets.length&&$.each($targets,function(index,value){($targets[index]!='#')&&$($targets[index]).toggleClass($classes[index]);});$this.toggleClass('active');});$(document).on('click','.panel-toggle',function(e){e&&e.preventDefault();var $this=$(e.target),$class='collapse',$target;if(!$this.is('a'))$this=$this.closest('a');$target=$this.closest('.panel');$target.find('.panel-body').toggleClass($class);$this.toggleClass('active');});$('.carousel.auto').carousel();$(document).on('click.button.data-api','[data-loading-text]',function(e){var $this=$(e.target);$this.is('i')&&($this=$this.parent());$this.button('loading');});var scrollToTop=function(){!location.hash&&setTimeout(function(){if(!pageYOffset)window.scrollTo(0,0);},1000);};var $window=$(window);var mobile=function(option){if(option=='reset'){$('[data-toggle^="shift"]').shift('reset');return;}
scrollToTop();$('[data-toggle^="shift"]').shift('init');return true;};$window.width()<768&&mobile();var $resize;$window.resize(function(){clearTimeout($resize);$resize=setTimeout(function(){$window.width()<767&&mobile();$window.width()>=768&&mobile('reset');},500);});$('.vbox > footer').prev('section').addClass('w-f');$(document).on('click','.sidebar-menu a',function(e){var $this=$(e.target),$active;$this.is('a')||($this=$this.closest('a'));if($('.nav-vertical').length){return;}
$active=$this.parent().siblings(".active");$active&&$active.find('> a').toggleClass('active')&&$active.toggleClass('active').find('> ul:visible').slideUp(200);($this.hasClass('active')&&$this.next().slideUp(200))||$this.next().slideDown(200);$this.toggleClass('active').parent().toggleClass('active');$this.next().is('ul')&&e.preventDefault();});$(document).on('click.bs.dropdown.data-api','.dropdown .on, .dropup .on',function(e){e.stopPropagation()});});}(jQuery);!function($){$(function(){var sr,sparkline=function($re){$(".sparkline").each(function(){var $data=$(this).data();if($re&&!$data.resize)return;($data.type=='pie')&&$data.sliceColors&&($data.sliceColors=eval($data.sliceColors));($data.type=='bar')&&$data.stackedBarColor&&($data.stackedBarColor=eval($data.stackedBarColor));$data.valueSpots={'0:':$data.spotColor};$(this).sparkline('html',$data);});};$(window).resize(function(e){clearTimeout(sr);sr=setTimeout(function(){sparkline(true)},500);});sparkline(false);$('.easypiechart').each(function(){var $this=$(this),$data=$this.data(),$step=$this.find('.step'),$target_value=parseInt($($data.target).text()),$value=0;$data.barColor||($data.barColor=function($percent){$percent/=100;return"rgb("+Math.round(200*$percent)+", 200, "+Math.round(200*(1-$percent))+")";});$data.onStep=function(value){$value=value;$step.text(parseInt(value));$data.target&&$($data.target).text(parseInt(value)+$target_value);}
$data.onStop=function(){$target_value=parseInt($($data.target).text());$data.update&&setTimeout(function(){$this.data('easyPieChart').update(100-$value);},$data.update);}
$(this).easyPieChart($data);});$(".combodate").each(function(){$(this).combodate();$(this).next('.combodate').find('select').addClass('form-control');});$(".datepicker-input").each(function(){$(this).datepicker({ language: locale});});$('.dropfile').each(function(){var $dropbox=$(this);if(typeof window.FileReader==='undefined'){$('small',this).html('File API & FileReader API not supported').addClass('text-danger');return;}
this.ondragover=function(){$dropbox.addClass('hover');return false;};this.ondragend=function(){$dropbox.removeClass('hover');return false;};this.ondrop=function(e){e.preventDefault();$dropbox.removeClass('hover').html('');var file=e.dataTransfer.files[0],reader=new FileReader();reader.onload=function(event){$dropbox.append($('<img>').attr('src',event.target.result));};reader.readAsDataURL(file);return false;};});var addPill=function($input){var $text=$input.val(),$pills=$input.closest('.pillbox'),$repeat=false,$repeatPill;if($text=="")return;$("li",$pills).text(function(i,v){if(v==$text){$repeatPill=$(this);$repeat=true;}});if($repeat){$repeatPill.fadeOut().fadeIn();return;};$item=$('<li class="label bg-dark">'+$text+'</li> ');$item.insertBefore($input);$input.val('');$pills.trigger('change',$item);};$('.pillbox input').on('blur',function(){addPill($(this));});$('.pillbox input').on('keypress',function(e){if(e.which==13){e.preventDefault();addPill($(this));}});$('.slider').each(function(){$(this).slider();});var $nextText;$(document).on('click','[data-wizard]',function(e){var $this=$(this),href;var $target=$($this.attr('data-target')||(href=$this.attr('href'))&&href.replace(/.*(?=#[^\s]+$)/,''));var option=$this.data('wizard');var item=$target.wizard('selectedItem');var $step=$target.next().find('.step-pane:eq('+(item.step-1)+')');!$nextText&&($nextText=$('[data-wizard="next"]').html());if($(this).hasClass('btn-next')&&$step.find('input, select, textarea').data('required')&&!$step.find('input, select, textarea').parsley('validate')){return false;}else{$target.wizard(option);var activeStep=(option=="next")?(item.step+1):(item.step-1);var prev=($(this).hasClass('btn-prev')&&$(this))||$(this).prev();var next=($(this).hasClass('btn-next')&&$(this))||$(this).next();prev.attr('disabled',(activeStep==1)?true:false);next.html((activeStep<$target.find('li').length)?$nextText:next.data('last'));}});if($.fn.sortable){$('.sortable').sortable();}
$('.no-touch .slim-scroll').each(function(){var $self=$(this),$data=$self.data(),$slimResize;$self.slimScroll($data);$(window).resize(function(e){clearTimeout($slimResize);$slimResize=setTimeout(function(){$self.slimScroll($data);},500);});});if($.support.pjax){$(document).on('click','a[data-pjax]',function(event){event.preventDefault();var container=$($(this).data('target'));$.pjax.click(event,{container:container});})};$('.portlet').each(function(){$(".portlet").sortable({connectWith:'.portlet',iframeFix:false,items:'.portlet-item',opacity:0.8,helper:'original',revert:true,forceHelperSize:true,placeholder:'sortable-box-placeholder round-all',forcePlaceholderSize:true,tolerance:'pointer'});});$('#docs pre code').each(function(){var $this=$(this);var t=$this.html();$this.html(t.replace(/</g,'&lt;').replace(/>/g,'&gt;'));});$(document).on('click','.fontawesome-icon-list a',function(e){e&&e.preventDefault();});$(document).on('change','table thead [type="checkbox"]',function(e){e&&e.preventDefault();var $table=$(e.target).closest('table'),$checked=$(e.target).is(':checked');$('tbody [type="checkbox"]',$table).prop('checked',$checked);});$(document).on('click','[data-toggle^="progress"]',function(e){e&&e.preventDefault();$el=$(e.target);$target=$($el.data('target'));$('.progress',$target).each(function(){var $max=50,$data,$ps=$('.progress-bar',this).last();($(this).hasClass('progress-xs')||$(this).hasClass('progress-sm'))&&($max=100);$data=Math.floor(Math.random()*$max)+'%';$ps.css('width',$data).attr('data-original-title',$data);});});function addMsg($msg){var $el=$('.nav-user'),$n=$('.count:first',$el),$v=parseInt($n.text());$('.count',$el).fadeOut().fadeIn().text($v+1);$($msg).hide().prependTo($el.find('.list-group')).slideDown().css('display','block');}
var $msg='<a href="#" class="media list-group-item">'+'<span class="pull-left thumb-sm text-center">'+'<i class="fa fa-envelope-o fa-2x text-success"></i>'+'</span>'+'<span class="media-body block m-b-none">'+'Sophi sent you a email<br>'+'<small class="text-muted">1 minutes ago</small>'+'</span>'+'</a>';setTimeout(function(){addMsg($msg);},1500);$('[data-ride="datatables"]').each(function(){var oTable=$(this).dataTable({"bProcessing":true,"sAjaxSource":"js/data/datatable.json","sDom":"<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>","sPaginationType":"full_numbers","aoColumns":[{"mData":"engine"},{"mData":"browser"},{"mData":"platform"},{"mData":"version"},{"mData":"grade"}]});});if($.fn.select2){$("#select2-option").select2();$("#select2-tags").select2({tags:["red","green","blue"],tokenSeparators:[","," "]});}});}(window.jQuery);(function(f){jQuery.fn.extend({slimScroll:function(h){var a=f.extend({width:"auto",height:"250px",size:"7px",color:"#000",position:"right",distance:"1px",start:"top",opacity:0.4,alwaysVisible:!1,disableFadeOut:!1,railVisible:!1,railColor:"#333",railOpacity:0.2,railDraggable:!0,railClass:"slimScrollRail",barClass:"slimScrollBar",wrapperClass:"slimScrollDiv",allowPageScroll:!1,wheelStep:20,touchScrollStep:200,borderRadius:"7px",railBorderRadius:"7px"},h);this.each(function(){function r(d){if(s){d=d||window.event;var c=0;d.wheelDelta&&(c=-d.wheelDelta/120);d.detail&&(c=d.detail/3);f(d.target||d.srcTarget||d.srcElement).closest("."+a.wrapperClass).is(b.parent())&&m(c,!0);d.preventDefault&&!k&&d.preventDefault();k||(d.returnValue=!1)}}function m(d,f,h){k=!1;var e=d,g=b.outerHeight()-c.outerHeight();f&&(e=parseInt(c.css("top"))+d*parseInt(a.wheelStep)/100*c.outerHeight(),e=Math.min(Math.max(e,0),g),e=0<d?Math.ceil(e):Math.floor(e),c.css({top:e+"px"}));l=parseInt(c.css("top"))/(b.outerHeight()-c.outerHeight());e=l*(b[0].scrollHeight-b.outerHeight());h&&(e=d,d=e/b[0].scrollHeight*b.outerHeight(),d=Math.min(Math.max(d,0),g),c.css({top:d+"px"}));b.scrollTop(e);b.trigger("slimscrolling",~~e);v();p()}function C(){window.addEventListener?(this.addEventListener("DOMMouseScroll",r,!1),this.addEventListener("mousewheel",r,!1),this.addEventListener("MozMousePixelScroll",r,!1)):document.attachEvent("onmousewheel",r)}function w(){u=Math.max(b.outerHeight()/b[0].scrollHeight*b.outerHeight(),D);c.css({height:u+"px"});var a=u==b.outerHeight()?"none":"block";c.css({display:a})}function v(){w();clearTimeout(A);l==~~l?(k=a.allowPageScroll,B!=l&&b.trigger("slimscroll",0==~~l?"top":"bottom")):k=!1;B=l;u>=b.outerHeight()?k=!0:(c.stop(!0,!0).fadeIn("fast"),a.railVisible&&g.stop(!0,!0).fadeIn("fast"))}function p(){a.alwaysVisible||(A=setTimeout(function(){a.disableFadeOut&&s||(x||y)||(c.fadeOut("slow"),g.fadeOut("slow"))},1E3))}var s,x,y,A,z,u,l,B,D=30,k=!1,b=f(this);if(b.parent().hasClass(a.wrapperClass)){var n=b.scrollTop(),c=b.parent().find("."+a.barClass),g=b.parent().find("."+a.railClass);w();if(f.isPlainObject(h)){if("height"in h&&"auto"==h.height){b.parent().css("height","auto");b.css("height","auto");var q=b.parent().parent().height();b.parent().css("height",q);b.css("height",q)}if("scrollTo"in h)n=parseInt(a.scrollTo);else if("scrollBy"in h)n+=parseInt(a.scrollBy);else if("destroy"in h){c.remove();g.remove();b.unwrap();return}m(n,!1,!0)}}else{a.height="auto"==a.height?b.parent().height():a.height;n=f("<div></div>").addClass(a.wrapperClass).css({position:"relative",overflow:"hidden",width:a.width,height:a.height});b.css({overflow:"hidden",width:a.width,height:a.height});var g=f("<div></div>").addClass(a.railClass).css({width:a.size,height:"100%",position:"absolute",top:0,display:a.alwaysVisible&&a.railVisible?"block":"none","border-radius":a.railBorderRadius,background:a.railColor,opacity:a.railOpacity,zIndex:90}),c=f("<div></div>").addClass(a.barClass).css({background:a.color,width:a.size,position:"absolute",top:0,opacity:a.opacity,display:a.alwaysVisible?"block":"none","border-radius":a.borderRadius,BorderRadius:a.borderRadius,MozBorderRadius:a.borderRadius,WebkitBorderRadius:a.borderRadius,zIndex:99}),q="right"==a.position?{right:a.distance}:{left:a.distance};g.css(q);c.css(q);b.wrap(n);b.parent().append(c);b.parent().append(g);a.railDraggable&&c.bind("mousedown",function(a){var b=f(document);y=!0;t=parseFloat(c.css("top"));pageY=a.pageY;b.bind("mousemove.slimscroll",function(a){currTop=t+a.pageY-pageY;c.css("top",currTop);m(0,c.position().top,!1)});b.bind("mouseup.slimscroll",function(a){y=!1;p();b.unbind(".slimscroll")});return!1}).bind("selectstart.slimscroll",function(a){a.stopPropagation();a.preventDefault();return!1});g.hover(function(){v()},function(){p()});c.hover(function(){x=!0},function(){x=!1});b.hover(function(){s=!0;v();p()},function(){s=!1;p()});b.bind("touchstart",function(a,b){a.originalEvent.touches.length&&(z=a.originalEvent.touches[0].pageY)});b.bind("touchmove",function(b){k||b.originalEvent.preventDefault();b.originalEvent.touches.length&&(m((z-b.originalEvent.touches[0].pageY)/a.touchScrollStep,!0),z=b.originalEvent.touches[0].pageY)});w();"bottom"===a.start?(c.css({top:b.outerHeight()-c.outerHeight()}),m(0,!0)):"top"!==a.start&&(m(f(a.start).position().top,null,!0),a.alwaysVisible||c.hide());C()}});return this}});jQuery.fn.extend({slimscroll:jQuery.fn.slimScroll})})(jQuery);


$(document).ready(function($) {

    // Sidebar overlay
	
    var $sidebarOverlay = $(".sidebar-overlay");
    $("#mobile_btn, .task-chat").on("click", function(e) {
        var $target = $($(this).attr("href"));
        if ($target.length) {
            $target.toggleClass("opened");
            $sidebarOverlay.toggleClass("opened");
            $("html").toggleClass("menu-opened");
            $sidebarOverlay.attr("data-reff", $(this).attr("href"));
        }
        e.preventDefault();
    });

    $sidebarOverlay.on("click", function(e) {
        var $target = $($(this).attr("data-reff"));
        if ($target.length) {
            $target.removeClass("opened");
            $("html").removeClass("menu-opened");
            $(this).removeClass("opened");
            $(".main-wrapper").removeClass("slide-nav");
        }
        e.preventDefault();
    });
	
    // Mobile Menu

    if ($('.main-wrapper').length > 0) {
        var $wrapper = $(".main-wrapper");
        $('#mobile_btn').click(function() {
            $wrapper.toggleClass('slide-nav');
            $('#chat_sidebar').removeClass('opened');
            $(".dropdown.open > .dropdown-toggle").dropdown("toggle");
            return false;
        });
        $('#open_msg_box').click(function() {
            $wrapper.toggleClass('open-msg-box');
            $('.themes').removeClass('active');
            $('.dropdown').removeClass('open');
            return false;
        });
    }
	
	// Select 2
	
	if($('.select').length > 0) {
		$('.select').select2({
			minimumResultsForSearch: -1,
			width: '100%'
		});
	}
	
    // Floating Label

    if ($('.floating').length > 0) {
        $('.floating').on('focus blur', function(e) {
            $(this).parents('.form-focus').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
        }).trigger('blur');
    }
	
    // Page wrapper height

    if ($('.page-wrapper').length > 0) {
        var height = $(window).height();
        $(".page-wrapper").css("min-height", height);
    }

    $(window).resize(function() {
        if ($('.page-wrapper').length > 0) {
            var height = $(window).height();
            $(".page-wrapper").css("min-height", height);
        }
    });
	
    // Left Sidebar Scroll

    if ($('.slimscroll').length > 0) {
        $('.slimscroll').slimScroll({
            height: 'auto',
            width: '100%',
            position: 'right',
            size: "7px",
            color: '#ccc',
            wheelStep: 10,
            touchScrollStep: 100
        });
        var hei = $(window).height() - 60;
        $('.slimscroll').height(hei);
        $('.sidebar .slimScrollDiv').height(hei);

        $(window).resize(function() {
            var hei = $(window).height() - 60;
            $('.slimscroll').height(hei);
            $('.sidebar .slimScrollDiv').height(hei);
        });
    }
	
    // Dropdown in Table responsive 

    $('.table-responsive').on('shown.bs.dropdown', function(e) {
        var $table = $(this),
            $dropmenu = $(e.target).find('.dropdown-menu'),
            tableOffsetHeight = $table.offset().top + $table.height(),
            menuOffsetHeight = $dropmenu.offset().top + $dropmenu.outerHeight(true);

        if (menuOffsetHeight > tableOffsetHeight)
            $table.css("padding-bottom", menuOffsetHeight - tableOffsetHeight);
    });
	
    $('.table-responsive').on('hide.bs.dropdown', function() {
        $(this).css("padding-bottom", 0);
    });
	
	$('a[data-toggle="modal"]').on('click',function(){
		setTimeout(function(){ if($(".modal.custom-modal").hasClass('in')){ 
		$(".modal-backdrop").addClass('custom-backdrop');
		$("body").addClass('custom-modal-open');
		
		} },500);
    });
	
	// Multiselect

	if($('#customleave_select').length > 0) {
		$('#customleave_select').multiselect();
	}

	
  $(document).on({
    'show.bs.modal': function() {
      var zIndex = 1040 + (10 * $('.modal:visible').length);
      $(this).css('z-index', zIndex);
      setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      }, 0);
    },
    'hidden.bs.modal': function() {
      if ($('.modal:visible').length > 0) {
        // restore the modal-open class to the body element, so that scrolling works
        // properly after de-stacking a modal.
        setTimeout(function() {
          $(document.body).addClass('modal-open');
        }, 0);
      }
    }
  }, '.modal');
	
	// Leave Settings button show
	
	$(document).on('click', '.leave-edit-btn', function() {
	    var type_form = $(this).data('typ');
		$(this).removeClass('leave-edit-btn').addClass('btn btn-white leave-cancel-btn').text('Cancel');
		$(this).closest("div.leave-right").append('<button class="btn btn-primary leave-save-btn" type="submit" id="'+type_form+'">Save</button>');
		$(this).parent().parent().find("input").prop('disabled', false);
		return false;
	});
	$(document).on('click', '.leave-cancel-btn', function() {
		$(this).removeClass('btn btn-white leave-cancel-btn').addClass('leave-edit-btn').text('Edit');
		$(this).closest("div.leave-right").find(".leave-save-btn").remove();
		$(this).parent().parent().find("input").prop('disabled', true);
		return false;
	});
	
	$(document).on('change', '.leave-box .onoffswitch-checkbox', function() {
		var id = $(this).attr('id').split('_')[1];
		if ($(this).prop("checked") == true) {
			$("#leave_"+id+" .leave-edit-btn").prop('disabled', false);
			$("#leave_"+id+" .leave-action .btn").prop('disabled', false);
		}
	    else {
			$("#leave_"+id+" .leave-action .btn").prop('disabled', true);	
			$("#leave_"+id+" .leave-cancel-btn").parent().parent().find("input").prop('disabled', true);
			$("#leave_"+id+" .leave-cancel-btn").closest("div.leave-right").find(".leave-save-btn").remove();
			$("#leave_"+id+" .leave-cancel-btn").removeClass('btn btn-white leave-cancel-btn').addClass('leave-edit-btn').text('Edit');
			$("#leave_"+id+" .leave-edit-btn").prop('disabled', true);
		}
	});
	
	$('.leave-box .onoffswitch-checkbox').each(function() {
		var id = $(this).attr('id').split('_')[1];
		if ($(this).prop("checked") == true) {
			$("#leave_"+id+" .leave-edit-btn").prop('disabled', false);
			$("#leave_"+id+" .leave-action .btn").prop('disabled', false);
		}
	    else {
			$("#leave_"+id+" .leave-action .btn").prop('disabled', true);	
			$("#leave_"+id+" .leave-cancel-btn").parent().parent().find("input").prop('disabled', true);
			$("#leave_"+id+" .leave-cancel-btn").closest("div.leave-right").find(".leave-save-btn").remove();
			$("#leave_"+id+" .leave-cancel-btn").removeClass('btn btn-white leave-cancel-btn').addClass('leave-edit-btn').text('Edit');
			$("#leave_"+id+" .leave-edit-btn").prop('disabled', true);
		}
	});
	
	// Small Sidebar

	if(screen.width >= 992) {
		$(document).on('click', '#toggle_btn', function() {
			if($('body').hasClass('mini-sidebar')) {
				$('body').removeClass('mini-sidebar');
				$('.subdrop + ul').slideDown();
			} else {
				$('body').addClass('mini-sidebar');
				$('.subdrop + ul').slideUp();
			}
			return false;
		});
		$(document).on('mouseover', function(e) {
			e.stopPropagation();
			if($('body').hasClass('mini-sidebar') && $('#toggle_btn').is(':visible')) {
				var targ = $(e.target).closest('.sidebar').length;
				if(targ) {
					$('body').addClass('expand-menu');
					$('.subdrop + ul').slideDown();
				} else {
					$('body').removeClass('expand-menu');
					$('.subdrop + ul').slideUp();
				}
				return false;
			}
		});
	}
    
});
$(document).ready(function() {
			// $('.approval-option input[type="radio"]').click(function(){
   //              alert('test');
			// 	var inputValue = $(this).attr("value");
			// 	var targetBox = $("." + inputValue);
			// 	$(".approver").not(targetBox).hide();
			// 	$(targetBox).show();
			// });

$(document).on('click','.approval-option input[type="radio"]',function(){

                var inputValue = $(this).attr("value");
                var targetBox = $("." + inputValue);
                $(".approver").not(targetBox).hide();
                $(targetBox).show();
 });
	
	
	//Add Approver
	
	$(".remove_approver").hide();
	  //when the Add Field button is clicked
	$("#add1").click(function(e) {
		var count=$('#count').val();
		var counts=parseInt(count)+1;
		$('#count').val(counts);

    $(".remove_approver").fadeIn("1500");
    //Append a new row of code to the "#items" div
    $("#items").append(
      '<div class="next-approver"><label class="control-label m-b-10" style="padding-left:0">Approver '+ counts +' </label><div class="row"><div class="col-md-10"><select name="offer_approvers[]"id="approver'+counts+'" class="approvers select form-control"><option value="">Select Approver</option></select></div><div class="col-md-2"><a class="remove_approver btn rounded border text-danger"><i class="fa fa-times" aria-hidden="true"></i></a></div></div></div>'
    );

    $.ajax({
        type: "POST",
        url: base_url+"offers/get_approvers",
        data:{id:$(this).val()}, 
        beforeSend :function(){
          $("#approver"+counts+" option:gt(0)").remove(); 
          $('#approver'+counts).find("option:eq(0)").html("Please wait..");
          
        },                         
        success: function (data) {   
           $('#approver'+counts).find("option:eq(0)").html("--Select--");
          var obj=jQuery.parseJSON(data);       
           $(obj).each(function(){
            var option = $('<option />');
            option.attr('value', this.value).text(this.label);           
            $('#approver'+counts).append(option);
          });       
          }
      });


	});


        $(document).on('click','#add2',function(e){		
        var count=$('#count').val();
		var counts=parseInt(count)+1;
		$('#count').val(counts);

    $(".remove_approver").fadeIn("1500");
    //Append a new row of code to the "#items" div
    $("#items1").append(
      '<div class="next-approver"><label class="control-label m-b-10" style="padding-left:0">Approver </label><div class="row"><div class="col-md-10"><select name="expense_approvers[]"id="approver'+counts+'" class="approvers select form-control"><option value="">Select Approver</option></select></div><div class="col-md-2"><a class="remove_approver btn rounded border text-danger"><i class="fa fa-times" aria-hidden="true"></i></a></div></div></div>'
    );

    $.ajax({
        type: "POST",
        url: base_url+"expenses/get_approvers",
        data:{id:$(this).val()}, 
        beforeSend :function(){
          $("#approver"+counts+" option:gt(0)").remove(); 
          $('#approver'+counts).find("option:eq(0)").html("Please wait..");
          
        },                         
        success: function (data) {   
           $('#approver'+counts).find("option:eq(0)").html("--Select--");
          var obj=jQuery.parseJSON(data);       
           $(obj).each(function(){
            var option = $('<option />');
            option.attr('value', this.value).text(this.label);           
            $('#approver'+counts).append(option);
          });       
          }
      });


	});

    $(document).on('click','#add_expense_approvers',function(e){        
    var count=$('#expense_approvers_count').val();
    var counts=parseInt(count)+1;
    $('#expense_approvers_count').val(counts);

    $(".remove_approver").fadeIn("1500");
    //Append a new row of code to the "#items" div
    $("#expense_approvers_items").append(
      '<div class="next-exp-approver"><label class="control-label m-b-10 exp_appr" style="padding-left:0">Approver '+counts+'</label><div class="row"><div class="col-md-10"><select name="expense_approvers[]"id="approver'+counts+'" class="approvers select form-control"><option value="">Select Approver</option></select></div><div class="col-md-2"><a class="remove_exp_approver btn rounded border text-danger"><i class="fa fa-times" aria-hidden="true"></i></a></div></div></div>'
    );

    $.ajax({
        type: "POST",
        url: base_url+"settings/get_designation",
        data:{id:$(this).val()}, 
        beforeSend :function(){
          $("#approver"+counts+" option:gt(0)").remove(); 
          $('#approver'+counts).find("option:eq(0)").html("Please wait..");
          
        },                         
        success: function (data) {   
           $('#approver'+counts).find("option:eq(0)").html("--Select--");
          var obj=jQuery.parseJSON(data);       
           $(obj).each(function(){
            var option = $('<option />');
            option.attr('value', this.value).text(this.label);           
            $('#approver'+counts).append(option);
          });       
          }
      });
    });

   $(document).on('click','#add_leave_approvers',function(e){        
    var count=$('#leave_approvers_count').val();
    var counts=parseInt(count)+1;
    $('#leave_approvers_count').val(counts);

    $(".remove_approver").fadeIn("1500");
    //Append a new row of code to the "#items" div
    $("#leave_approvers_items").append(
      '<div class="next-leave-approver"><label class="control-label m-b-10 leave_appr" style="padding-left:0">Approver '+counts+'</label><div class="row"><div class="col-md-10"><select name="leave_approvers[]"id="approver'+counts+'" class="approvers select form-control"><option value="">Select Approver</option></select></div><div class="col-md-2"><a class="remove_leave_approver btn rounded border text-danger"><i class="fa fa-times" aria-hidden="true"></i></a></div></div></div>'
    );

    $.ajax({
        type: "POST",
        url: base_url+"settings/get_designation",
        data:{id:$(this).val()}, 
        beforeSend :function(){
          $("#approver"+counts+" option:gt(0)").remove(); 
          $('#approver'+counts).find("option:eq(0)").html("Please wait..");
          
        },                         
        success: function (data) {   
           $('#approver'+counts).find("option:eq(0)").html("--Select--");
          var obj=jQuery.parseJSON(data);       
           $(obj).each(function(){
            var option = $('<option />');
            option.attr('value', this.value).text(this.label);           
            $('#approver'+counts).append(option);
          });       
          }
      });


    });

	
	$("body").on("click", ".remove_approver", function(e) {
  
		$(".next-approver").last().remove();
	});

    //Expense remove function starts
    $("body").on("click", ".remove_exp_approver", function(e) {
  
        //$(".next-exp-approver").last().remove();
        $(this).closest('.next-exp-approver').remove();        
        var exp_appr_cnt = $("#expense_approvers_count").val();
        $("#expense_approvers_count").val(parseInt(exp_appr_cnt) - 1);

        approveLabelUpdate();
    });


    $("body").on("click", ".remove_ex_exp_approver", function(e) {

        var id = $(this).attr('data-id');
  
        $(".ex_exp_approvers_"+id).remove();
        var exp_appr_cnt = $("#expense_approvers_count").val();
        $("#expense_approvers_count").val(parseInt(exp_appr_cnt) - 1);
        approveLabelUpdate();
    });

    function approveLabelUpdate(){
        var exp_appr_cnt = $("#expense_approvers_count").val();
        for(var i = 0; i < exp_appr_cnt; i++){
            var approveCnt = parseInt(i) + 1;
            $(".exp_appr:eq( " + i + " )").html("Approver " + approveCnt);
        }
    }

    //Expense remove function ends


    //Leave remove function starts
    $("body").on("click", ".remove_leave_approver", function(e) {
  
        //$(".next-exp-approver").last().remove();
        $(this).closest('.next-leave-approver').remove();        
        var exp_appr_cnt = $("#leave_approvers_count").val();
        $("#leave_approvers_count").val(parseInt(exp_appr_cnt) - 1);

        approveLeaveLabelUpdate();
    });

    $("body").on("click", ".remove_ex_leave_approver", function(e) {

        var id = $(this).attr('data-id');
  
        $(".ex_leave_approvers_"+id).remove();
        var leave_appr_cnt = $("#leave_approvers_count").val();
        $("#leave_approvers_count").val(parseInt(leave_appr_cnt) - 1);
        approveLeaveLabelUpdate();
    });

    function approveLeaveLabelUpdate(){
        var leave_appr_cnt = $("#leave_approvers_count").val();
        for(var i = 0; i < leave_appr_cnt; i++){
            var leaveCnt = parseInt(i) + 1;
            $(".leave_appr:eq( " + i + " )").html("Approver " + leaveCnt);
        }
    }

    //Leave remove function ends

    $("body").on("click", ".remove_create_approver", function(e) {

        var id = $(this).attr('data-id');
  
        $(".create_offer_approvers_"+id).remove();
    });
   
	
	//show table
	});
    $(document).on("change","#job_type",function() {  
        
       var job_type = $('#job_type').val();
       if(job_type == 4){
            $(".base_salary").removeClass("hide");
            $(".hourly_rate").addClass ("hide");
       }else{
            $(".base_salary").addClass("hide");
            $(".hourly_rate").removeClass ("hide");
       }
     
    });

/* Offers Dashboard */
		$(document).ready(function() {
			$('.showTable1').click(function() {
				$('#table-2').hide('slow');
				$('#table-3').hide('slow');
				$('#table-4').hide('slow');
				$('#table-5').hide('slow');
				$('#table-6').hide('slow');
				$('#table-1').toggle('slow');
				 
			});
			$('.showTable2').click(function() {
				$('#table-2').toggle('slow');
				$('#table-3').hide('slow');
				$('#table-4').hide('slow');
				$('#table-5').hide('slow');
				$('#table-6').hide('slow');
				$('#table-1').hide('slow');
			});
			$('.showTable3').click(function() {
				$('#table-3').toggle('slow');
				$('#table-2').hide('slow');
				$('#table-4').hide('slow');
				$('#table-5').hide('slow');
				$('#table-6').hide('slow');
				$('#table-1').hide('slow');
			});
			$('.showTable4').click(function() {
				$('#table-4').toggle('slow');
				$('#table-2').hide('slow');
				$('#table-3').hide('slow');
				$('#table-5').hide('slow');
				$('#table-6').hide('slow');
				$('#table-1').hide('slow');
			});
			$('.showTable5').click(function() {
				$('#table-5').toggle('slow');
				$('#table-2').hide('slow');
				$('#table-3').hide('slow');
				$('#table-4').hide('slow');
				$('#table-6').hide('slow');
				$('#table-1').hide('slow');
			});
			$('.showTable6').click(function() {
				$('#table-6').toggle('slow');
				$('#table-2').hide('slow');
				$('#table-3').hide('slow');
				$('#table-4').hide('slow');
				$('#table-5').hide('slow');
				$('#table-1').hide('slow');
			});



			$(document).on("click","#add_another_goal",function() {
				var count = $("#count").val();
				var teamlead_id = $("#teamlead").val();
				// alert(teamlead_id);
				$("#count").val(parseInt(count)+1);


				var dynamic_div = addGoalContent(count,teamlead_id);
				$(".add-another-goal").before(dynamic_div);
                // alert(dynamic_div);
			});
			
			$("body").on("click", ".goal_remove", function () {
                var count = $("#count").val();
                $("#count").val(count-1);
				$(this).closest(".performance-box").remove();
                var count = $("#count").val();
                var taskkcount = parseInt(count) - 1;
                for (var i = 0; i < taskkcount; i++) {
                    var goalCnt = parseInt(i) + 1;
                    $(".goalCount:eq( " + i + " )").html("Goal " +  goalCnt);
                }
			});
			 
			function addGoalContent(count,teamlead_id) {
				var count_incre = parseInt(count)+1;

				$("#count").val(count_incre);

				$('.taskdetails').data('action',count); 

                if( ratings_value !=''){ 
                     var option = '';
                    var a= 1;
                    for (var i=0; i < ratings_value.length ; i++) {
                        if(ratings_value[i] !=''){ 
                     
                    option += ' <option value="'+ ratings_value[i]+'">'+ratings_value[i]+'</option>';
                 } } } else { 
                       option +=  '<option value="">Ratings Not Found</option>';
                 }

				return '<form class="form-horizontal perform_360"  action="performance_three_sixty/create_360" method="POST"><div class="performance-box">' +
				'<a href="javascript:void(0);" class="goal_remove goals_remove" title="Remove"><i class="fa fa-times"></i></a>' +
					'<div class="table-responsive">' +
						'<table class="table performance-table">' +
							'<thead>' +
								'<tr>' +
									'<th style="min-width: 600px;" class="goalCount">Goal '+count+'</th>' +
									'<th class="text-center">Status</th>' +
									'<th class="text-center" style="width: 120px;">Self Rating</th>' +
									'<th class="text-center" style="width: 120px;">Rating</th>' +
									'<th class="text-center" style="width: 85px;">Feedback</th>' +
								'</tr>' +
							'</thead>' +
							'<tbody>' +
								'<tr>' +
									'<td>' +
										'<div class="" style="margin-bottom:15px;">' +
											
											'<input type="text" class="form-control" name="goals" required>' +
											'<input type="hidden" class="form-control" name="teamlead_id" value="'+teamlead_id+'">' +
										'</div>' +
                                        '<div class="progress m-b-0">' +
                                                        '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="">'+
                                                            '<span class="progress_percentage"></span>' +

                                                        '</div>' +
                                                         '<input type="hidden" class="goal_progress" name="progress"value="">' +
                                                    '</div>' +


									'</td>' +
									'<td class="text-center">' +
									'<input type="hidden" name="status" class="form-control">' +
										'<span class="badge btn-danger">Not Approved</span>' +
									'</td>' +
									'<td>' +
										'<select class="form-control select" name="self_rating">' +
                                        option +

										'</select>' +
									'</td>' +
									'<td>' +
										'<select class="form-control select" name="rating" disabled="disabled">' +
											 option +
										'</select>' +
									'</td>' +
									'<td class="text-center">' +
										'<button class="btn btn-white" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button>' +
									'</td>' +
								'</tr>' +
							'</tbody>' +
							'<tbody>' +
								'<tr>' +
									'<td colspan="6">' +
										'<div class="add-another">' +
											'<a href="javascript:void(0);" class="add_goal_action"><i class="fa fa-plus"></i> Actions</a>' +
										'</div>' +
									'</td>' +
								'</tr>' +
								'<tr>' +
									'<td style="min-width: 600px;">' +
                                                        
                                                       

                                                         '<div class="task-wrapper goal-wrapper">' +
                                                        '<div class="task-list-container">'+
                                                            '<div class="task-list-body">'+
                                                                '<ul class="task-list" id="tasklist">'+
                                                                    '<li class="task">'+
                                                                        '<div class="task-container">'+
                                                                            '<span class="task-action-btn task-check">'+
                                                                                '<span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">'+
                                                                                    '<i class="material-icons">check</i>'+
                                                                                '</span>'+
                                                                            '</span>'+
                                                                            '<input type="text" class="form-control" name="action[]" data-action="action_1" placeholder="Goal Action 1">'+

                                                                            '<span class="task-action-btn task-btn-right">'+
                                                                                '<span class="action-circle large delete-btn" title="Delete Goal Action">'+
                                                                                    '<i class="material-icons">delete</i>'+
                                                                                '</span>'+
                                                                            '</span>'+
                                                                        '</div>'+
                                                                    '</li>'+
                                                                    '<li class="task">'+
                                                                        '<div class="task-container">'+
                                                                            '<span class="task-action-btn task-check">'+
                                                                                '<span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">'+
                                                                                    '<i class="material-icons">check</i>'+
                                                                                '</span>'+
                                                                            '</span>'+
                                                                              '<input type="text" class="form-control" name="action[]" data-action="action_1" placeholder="Goal Action 2">'+
                                                                            '<span class="task-action-btn task-btn-right">'+
                                                                                '<span class="action-circle large delete-btn" title="Delete Goal Action">'+
                                                                                    '<i class="material-icons">delete</i>'+
                                                                                '</span>'+
                                                                            '</span>'+
                                                                       '</div>'+
                                                                    '</li>'+
                                                                '</ul>'+
                                                            '</div>'+
                                                            '<div class="task-list-footer">'+
                                                                '<div class="new-task-wrapper">'+
                                                                    '<textarea class="add-new-goal" placeholder="Enter new goal action here. . ."></textarea>'+
                                                                    '<span class="error-message hidden">You need to enter a goal action first</span>'+
                                                                    '<span class="add-new-task-btn btn add_goal">Add Goal Action</span>'+
                                                                    '<span class="cancel-btn btn close-goal-panel">Close</span>'+
                                                                '</div>'+
                                                           '</div>'+
                                                        '</div>'+
                                                    '</div>' +
                                                    '<div class="notification-popup hide">'+
                                                        '<p>'+
                                                            '<span class="task"></span>'+
                                                            '<span class="notification-text"></span>'+
                                                        '</p>'+
                                                    '</div>'+

                                                    '<div class="m-t-30 text-center">'+
                                                                    '<button class="btn btn-primary" type="submit" id="create_offers_submit">Create 360 Performance</button>'+
                                                                '</div>' +
                                                       
                                                    '</td>' +
								'</tr>' +
							'</tbody>' +
						'</table>' +
					'</div>' +
				'</div>'+
				'</form>'
			
			}

			$(document).on("click",".add_competency",function() {
				var div = $("<tr />");
				var teamlead_id = $("#teamlead").val();
				div.html(KeyResultContent("",teamlead_id));
				$(this).closest("tbody").append(div);
			});
			$("body").on("click", ".key-remove", function () {
				$(this).closest("tr").remove();
			});
				
			function KeyResultContent(value,teamlead_id) {

                if( ratings_value !=''){ 
                     var option = '';
                    var a= 1;
                    for (var i=0; i < ratings_value.length ; i++) {
                        if(ratings_value[i] !=''){ 
                     
                    option += ' <option value="'+ ratings_value[i]+'">'+ratings_value[i]+'</option>';
                 } } } else { 
                       option +=  '<option value="">Ratings Not Found</option>';
                 }

				return '<td>' +
					'<input type="text" class="form-control" name="competencies[]">' +
					'<input type="hidden" class="form-control" name="teamlead_id[]" value="'+teamlead_id+'">' +
					'' +
					'</td>' + 
					'<td>' +
						'<select class="form-control select" name="self_rating[]" >'+
							option +
						'</select>'+
					'</td>' +
					'<td>' +
						'<select class="form-control select" name="rating[]" disabled="disabled">'+
							option +
						'</select>'+
					'</td>' +
					'<td class="text-center">' +
						'<button class="btn btn-white" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button>' +
					'</td>' +
				'<td><a href="javascript:void(0);" class="text-danger key-remove" title="Remove"><i class="fa fa-times"></i></a></td>'
			}


            $(document).on("click",".add_competency_performance",function() {
                var div = $("<tr />");
                div.html(KeyResultContentcompetency());
                $(this).closest("tbody").append(div);
            });
            $("body").on("click", ".key-removecompetency", function () {
                $(this).closest("tr").remove();
            });
                
            function KeyResultContentcompetency() {

               

                return '<td style="padding-left:0;">' +
                    '<input type="text" class="form-control" name="competency[]" required>' +
                    '' +
                    '</td>' + 
                    '<td class="text-center">' +
                        '<textarea style="height: 44px;" rows="4" cols="5" class="form-control" name="definition[]" placeholder="Definition" required></textarea>' +
                    '</td>' +
                    '<td style="padding-right:0;"><a href="javascript:void(0);" class="text-danger btn key-removecompetency" title="Remove"><i class="fa fa-times"></i></a></td>'
            }

			//Smart Goal

			$(document).on("click","#add_smart_goal",function() {
				var count = $("#count").val();
				
				var dynamic_div = addSmartGoalContent(count);
				$(".add-another-goal").append(dynamic_div);
			});
			
			$("body").on("click", ".goals_remove", function () {
				$(this).closest(".performance-box").remove();
				var count = $("#count").val();
				var taskkcount = parseInt(count) - 1;
                for (var i = 0; i < taskkcount; i++) {
                    var goalCnt = parseInt(i) + 1;
                    $(".goalCount:eq( " + i + " )").html("Goal " +  goalCnt);
                }

				// $("#count").val(taskkcount);
				
			});
			 
			function addSmartGoalContent(count) {
                var goal_count = 'Goal '+count;
				var count_incre = parseInt(count)+1;

				$("#count").val(count_incre);
				
				var taskcount = $("#task_count").val();
				var task_count = parseInt(taskcount) + 1;
				$("#task_count").val(task_count);


                if( ratings_value !=''){ 
                     var option = '';
                     option += ' <option value="">select</option>';
                    var a= 1;
                    for (var i=0; i < ratings_value.length ; i++) {
                        if(ratings_value[i] !=''){ 
                     
                    option += ' <option value="'+ ratings_value[i]+'">'+ratings_value[i]+'</option>';
                 } } } else { 
                       option +=  '<option value="">Ratings Not Found</option>';
                 }
				

				return '<div class="performance-box">' +
				'<a href="javascript:void(0);" class="goal_remove" title="Remove"><i class="fa fa-times"></i></a>' +
				'<div class="table-responsive">' +
					'<table class="table performance-table">' +
						'<thead>' +
							'<tr>' +
								'<th class="goalCount">'+goal_count+'</th>' +
							     '<th class="text-center">Status</th>' +
								'<th class="text-center">Start </th>' +
								'<th class="text-center">Completed </th>' +
                                '<th class="text-center">Rating </th>' +
                                '<th class="text-center">Feedback </th>' +
								
							'</tr>' +
						'</thead>' +
					
						'<tbody>' +
							'<tr>' +
								'<td style="width: 400px;">'+
									'<div class="form-group">' +
										'<input type="text" class="form-control" data-goalid="goal_'+count+'" name="goal[]">' +
									'</div>' +
									'<div class="progress m-b-0">' +
										'<div class="progress-bar progress-bar-warning progress_percentage" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">' +
											'</span>' +
										'</div>' +
										' <input type="hidden" class="goal_progress" name="goal_progress[]"value="">' +
									'</div>' +
								'</td>' +
                                '<td>' +
                                        '<select class="form-control select" name="status[]" disabled>' +
                                        '<option value="Approved">Approved</option>'+
                                        '<option value="Pending">Pending</option>'+

                                        '</select>' +
                                    '</td>' +
								
								'<td class="text-center">' +
									'<div class="cal-icon">' +
										'<input type="text" class="form-control datetimepicker" name="created_date[]">' +
									'</div>' +
								'</td>' +
								'<td class="text-center">' +
									'<div class="cal-icon">' +
										'<input type="text" class="form-control datetimepicker" name="completed_date[]">' +
									'</div>' +
								'</td>' +
                                '<td>' +
                                        '<select class="form-control select" name="rating" disabled>' +
                                        option +

                                        '</select>' +
                                    '</td>' +
                                    '<td class="text-center">' +
                                        '<button class="btn btn-white" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button>' +
                                    '</td>' +
								
							'</tr>' +
						'</tbody>' +
						'<tbody>' +
							'<tr>' +
								'<td colspan="6">' +
									'<div class="add-another">' +
										'<a href="javascript:void(0);" class="add_goal_action"><i class="fa fa-plus"></i> Actions</a>' +
									'</div>' +
								'</td>' +
							'</tr>' +
								'<tr>' +
									'<td style="min-width: 600px;">' +
										'<div class="task-wrapper goal-wrapper">' +
											'<div class="task-list-container">' +
												'<div class="task-list-body">' +
													'<ul class="task-list">' +
														'<li class="task">' +
															'<div class="task-container">' +
																'<span class="task-action-btn task-check">' +
																	'<span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">' +
																		'<i class="material-icons">check</i>' +
																	'</span>' +
																'</span>' +
																' <input type="text" class="form-control" name="goal_action['+task_count+'][]"  data-action="action_'+count+'" placeholder="Goal Action 1">' +
																'<span class="task-action-btn task-btn-right">' +
																	'<span class="action-circle large delete-btn" title="Delete Goal Action">' +
																		'<i class="material-icons">delete</i>' +
																	'</span>' +
																'</span>' +
															'</div>' +
														'</li>' +
														'<li class="task">' +
															'<div class="task-container">' +
																'<span class="task-action-btn task-check">' +
																	'<span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">' +
																		'<i class="material-icons">check</i>' +
																	'</span>' +
																'</span>' +
																'<input type="text" class="form-control" name="goal_action['+task_count+'][]" data-action="action_'+count+'" placeholder="Goal Action 2">' +
																'<span class="task-action-btn task-btn-right">' +
																	'<span class="action-circle large delete-btn" title="Delete Goal Action">' +
																		'<i class="material-icons">delete</i>' +
																	'</span>' +
																'</span>' +
															'</div>' +
														'</li>' +
													'</ul>' +
												'</div>' +
												'<div class="task-list-footer">' +
													'<div class="new-task-wrapper">' +
														'<textarea  class="add-new-goal" placeholder="Enter new goal action here. . ."></textarea>' +
														'<span class="error-message hidden">You need to enter a goal action first</span>' +
														'<span class="add-new-task-btn btn add_goal">Add Goal Action</span>' +
														'<span class="cancel-btn btn close-goal-panel">Close</span>' +
													'</div>' +
												'</div>' +
											'</div>' +
										'</div>' +
										'<div class="notification-popup hide">' +
											'<p>' +
												'<span class="task"></span>' +
												'<span class="notification-text"></span>' +
											'</p>' +
										'</div>' +
									'</td>' +
								'</tr>' +
						'</tbody>' +
					'</table>' +
					
					
				'</div>' +
				'</div>'
			
			}
		



			var notificationTimeout;

		//Shows updated notification popup 
		var updateNotification = function(task, notificationText, newClass){
			var notificationPopup = $('.notification-popup ');
			notificationPopup.find('.task').text(task);
			notificationPopup.find('.notification-text').text(notificationText);
			notificationPopup.removeClass('hide success');
			// If a custom class is provided for the popup, add It
			if(newClass)
				notificationPopup.addClass(newClass);
			// If there is already a timeout running for hiding current popup, clear it.
			if(notificationTimeout)
				clearTimeout(notificationTimeout);
			// Init timeout for hiding popup after 3 seconds
			notificationTimeout = setTimeout(function(){
				notificationPopup.addClass('hide');
			}, 3000);
		}

		// Adds a new Task to the todo list 
		var addTask = function(e){ 
			// Get the new task entered by user
			var newTask = e.parent().find('.add-new-goal').val();
			// If new task is blank show error message
			if(newTask == ''){
				e.parent().find('.add-new-goal').addClass('error');
				e.parent().find('.error-message').removeClass('hidden');
			}
			else{
				var todoListScrollHeight = $('.task-list-body').prop('scrollHeight');
				// Make a new task template
				var newTemplate = $(taskTemplate).clone();
				// update the task label in the new template
				newTemplate.find('.task-label').val(newTask);
				newTemplate.find('.taskdetails').val(newTask);                 
				var task_count = $('#task_count').val();
				$('#goal_form').append('<input type="hidden" class="taskdetails" name="goal_action['+task_count+'][]" value="'+newTask+'">');
                $('.perform_360').append('<input type="hidden" class="taskdetails" name="action[]" value="'+newTask+'">');
                $('.update_360').append('<input type="hidden" class="taskdetails" name="action[]" value="'+newTask+'">');

				console.log(task_count);

				// Add new class to the template
				newTemplate.addClass('new');
				// Remove complete class in the new Template in case it is present
				newTemplate.removeClass('completed');
				//Append the new template to todo list
				e.parent().parent().parent().find('.task-list').append(newTemplate);
				// Clear the text in textarea
				$('.add-new-goal').val('');
				// Show notification
				updateNotification(newTask, 'added to list');
				// Smoothly scroll the todo list to the end
				$('.task-list-body').animate({ scrollTop: todoListScrollHeight}, 1000);
			}
		}

		// Closes the panel for entering new tasks & shows the button for opening the panel
		var closeNewTaskPanel = function(e){			 
			e.closest('.add_goal_action').toggleClass('visible');
			e.closest('.new-task-wrapper').toggleClass('visible');
			if(e.closest('.add-new-goal').hasClass('error')){
				e.closest('.add-new-goal').removeClass('error');
				e.closest('.new-task-wrapper .error-message').addClass('hidden');
			}
		}

		// Initalizes HTML template for a given task 
		var taskTemplate = $($('#goal-template').html());

		// Shows panel for entering new tasks
		$(document).on("click",".add_goal_action",function() {
			var current_tr = $(this).closest('tr').next('tr');
			var newTaskWrapperOffset = current_tr.find('.new-task-wrapper').offset().top;
			$(this).toggleClass('visible');
			current_tr.find('.new-task-wrapper').toggleClass('visible');
			// Focus on the text area for typing in new task
			current_tr.find('.add-new-goal').focus();
			// Smoothly scroll to the text area to bring the text are in view
			$('body').animate({ scrollTop: newTaskWrapperOffset}, 1000);
		});

		// Deletes task on click of delete button
		$(document).on('click', '.task-action-btn .delete-btn', function(){			 
			var task = $(this).closest('.task');
			var taskText = task.find('.task-label').text();
			task.remove();
			updateNotification(taskText, ' has been deleted.');
		});


		// Marks a task as complete
/*		$(document).on('click', '.task-action-btn .complete-btn', function(e)
		{
			var task = $(this).closest('.task');
			var taskText = task.find('.task-label').text();
			var newTitle = task.hasClass('completed') ? 'Mark Complete' : 'Mark Incomplete';
			$(this).attr('title', newTitle); 
			task.hasClass('completed') ? updateNotification(taskText, 'marked as Incomplete.') : updateNotification(taskText, ' marked as complete.', 'success');
			task.toggleClass('completed');

			var task_list = $('ul#tasklist li').length
			var task_complete = $('li.completed').length	  		
	  		
   			var percentage = Math.round(((task_complete / task_list) * 100)) +"%";
   		
    		$('.progress_percentage').text(percentage); 
    		$('.goal_progress').val(percentage); 
    		$(".progress-bar-success").css("width", percentage);

		});*/

		// Adds a task on hitting Enter key, hides the panel for entering new task on hitting Esc. key
		$(document).on("keydown",".add-new-goal",function(event) {
			// Get the code of the key that is pressed
			var keyCode = event.keyCode;
			var enterKeyCode = 13;
			var escapeKeyCode = 27;
			// If error message is already displayed, hide it.
			if($(this).hasClass('error')){
				$(this).removeClass('error');
				$(this).parent().find('.error-message').addClass('hidden');
			}
			// If key code is that of Enter Key then call addTask Function
			if(keyCode == enterKeyCode){
				event.preventDefault();
				addTask();
			}
			// If key code is that of Esc Key then call closeNewTaskPanel Function
			else if(keyCode == escapeKeyCode)
				closeNewTaskPanel();

		});
		
		
		
		// Add new task on click of add task button
		//$('.add_goal').click(addTask);
		$(document).on("click",".add_goal",function() {
			
			addTask($(this));
		});

		// Close new task panel on click of close panel button
		//$('.close-goal-panel').click(closeNewTaskPanel);
		$(document).on("click",".close-goal-panel",function() {			 
			closeNewTaskPanel($(this));
		});

		$('.status_changebuttons').click(function(){
			var status=$(this).data('status');
			var offid= $(this).data('offid');
            var offerid= $(this).data('offerid');
			$.ajax({
				url: base_url+'/offers/candidate_approve/',
        dataType:'json',
        type: 'POST',
        data: {'status':status,'offer_tab_id':offid,'offer_id':offerid},
        success: function (data) {
			window.location.reload();
        },
				
				});
			});

        $('.rating_scale').click(function(){
            
            $('.five_rating_scale').val($(this).val());
            });

		});
$(document).on('click','.okr_description_submit',function(){
        
            $("#okr_description").validate({
                ignore: [],
                rules: {
                    description: {
                        required: true
                    }
                },
                messages: {
                    description: {
                        required: "Description must not be empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
            }); 
		
	/* Offers Dashboard End */	


	// Smart Goal create

	  // $(document).on('click','.create_goal_configuration_submit',function(){
	  // 	// alert();
       		

   //          $("#create_goal_configuration").validate({
   //              ignore: [],
   //              rules: {
   //                  rating_scale: {
   //                      required: true
   //                  },
   //                  'rating_value[]': {
   //                      required: true
   //                  },
   //                  'definition[]': {
   //                     required: true,
   //                  }
   //              },
   //              messages: {
   //                  rating_scale: {
   //                      required: "Rating Scale must not be empty"
   //                  },
   //                  'rating_value[]': {
   //                      required: "Rating value must not be empty"
   //                  },
   //                  'definition[]': {
   //                      required: "Definition  date must not be empty"
   //                  }
   //              },
   //              submitHandler: function(form) {
   //                  form.submit();
   //              }
                
   //             });
   //          }); 

	// Smart Goal End




	  $(document).on('click','.create_goal_submit',function(){

       		var count =  $(this).data("count");

            $("#create_goal"+count).validate({
                ignore: [],
                rules: {
                    goal: {
                        required: true
                    },
                    created_date: {
                        required: true
                    },
                    email: {
                       required: true,
                    },
                    rating: {
                        required: true,
                    }
                },
                messages: {
                    goal: {
                        required: "Goal must not be empty"
                    },
                    created_date: {
                        required: "Created date must not be empty"
                    },
                    completed_date: {
                        required: "Completed date must not be empty"
                    },
                    rating: {
                        required : "Rating field must not be empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                //     var goal = $("#goal").val();
                // 	var status = $("#status").val();
                // 	var created_date = $("#created_date").val();
                // 	var completed_date = $("#completed_date").val();
                // 	var rating = $("#rating").val();
                // 	var goal_duration $('input[type=radio][name=goal_duration]:checked').val();
                // 	var goal = $("#goal").val();
                //     $.ajax({  
                //     url:base_url +'smart_goal/create/',
                //     dataType: 'text',  // what to expect back from the PHP script, if anything
                //     cache: false,
                //     contentType: false,
                //     processData: false,
                //     data: form_data,                         
                //     type: 'post',
                //      success:function(data)  
                //      {  
                //           if(data == 'success')
                //           {
                //             toastr.success('smart goal');
                //                setTimeout(function () {
                //                     location.reload();
                //                 }, 1500);
                //            }else{
                //             toastr.error('Upload Failed');
                //                setTimeout(function () {
                //                     location.reload();
                //                 }, 1500);
                //            }
                //      }  
                // });
                }
                
               });
            }); 


			var tasklist = $('ul#tasklist li').length
			var task_completed = $('li.completed').length
	  
			
   			var percentage = Math.round(((task_completed / tasklist) * 100)) +"%";


    		$('.progress_percentage').text(percentage); 

    	function progress_smartgoal(e)
		{
			if($(e).closest('li').hasClass('completed'))
			{
				$(e).closest('li').removeClass('completed');
			}
			else 
			{
				$(e).closest('li').addClass('completed');	
			}
			
			var ul_list = $(e).closest('ul');

			var over_all_task = 0;
			var over_all_completed_task = 0;			 

			ul_list.find('li').each(function()
			{
				if($(this).hasClass('completed'))
				{
					over_all_completed_task = parseInt(over_all_completed_task)+1;
				}
				over_all_task = parseInt(over_all_task)+1;
			});

/*			var task_list = $(e).closest('ul#tasklist li').length
			var task_complete = $(e).closest('li.completed').length	  		  		
   			var percentage = Math.round(((task_complete / task_list) * 100)) +"%"; */

			var percentage = Math.round(((over_all_completed_task / over_all_task) * 100)) +"%"; 

			console.log(" percentage : " + percentage );
   			// console.log(" task_list : " + task_list + " task_complete : " + task_complete + " percentage : " + percentage );
   		
   			//console.log("$(e).closest('tbody')." + $(e).closest('tbody').prev('tbody').find('.progress_percentage').html() );
   			$(e).closest('tbody').prev('tbody').find('.progress_percentage').html(percentage);
			$(e).closest('tbody').prev('tbody').find('.goal_progress').val(percentage);
			$(e).closest('tbody').prev('tbody').find(".progress-bar-success").css("width", percentage); 
			$(e).closest('tbody').prev('tbody').find(".progress-bar-warning").css("width", percentage); 

			 console.log(" over_all_task : " + over_all_task + " over_all_completed_task : " + over_all_completed_task );
		}

		function progress_goal(e)
		{
			if($(e).closest('li').hasClass('completed'))
			{
				$(e).closest('li').removeClass('completed');
			}
			else 
			{
				$(e).closest('li').addClass('completed');	
			}
			
			var ul_list = $(e).closest('ul');

			var over_all_task = 0;
			var over_all_completed_task = 0;			 

			ul_list.find('li').each(function()
			{
				if($(this).hasClass('completed'))
				{
					over_all_completed_task = parseInt(over_all_completed_task)+1;
				}
				over_all_task = parseInt(over_all_task)+1;
			});

			  console.log(" over_all_task : " + over_all_task + " over_all_completed_task : " + over_all_completed_task );

		}
		
 		
		jQuery(function() {
			var datepicker = $('input#req_leave_date_from');

			if (datepicker.length > 0) {
			
			datepicker.datepicker({
			format: "dd-mm-yyyy",
			startDate: new Date()
			});
			}
			var datepicker_to = $('input#req_leave_date_to');

			if (datepicker_to.length > 0) {
			datepicker_to.datepicker({
			format: "dd-mm-yyyy",
			startDate: new Date()
			});
			}

		});


        $(document).on("change",".manager_rating",function() {  
            
            var id      =    $(this).attr('data-id');
            var rating  =    $(this).val();
            $.post(base_url+'performance_three_sixty/manager_rating/',{id:id, rating:rating},function(res){               
                if(res == 'yes'){
                     toastr.success('Rated successfully');
               setTimeout(function () {
                    location.reload();
                }, 1500);
                }else{
                     toastr.error('Somthing went wrong');
                     setTimeout(function () {
                    location.reload();
                }, 1500);
                   

                }
            });
        });

        $(document).on("change",".manager_competence_rating",function() {  
            
            var competencies      =    $(this).attr('data-id');
            var user_id      =    $(this).attr('data-userid');
            var rating  =    $(this).val();
            if(rating != ''){
            $.post(base_url+'performance_three_sixty/manager_competence_rating/',{competencies:competencies,user_id:user_id, rating:rating},function(res){
                if(res == 'yes'){
                     toastr.success('Rated successfully');
               setTimeout(function () {
                    location.reload();
                }, 1500);
                }else{
                     toastr.error('Somthing went wrong');
                     setTimeout(function () {
                    location.reload();
                }, 1500);
                   

                }
            });
        } else {
            alert('Please select  rating');
            return false
        }
        });

        $(document).on("change",".employee_competence_rating",function() {  
            
            var competencies      =    $(this).attr('data-id');
            var self_rating  =    $(this).val();
            if (self_rating != '') {
                $.post(base_url+'performance_three_sixty/employee_competence_rating/',{competencies:competencies, self_rating:self_rating},function(res){
                    
                    if(res == 'yes'){
                         toastr.success('Rated successfully');
                   setTimeout(function () {
                        location.reload();
                    }, 1500);
                    }else{
                         toastr.error('Somthing went wrong');
                         setTimeout(function () {
                        location.reload();
                    }, 1500);
                       

                    }
                });
            }else{
                alert('Please select rating');
                return false
            }
        });

         $(document).on("click",".performance_status",function() {           
            
            var performance_status  =    $(this).attr('data-status');

            if(performance_status != ''){
            
            $.post(base_url+'settings/performance_status/',{performance_status:performance_status},function(res){
                if(res == 'yes'){
                     toastr.success(performance_status +' activated successfully');
               setTimeout(function () {
                    window.location.href= base_url+'settings/?settings=performance&key='+performance_status;
                }, 1500);
                }else{
                     toastr.error('Somthing went wrong');
                     setTimeout(function () {
                    window.location.href= base_url+'settings/?settings=performance&key='+performance_status;
                }, 1500);
                   

                }
            });
        } else{
            toastr.error('Please Select Performance');
                //      setTimeout(function () {
                //     // location.reload();
                // }, 1500);
                   
        }
        });


        $(document).on("click",".office_address",function() {     

            $('#address').val(office_address);  
            $('#city').val(office_city);  
            $('#state').val(office_state);  
            $('#pincode').val(office_zip_code);             
            
        });

        $(document).on("change",".okr_object_status",function() {  
            
            var object_id      =    $(this).attr('data-id');
            var user_id      =    $(this).attr('data-userid');
            var okr_status  =    $(this).val();
            if(okr_status != ''){
            $.post(base_url+'performance/objective_status/',{object_id:object_id,okr_status:okr_status,user_id:user_id},function(res){
                if(res == 'yes'){
                     toastr.success('Status Updated successfully');
               setTimeout(function () {
                    location.reload();
                }, 1000);
                }else{
                     toastr.error('Somthing went wrong');
                     setTimeout(function () {
                    location.reload();
                }, 1000);
                   

                }
            });
        } else {
            alert('Please select  status');
            return false
        }
        });

        $(document).on("change",".okr_result_status",function() {  
            
            var key_id      =    $(this).attr('data-id');
            var user_id      =    $(this).attr('data-userid');
            var key_status  =    $(this).val();
            if(key_status != ''){
            $.post(base_url+'performance/key_status/',{key_id:key_id,key_status:key_status,user_id:user_id},function(res){
                if(res == 'yes'){
                     toastr.success('Status Updated successfully');
               setTimeout(function () {
                    location.reload();
                }, 1000);
                }else{
                     toastr.error('Somthing went wrong');
                     setTimeout(function () {
                    location.reload();
                }, 1000);
                   

                }
            });
        } else {
            alert('Please select  status');
            return false
        }
        });

        $(document).on("change",".okr_object_rating",function() {  
            
            var objective_id      =    $(this).attr('data-id');
            var user_id      =    $(this).attr('data-userid');
            var grade_value  =    $(this).val();
            if(grade_value != ''){
            $.post(base_url+'performance/okr_object_rating/',{objective_id:objective_id,grade_value:grade_value,user_id:user_id},function(res){
                if(res == 'yes'){
                     toastr.success('Grade Updated successfully');
               setTimeout(function () {
                    location.reload();
                }, 1000);
                }else{
                     toastr.error('Somthing went wrong');
                     setTimeout(function () {
                    location.reload();
                }, 1000);
                   

                }
            });
        } else {
            alert('Please select  status');
            return false
        }
        });

          $(document).on("change",".okr_result_rating",function() {  
            
            var result_id      =    $(this).attr('data-id');
            var user_id      =    $(this).attr('data-userid');
            var key_gradeval  =    $(this).val();
            if(key_gradeval != ''){
            $.post(base_url+'performance/okr_result_rating/',{result_id:result_id,key_gradeval:key_gradeval,user_id:user_id},function(res){
                if(res == 'yes'){
                     toastr.success('Grade Updated successfully');
               setTimeout(function () {
                    location.reload();
                }, 1000);
                }else{
                     toastr.error('Somthing went wrong');
                     setTimeout(function () {
                    location.reload();
                }, 1000);
                   

                }
            });
        } else {
            alert('Please select  status');
            return false
        }
        });
     
     