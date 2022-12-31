
function Sendclick(){
      var regpassword = /^(?=.*?[A-Z])([\d|a-zA-Z0-9]{6,10})+$/;
      var error=0;
      if($("[name='oldpassword']").val().length < 1){
            error+=1;
            $("[name='oldpassword']").addClass("is-invalid").next(".invalid-feedback").text('未輸入現在密碼');
      }
      
      if($("[name='newpassword']").val().length < 1){
            error+=1;
            $("[name='newpassword']").addClass("is-invalid").next(".invalid-feedback").text('未輸入現在變更密碼');
      } else if(!regpassword.test($("[name='newpassword']").val())){
            error+=1;
            $("[name='newpassword']").addClass("is-invalid").next(".invalid-feedback").text('您輸入變更密碼格式只限英文、數字，最少 6 位最長 10 位，至少一個大寫字母。');
      
      }
      
      if($("[name='confirmpassword']").val().length < 1 ||  $("[name='confirmpassword']").val() != $("[name='newpassword']").val()){
            error+=1;
            $("[name='confirmpassword']").addClass("is-invalid").next(".invalid-feedback").text('變更密碼及確認密碼不一致性');
      }
      
      if(error > 0 ){
            return false;
      }
      
      
      var fd = new FormData();
      $('#validation input, select ,textarea').each(function(){
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
                        fd.append(title,$.trim(val));
                  }
            }
            
      });
      
      
      ShowMeassage("存儲...");
      $.ajax({
            type: 'POST',
            url: '/personal.php?action=Uploadsave',
            data: fd,
            cache:false,
            processData: false,
            contentType: false,
            success: function(data, textStatus, XMLHttpRequest){
                  
                  if(data == 'success'){
                        
                        UpdateMeassage('success','存儲完成',function(){
                              MsgEnd(function(){
                                    window.location.reload();
                              });
                              
                        });
                  } else {
                        
                        UpdateMeassage('cancel',data,function(){
                              MsgEnd();
                        });
                        
                  }
            },error:function (xhr, ajaxOptions, thrownError){
                  
                  if(xhr.status == 404 || xhr.status == 500){
                        MsgEnd();
                        var str='';
                        str+="responseText"+xhr.responseText;
                        str+="\n";
                        
                        str+="status"+xhr.status;
                        str+="\n";
                        
                        str+="readyState"+xhr.readyState;
                        str+="\n";
                        
                        str+="statusText"+xhr.statusText;
                        str+="\n";
                        
                        str+="textStatus"+ajaxOptions;
                        str+="\n";
                        
                        str+="errorThrown"+errorThrown;
                        str+="\n";
                        
                        alert(str);
                        return false;
                  }
            }
      });
}


$(document).ready(function(){
      
      
      $("[name='oldpassword']").click(function() {
            $("[name='oldpassword']").removeClass("is-invalid").next(".invalid-feedback").text('');
      });
      
      $("[name='newpassword']").click(function() {
            $("[name='newpassword']").removeClass("is-invalid").next(".invalid-feedback").text('');
      });
      $("[name='confirmpassword']").click(function() {
            $("[name='confirmpassword']").removeClass("is-invalid").next(".invalid-feedback").text('');
      });
      $.getScript("js/dialog.js?v="+new Date().getTime());
});