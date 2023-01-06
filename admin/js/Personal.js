




function SendPost(){
      
      var strmsg = 0;
      
      if($("[name='oldpass']").val().length < 1){
            strmsg += 1;
            $("[name='oldpass']").addClass('is-invalid');
      }
      
      
      if($("[name='newpass']").val().length < 1){
            strmsg += 1;
            $("[name='newpass']").addClass('is-invalid');
      }
      
      if($("[name='newpass']").val().length > 0 && $.trim($("[name='newpass']").val()) == $.trim($("[name='oldpass']").val())){
            strmsg += 1;
            $("[name='newpass']").addClass('is-invalid');
            $(".newpass").html("非常抱歉,您的登入密碼及變更密碼是相同的?請重新輸入!");
      }
      
      if(strmsg>0){
            return false;
      }
      
      
      $("[name='SendPost']").prop('disabled', true).html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> 送出中...");
      
      
      var fd = new FormData();
      
      $('.formvalidation input, select ,textarea').each(function(){
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
      
      fd.append('action','EditSave');
      
      $.ajax({
            type: 'POST',
            url: 'admin.php?adminis=Personal',
            data: fd,
            cache:false,
            processData: false,
            contentType: false,
            success: function(data, textStatus, XMLHttpRequest){
                  
                  
                  if(data == 'success'){
                        
                        $("[name='oldpass']").val('');
                        $("[name='newpass']").val('');
                        
                        Notify.alert({
                              title : 'prompt',
                              text : '編輯完成'
                        });
                        
                        $("[name='SendPost']").prop('disabled', false).html("送出");
                        
                  } else {
                        
                        
                        $("[name='SendPost']").prop('disabled', false).html("送出");
                        Notify.alert({
                              title : 'prompt',
                              text : data
                        });
                  }
                  
                  
                  
                  
            },error:function (xhr, ajaxOptions, thrownError){
                  if(xhr.status == 404 || xhr.status == 500){
                        
                        var str ='';
                        
                        str+="responseText:"+xhr.responseText;
                        
                        str+="\n";
                        
                        str+="status:"+xhr.status;
                        
                        str+="\n";
                        
                        str+="readyState:"+xhr.readyState;
                        
                        str+="\n";
                        
                        str+="statusText:"+xhr.statusText;
                        
                        str+="\n";
                        
                        str+="textStatus:"+ajaxOptions;
                        
                        str+="\n";
                        
                        str+="發送異常,請聯絡管理員。";
                        
                        alert(str);
                        
                        $("[name='SendPost']").prop('disabled', false).html("送出");
                        
                        return false;
                        
                  }
            }
      });
      
      
      
}



$(document).ready(function(){
      
      $("[name='oldpass']").click(function(){
            
            $("[name='oldpass']").removeClass('is-invalid');
      });
      
      $("[name='newpass']").click(function(){
            $(".newpass").html("请输入您的更改密码");
            $("[name='newpass']").removeClass('is-invalid');
      });
      
});


