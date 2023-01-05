var Showiframe = '';
var operationWidth = 90;
var scrollSetp = 500;
var animatSpeed = 150;

function MenuShow(title){
      
      
      //console.log($("#i_"+title).removeClass("fa-angle-down"));
      
      if($(".i_"+title).hasClass( "fa-angle-down" )){
            $(".i_"+title).removeClass("fa-angle-down").addClass("fa-angle-up");
            
            $("."+title).slideUp();
      } else {
            $(".i_"+title).removeClass("fa-angle-up").addClass("fa-angle-down");
            $("."+title).slideDown("slow");
      }
      
      
      
}


function Menuclear(){
      
      
      if($(window).width() < 1660){
            
            
            
            $("#main").removeClass("col-md-11").addClass("col-md-10");
            
            
      } else {
            
            
            $("#main").removeClass("col-md-10").addClass("col-md-11");
      }
      
      
      if($("#leftmenu").is(':hidden')){
            
            $('#leftmenu').show();
            
            
            
            $("#main").removeClass("col-md-10").removeClass("col-md-12").addClass("col-md-10");
            
            
            
      } else {
            //ml-sm-auto col-lg-11
            
            $('#leftmenu').hide();
            
            
            
            
            
            $("#main").removeClass("col-md-10").removeClass("col-md-11").addClass("col-md-12");
            
            
            
            
            
            
      }
      
}





function topmenu(n,t) {
      
      
      if($("#a"+$.md5(t)).length < 1){
            
            var linkUrl = window.location.protocol + "//" + window.location.host + "/admin.php?adminis=" + n + "&t="+ new Date().getTime();
            
            
            
            if(n == "main"){
                  
                  var iel = "&nbsp;&nbsp;";
                  
                  
            } else {
                  var iel = $("<i>", {
                        "class": "menu-close"
                  }).bind("click",function(){
                        ifclosemenu($.md5(t));
                  });
            }
            
            
            $("<a>", {
                  "id":"a"+$.md5(t),
                  "html": t,
                  "href": "javascript:;",
                  "data-url": linkUrl,
                  "data-value": t
            }).bind("click",function() {
                  //var jthis = $(this);
                  //console.log(jthis.data("url") + "=" + jthis.data("value"));
                  linkframe(linkUrl, t)
            }).append(iel).appendTo("#menu-list");
            
            var iframe = $("<iframe>", {
                  "id":"f"+$.md5(t),
                  "scrolling":"yes",
                  "border":"0",
                  "cellspacing":"0",
                  "frameborder":"no",
                  "class": "iframe-content",
                  "data-url": linkUrl,
                  "data-value": t,
                  "src": linkUrl
            });
            
            $("#page-content").append(iframe);
            
            
            linkframe(linkUrl, t);
            
      } else {
            
            linkframe(linkUrl, t);
      }
      
}


function ifclosemenu(obj){
      
      //console.log($("#menu-list a").length);
      
      if($("#menu-list a").length>0){
            
            var num=0;
            $( "#menu-list a" ).each(function(key,val) {
                  
                  if($(this).text() == $("#a"+obj).text()){
                        
                        num=key;
                        
                        //console.log( key + ": " + $(this).text());
                        
                        return ;
                  }
                  
                  
            });
            
            var datavalue = "";
            var dataurl = "";
            
            if(num==0 && $("#menu-list a").length>1){
                  
                  datavalue = $("#menu-list a").eq(1).attr("data-value");
                  dataurl = $("#menu-list a").eq(1).attr("data-url");
                  
            } else if($("#menu-list a").length == Number(num+1)){
                  
                  
                  datavalue = $("#menu-list a").eq(Number(num-1)).attr("data-value");
                  dataurl = $("#menu-list a").eq(Number(num-1)).attr("data-url");
                  
            } else {
                  
                  datavalue = $("#menu-list a").eq($("#menu-list a").length).attr("data-value");
                  dataurl = $("#menu-list a").eq($("#menu-list a").length).attr("data-url");
            }
            
            //console.log(dataurl + "="+datavalue);
            $("#a"+obj).remove();
            $("#f"+obj).remove();
            
            linkframe(dataurl, datavalue);
            
            
      }
      
}

function linkframe(url, value) {
      if(value == null || typeof(value) == 'undefined'){
            return false;
      }
      
      var id= $.md5(value);
      
      $("#menu-list a.active").removeClass("active");
      
      $("#a"+id).addClass("active").show();
      
      $("iframe").css({"display":"none"});
      
      $("#f"+id).css({"display":"block"});
      
      
      //$('#f'+id).attr('src', url);
      
      
      Showiframe = id;
      
}




function UpdateAdminMaessage(msg) {

	if($("#Maessage").length > 0){
		window.setTimeout(function(){
			$("#Maessage").html(msg);
		},1300);
	}

}



function AdminMaessage(msg) {

	$.blockUI({
              message: "<h5 id='Maessage'><img src=\"images/loading.gif\" align=\"absmiddle\">"+msg+"...</h5>",
              css : {backgroundColor:'#fff',color:'#000'}
	});

}


function AdminMsgClse() {

       window.setTimeout(function(){
              $.unblockUI();
       },1600);

}


function getreloads(){
      var src = $('.iframe-content:visible').attr('data-url');
      

      $('.iframe-content:visible').attr('src',src);

}


function tourl(url) {

	if(url == null || typeof(url) == 'undefined'){
		return false;
	}

	window.location = url;

}



function QuitSystem() {



       if(!window.confirm('確定要登出嗎？')) return false;
      
      window.location = "admin.php?quit=quit&t=" + new Date().getTime() + '';



}



//去除前後(左右)空白
function trim(string) {
    		return string.replace(/(^[\s]*)|([\s]*$)/g, "");
}

//去左空白
function lTrim(string) {
    		return string.replace(/(^[\s]*)/g, "");
}

//去除右空白
function rTrim(string) {
    		return string.replace(/([\s]*$)/g, "");
}

//去除任何空白
function allTrim(string) {
    		return string.replace(/([\s])/g, "");

}



function SetCookie(name,value) {
	expires = new Date();
	expires.setTime(expires.getTime()+(86400*365));
	document.cookie=name+"="+escape(value)+"; expires="+expires.toGMTString()+"; path=/";
}

function DelCookie(name) {
	expires = new Date();
	expires.setTime(expires.getTime()-(86400*365));
	document.cookie=name+"=; expires="+expires.toGMTString()+"; path=/";
}

function FetchCookie(name) {
	var start = document.cookie.indexOf(name);
	var end = document.cookie.indexOf(";",start);
	return start==-1 ? null : unescape(document.cookie.substring(start+name.length+1,(end>start ? end : document.cookie.length)));
}



$(document).ready(function(){

 
 
	$(window).scroll(function(e) {

	});
      
      
      $(window).resize(function(){
            
            
            $(".sidebar-sticky").css({'height':$(window).height() - 56 + 'px'});
            
            $("#main").css({'height':$(window).height() - 56 + 'px'});
      
            $("#page-content").css({'height':$(window).height() - 88 + 'px'});
      
            $("iframe").css({'height':$(window).height() - 88 + 'px'});
            
            
            
            if(!$("#leftmenu").is(':hidden')){
                  
                  if($(window).width() < 1660){
                        
                        $("#leftmenu").removeClass("col-md-1").addClass("col-md-2");
                        
                        $("#main").removeClass("col-md-11").addClass("col-md-10");
                        
                        
                  } else {
                        
                        $("#leftmenu").removeClass("col-md-2").addClass("col-md-1");
                        $("#main").removeClass("col-md-10").addClass("col-md-11");
                  }
            }
            
      });
      
      
      $(".sidebar-sticky").css({'height':$(window).height() - 56 + 'px'});
      
      $("#main").css({'height':$(window).height() - 56 + 'px'});
      
      
      
      $("#page-content").css({'height':$(window).height() - 88 + 'px'});
      
      $("iframe").css({'height':$(window).height() - 88 + 'px'});
      
      
      
      if(!$("#leftmenu").is(':hidden')){
            
            if($(window).width() < 1660){
                  
                  $("#leftmenu").removeClass("col-md-1").addClass("col-md-2");
                  
                  $("#main").removeClass("col-md-11").addClass("col-md-10");
                  
                  
            } else {
                  
                  $("#leftmenu").removeClass("col-md-2").addClass("col-md-1");
                  $("#main").removeClass("col-md-10").addClass("col-md-11");
            }
      }
      
      
      topmenu('main','首頁');
      
      $("#page-prev").bind("click",function() {
            var nav = $("#menu-list");
            var left = parseInt(nav.css("margin-left"));
            if (left !== 0) {
                  nav.animate({
                              "margin-left": (left + scrollSetp > 0 ? 0 : (left + scrollSetp)) + "px"
                        },
                        animatSpeed)
            }
      });
      
      
      $("#page-next").bind("click",function() {
            
            var nav = $("#menu-list");
            var left = parseInt(nav.css("margin-left"));
            var wwidth = parseInt($("#page-tab").width());
            var navwidth = parseInt(nav.width());
            var allshowleft = -(navwidth - wwidth + operationWidth);
            if (allshowleft !== left && navwidth > wwidth - operationWidth) {
                  var temp = (left - scrollSetp);
                  nav.animate({
                              "margin-left": (temp < allshowleft ? allshowleft: temp) + "px"
                        },
                        animatSpeed)
            }
      });
      
});

