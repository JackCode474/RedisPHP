
function Sendclick(){
      
      
      
      
      var error = 0;
      
      if($("[name='username']").val().length < 1){
            error+=1;
            $("[name='username']").addClass("is-invalid").next(".invalid-feedback").text('您注册帳號未填寫');
            
      }
      
      
      if($("[name='password']").val().length < 1){
            error+=1;
            $("[name='password']").addClass("is-invalid").next(".invalid-feedback").text('您注册密碼未填寫');
      }
      
      
      if($("[name='email']").val().length < 1){
            error+=1;
            $("[name='email']").addClass("is-invalid").next(".invalid-feedback").text('您注册 email 未填寫');
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
      
      fd.append('step','true');

      
      ShowMeassage("注册驗證中...");
      
      $.ajax({
            type: 'POST',
            url: '/register.php',
            data: fd,
            cache:false,
            processData: false,
            contentType: false,
            success: function(data, textStatus, XMLHttpRequest){
                  
                  if(data == 'success'){
                        
                        UpdateMeassage('success','注册完成',function(){
                              
                              MsgEnd(function(){
                                    window.location.replace('/');
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


const  generateRandomString = (num) => {
      const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      let result1= '';
      const charactersLength = characters.length;
      for ( let i = 0; i < num; i++ ) {
            result1 += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      
      return result1;
}


$(document).ready(function(){
      $("[name='username']").val(generateRandomString(5));
      $("[name='password']").val(generateRandomString(8));
      $("[name='email']").val(generateRandomString(8)+'@test.com');
      
      $.getScript("js/dialog.js?v="+new Date().getTime());
});
