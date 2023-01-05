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


function tourl(url) {
      
      if(url == null || typeof(url) == 'undefined'){
            return false;
      }
      
      
      //$(".nav a").tab();
      window.location = url ;//+ "&t=" + new Date().getTime();
      
}



$(document).ready(function(){
      
      $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });
   
});