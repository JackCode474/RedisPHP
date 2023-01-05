//去除前後(左右)空白
function trim(string) {
      return string.replace(/(^[\s]*)|([\s]*$)/g, "");
}


function SendData(){
      
      
      var strmsg = 0;
      
      if($("[name='AdminUSER']").val().length < 1){
            strmsg += 1;
            $("[name='AdminUSER']").addClass('is-invalid');
            $("[name='AdminUSER']").next(".invalid-feedback").text('您登入帳號未填寫');
      }
      
      if($("[name='AdminPW']").val().length < 1){
            strmsg += 1;
            $("[name='AdminPW']").addClass('is-invalid');
            $("[name='AdminPW']").next(".invalid-feedback").text('您登入密碼未填寫');
      }
      
      
      if(strmsg>0){
            return false;
      }
      
      
      $("#SendData").prop('disabled', true).html("<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> 登入中...");
      
      var fd = new FormData();
      $('#main input, select ,textarea').each(function(){
            if(this.type== 'radio' && !this.checked){
                  return;
            } else if(this.type== 'checkbox' && !this.checked){
                  return;
            } else if(this.type == 'file'){
                  fd.append(this.name,$("[name='"+this.name+"']")[0].files[0]);
            } else if(this.type != 'file'){
                  
                  var title = this.name;
                  var val = this.value;
                  if(title.length>0 && val.length>0){
                        fd.append(title,trim(val));
                  }
            }
            
      });
      
      $.ajax({
            type: 'POST',
            url: 'admin.php',
            data: fd,
            cache:false,
            processData: false,
            contentType: false,
            success: function(data, textStatus, XMLHttpRequest){
                  
                  
                  if(data == 'success'){
                        
                        $("#SendData").html("驗證成功,正在登入...");
                        
                        window.setTimeout(function(){
                              window.location.reload();
                        },500);
      
      
                  } else if(data == 'NoAdminUser'){
      
                        $.alert({
                              title: 'Message',
                              content: "不存在帳戶",
                        });
      
                        $("#SendData").prop('disabled', false).html(" 送 出 ");
                  } else {
                        
                        
                        $("#SendData").prop('disabled', false).html(" 送 出 ");
      
      
                        $.alert({
                              title: 'Message',
                              content: data,
                        });
                        
                        
                       // alert('不存在帳戶,請查閱帳戶及密碼是否正確');
                  }
                  
                  
                  
                  
            },error:function (xhr, ajaxOptions, thrownError){
                  if(xhr.status == 404 || xhr.status == 500){
                        $("#SendData").prop('disabled', false).html(" 送 出 ");
                        var str ='';
                        
                        str+="responseText:"+xhr.responseText;
                        
                        str+="<br>";
                        
                        str+="status:"+xhr.status;
                        
                        str+="<br>";
                        
                        str+="readyState:"+xhr.readyState;
                        
                        str+="<br>";
                        
                        str+="statusText:"+xhr.statusText;
                        
                        str+="<br>";
                        
                        str+="textStatus:"+ajaxOptions;
                        
                        str+="<br>";
                        
                        str+="請求主機資料異常，請聯絡管理員。";

                        $.alert({
                              title: 'Message',
                              content: str,
                        });
                        return false;
                        
                  }
            }
      });
}


$(document).ready(function(){
      
      $(window).resize(function(){
            
            $("#main").css({'top':$(window).height()/2 - $('#main').height()/2   + "px",'left':$(window).width()/2 - $('#main').width()/2  + 'px'}).show(500);
     
      });

      
      $("#main").css({'top':$(window).height()/2 - $('#main').height()/2   + "px",'left':$(window).width()/2 - $('#main').width()/2  + 'px'}).show(500);
      
      
      $("#SendData").click(function(){
            SendData();
      });
      
      
      $(".form-signin").keyup(function(event) {
                  if(event.which == 13 ) {
                        SendData();
                  }
      });
      
      $("[name='AdminUSER']").click(function(){
            $("[name='AdminUSER']").removeClass('is-invalid');
            $("[name='AdminUSER']").next(".invalid-feedback").text('');
      });
      
      
      $("[name='AdminPW']").click(function(){
            $("[name='AdminPW']").removeClass('is-invalid');
            $("[name='AdminPW']").next(".invalid-feedback").text('');
      });
      
});