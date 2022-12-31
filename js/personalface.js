
function Sendclick(){
      
      var error = 0;
      
      if($("[name='formFile']").val().length < 1){
            error+=1;
            $("[name='formFile']").addClass("is-invalid").next(".invalid-feedback").text('未選擇圖片');
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

      
      ShowMeassage("上傳 ...");
      
      $.ajax({
            type: 'POST',
            url: '/personal.php?action=ChangeUploadsave',
            data: fd,
            cache:false,
            processData: false,
            contentType: false,
            success: function(data, textStatus, XMLHttpRequest){
                  
                  if(data == 'success'){
                        
                        UpdateMeassage('success','上傳完成',function(){
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


function readURL(input){
      if(input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function (e) {
                  $("#preview_progressbarTW_img").attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
      }
}

$(document).ready(function(){
      $("[name='formFile']").click(function() {
            $("[name='formFile']").removeClass("is-invalid").next(".invalid-feedback").text('');
      });
      $("[name='formFile']").change(function(){
      
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                  alert("只能上傳 : " + fileExtension.join(', ') + " 檔案格式");
                  $(this).after($(this).clone(true)).remove().val('');
                  $(this).val('');
                  return false;
            } else {
      
                  readURL(this);
            
            }
      });
      $.getScript("js/dialog.js?v="+new Date().getTime());
});