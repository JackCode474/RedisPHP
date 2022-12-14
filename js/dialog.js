

function ClseEnd(){
	$("#ShowMeassage").hide();
	$("#mask").hide();
}







function MsgEnd(callback){
      
      
      if(typeof callback != "undefined" && callback != null){
            window.setTimeout(function(){
            
                  $("#ShowMeassage").hide();
            
                  $("#mask").hide();
            
                  if(typeof  callback != "undefined"){
                        callback();
                  }
            
            },2500);
      } else {
            
            window.setTimeout(function(){
            
                  $("#ShowMeassage").hide();
            
                  $("#mask").hide();
            
            },2500);
      }
      

      
      

}



function Ajaxmask(){

	if($('#mask').length > 0 ){

        	var maskHeight = $(document).height();
        	
        	var maskWidth =  $(document).width();
        	
		$('#mask').css({'width':maskWidth,'height':maskHeight}).show();

	}
}


function ShowXY(){

	if($('#ShowMeassage').length > 0){
	  
			$('#ShowMeassage').css('top',  $(window).height()/2 - $('#ShowMeassage').height()/2 + 'px');

			$('#ShowMeassage').css('left', $(window).width()/2 - $('#ShowMeassage').width()/2 - 12 + 'px');
	}
}



function ShowMeassage(msgtxt){

	if($('#ShowMeassage').length > 0){

                  Ajaxmask();
                  
                  $("#Showmsg").css({'width':$('#ShowMeassage').width() + 'px','height':$('#ShowMeassage').height() +'px'});
                  
                  $("#Showmsg").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> '+msgtxt);
                  
                  $("#ShowMeassage").show();
                  
                  ShowXY();

	}
}





function UpdateMeassage(msgtype,msgtxt, callback){

	if($('#ShowMeassage').length > 0){

		if(msgtype == 'loading'){
                     window.setTimeout(function(){
                     	$("#Showmsg").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> '+msgtxt);
                     },1000);

		} else if(msgtype == 'success'){
                     window.setTimeout(function(){
                     	$("#Showmsg").html('<img src="image/success.png" align="absmiddle"/> '+msgtxt);
                     },1000);
		} else if(msgtype == 'cancel'){
                     window.setTimeout(function(){
                     	$("#Showmsg").html('<img src="image/cancel.png" align="absmiddle"/> '+msgtxt);
                     },1000);
		}
		
		
		if(typeof  callback != "undefined"){
                  callback();
            }
		
	}
}



$(document).ready(function(){



	$(window).resize(function(){
		if($('#mask').length > 0 && $('#mask').is(':visible')){
        		var maskHeight = $(document).height();
        		var maskWidth = $(window).width();
			$('#mask').css({'width':maskWidth,'height':maskHeight});
		}

		if($('#ShowMeassage').length > 0){
	              ShowXY();
		}
	});



	$(window).scroll(function(){
              if($('#ShowMeassage').length > 0){
                 	$('#ShowMeassage').css('top',  $(window).height()/2 - $('#ShowMeassage').height()/2 + 'px');
		}
	});
	
      $(document.body).prepend('<link href="js/dialog.css?v='+new Date().getTime()+'" rel="stylesheet" type="text/css">');

	$(document.body).prepend('<div id="ShowMeassage" class="dialog"><div id="Showmsg"></div></div>');
	
	$(document.body).prepend('<div id="mask" class="mask"></div>');


});
