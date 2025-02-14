
  $(document).ready(function(){


//time plugin
$('#datetimepicker5').datetimepicker({
    defaultDate: new Date(),
    
     format: 'YYYY-MM-DD hh:mm',
    sideBySide: false,
});
//**************************************************
      $("img.lazy_load").lazyload();
//     $('.slider').bxSlider();
//     **********************************
//        placeHolder search
        $('#search_check').click(function(){
            if($("#search_check").is(':checked')){
           $("#search_check").attr('value', 'true');
             var checkedValue = $('#search_check').val();
              $("#search").attr("placeholder", "search in All");   
          }   
          else{
                $("#search_check").attr('value', 'false');
//            var checkedValue = $('#search_check').val();  
              $("#search").attr("placeholder", "search in "+$("#gallery_list option:selected").text());
                    $("#gallery_list").change(function() {
                  var option_text= $(this).find("option:selected").text();
                  $("#search").attr("placeholder", "search in "+option_text);
                });
          }
        });
//        End placeholder search
//**********************************************************
//      show and hide gallery box
      function showhide()
 {
       var div = document.getElementById("newpost");
if (div.style.display !== "none") {
    div.style.display = "none";

}
else {
    div.style.display = "block";

}
dropList();
 }
   $("#button_Show_Hide").click(showhide);
 function hide(){

     var div = document.getElementById("newpost");
            div.style.display = "none";   
   };
      $("#close").click(hide);
      
//      *********************create new gallery***********************
$('#create_gallery').click(function(e){
         $(".preload").css('display','block');
         e.preventDefault(); // this prevents the form from submitting   
         $.ajax({
                url: '../../gallery/store_gallery',
                type: "post",
                data: {
                      '_token': $('input[name="_token"]').val(),
                      'gallery_name': $('input[name="gallery_name"]').val()
                     },       
               dataType: 'JSON',
               success: function (data) {
//                           console.log(data); 
                      if(data.status=="success"){  
                          $('.msg').empty();
//                          $(".print-error-msg").find("ul").html('');
                          $(".print-error-msg").css('display','none');
                        $('.msg').append("<div class=\"alert alert-success text-center\">Gallery Created successfully</div>");
                        dropList($('input[name="gallery_name"]').val());
                       }
                     if(data.status=="fail"){
                          $('.msg').empty();
                            $(".print-error-msg").css('display','none');
                        $('.msg').append("<div class=\"alert alert-danger text-center\">please write the gallery name</div>");
                        }
                          $(".preload").css('display','none');
                   },
                     error: function(data){
//                    console.log("error");
                }
      });
   
    });
//    ***************************read data in drop list (gallery_list)****************************************************
function dropList($name){
                $.ajax({
                    url: '../../gallery/gallery_list',     
                    type: "GET",
                    dataType: "json",
                    
                    success:function(data) {
//                         console.log(data);
 
                        if(data){
                             $('select[name="gallery_list"]').empty();                       
                              for (var key in data) 
                                {
                                        var obj = data[key];
                                        var id_value= obj['id'];
                                       var title_value=obj['title'];
                 
//                                $("#search").attr("placeholder", "search in "+title_value);
                                 if(title_value==$name) {$('select[name="gallery_list"]').append('<option selected value="'+ id_value +'">'+ title_value +'</option>');}
                                  else{$('select[name="gallery_list"]').append('<option value="'+ id_value +'">'+ title_value +'</option>');}      
                                
//                                  placeHolder search
                              $("#search").attr("placeholder", "search in "+$("#gallery_list option:selected").text());
                                $("#gallery_list").change(function() {
                              var option_text= $(this).find("option:selected").text();
                              $("#search").attr("placeholder", "search in "+option_text);
                            });
                            
                            
                            }
                                
                    }
                }
                });//ajax end 

            } //   end function 
//    ****************************show_images from gallery_images table***************************************************
$('#show_images').click(function(e){
     $(".preload").css('display','block');
      $("#image_block").css('display','none');
       $("#gallery_items_upload").css('display','none');
        $("#delete_images").css('display','none'); 
         e.preventDefault(); 
           var id=$('select[name="gallery_list"]').val()
                       
                $.ajax({
                    url: '../../gallery/show_gallery_Images',     
                    type: "GET",
                    dataType: "json",
                     data: {
                          'id_value':id
                        },    
                    success:function(data) {    
//                         console.log(data);           
               if(data){
                        $('#image_block').empty(); 
                        $('.msg').empty();
                        $(".print-error-msg").css('display','none');
                            var public_path =data.public_path;
//                            if there is no path(no images)
                             if(data.gallery_Images_path.length ===0){
                               $('.msg').append("<div class=\"alert alert-danger text-center\">There is no uploaded images in This gallery</div>");               
                            }                                
                   else{
                        $.each(data.gallery_Images_path, function(index,value) {        
                            var path_image=value.path_image; 
                            var id=value.id;
                             $('#image_block').append('<div id="' + id + '"  style="float: left;display:block;"><div id="images"><img width="141px" height="106px" src='+"../"+"../"+"../"+"public/"+"Gallery/"+path_image+' style="padding-right: 23px;padding-top: 16px;"/></div> <div id="check" ><input id="checked" class="source" type="checkbox"  value="' + id + '" name="check" style="margin-top: 6px;margin-left: 6px;width: 15px;height: 15px;"/></div></div>');      
                                      
                        });
                       }  
                       }
                       $(".preload").css('display','none');
                       $("#image_block").css('display','block');
                       $("#gallery_items_upload").css('display','block');
                       $("#delete_images").css('display','block'); 
                    },
                     error: function(data){
//                      console.log("error");
                       }
                });//ajax end 

             }); //   end function 

//*************************************upload Images*****************************************************
$("body").on("click","#gallery_upload",function(e){
    $(".preload").css('display','block');
    $(".msg").css('display','none');
     $('#image_block').css('display','none');
      $("#gallery_items_upload").css('display','none');
     var id=$('select[name="gallery_list"]').val();
     $(this).parents("form").ajaxForm({
        data: {
            'gallery_id': id
            },
       complete: function(response) 
    {   
       if($.isEmptyObject(response.responseJSON.error)){
                 $('.msg').empty();
                 $(".print-error-msg").css('display','none');
                 $('.msg').append("<div class=\"alert alert-success text-center\" >The Images Uploaded successflly</div>");   

//       make  choose file empty
                document.getElementById("gallery_files").value = "";
//         show images
               $('#image_block').empty();
               $.each(response.responseJSON.path, function(index,value) {        
                    var path_image=value.path_image; 
                    var id=value.id;
                     $('#image_block').append('<div id="' + id + '"  style="float: left;display:block;"><div id="images"><img width="141px" height="106px" src='+"../"+"../"+"../"+"public/"+"Gallery/"+path_image+' style="padding-right: 23px;padding-top: 16px;"/></div> <div id="check" ><input id="checked" class="source" type="checkbox"  value="' + id + '" name="check" style="margin-top: 6px;margin-left: 6px;width: 15px;height: 15px;"/></div></div>');      
                     });
                 $(".preload").css('display','none');
                 $(".msg").css('display','block');
                 $('#image_block').css('display','block');
                 $(".msg").css('display','block');
                 $("#gallery_items_upload").css('display','block');
        }
        else{
                printErrorMsg(response.responseJSON.error);
                $('.msg').empty();
                $(".preload").css('display','none');     
                $(".print-error-msg").css('display','block');
            }
       if($.isEmptyObject(response.responseJSON.path)){
                     $('.msg').empty();
                  $(".msg").css('display','block');   
                    $('.msg').append("<div class=\"alert alert-danger text-center\">There is no uploaded images in This gallery</div>");
        }
                }//end complete
    });                
  });
  function printErrorMsg (msg) {
//       $(".print-error-msg").empty();
	$(".print-error-msg").find("ul").html('');
	$(".print-error-msg").css('display','block');
	$.each( msg, function( key, value ) {
	$(".print-error-msg").find("ul").append('<li>'+value+'</li>');
	});
  }
//  ************************gallery_items************************
 $('#gallery_items_upload').click(function(e){
     $(".preload").css('display','block');
     $("#image_block").css('display','none');
     $(".msg").css('display','none');
     $("#gallery_items_upload").css('display','none');
  
         e.preventDefault(); 
         var item_id=$('input[name="item_id"]').val();
         var item_type=$('input[name="item_type"]').val();
         var checkbox_value = [];
          if($(".source").is(':checked'))
           {
               $(".source:checked").each(function(){
               checkbox_value.push($(this).val()); 
               });
               $('.msg').empty();
                $(".print-error-msg").css('display','none');
                $('.msg').append("<div class=\"alert alert-success text-center\">Add Images in "+"  "  + item_type +"  " +" done succussfuly "+" </div>");
          }
         if(!$(".source").is(':checked'))
         {
          $('.msg').empty();
          $(".print-error-msg").css('display','none');
          $('.msg').append("<div class=\"alert alert-danger text-center\">you must check at least one image</div>");
         checkbox_value=null;
         }
       
           $.ajax({
                url: '../../gallery/store_gallery_items',
                type: "get",
                data: {
                 'gallery_images_id':checkbox_value,
                 'item_id':item_id,
                 'item_type':item_type,
                 },       
                dataType: 'JSON',
                success: function (data) {
//                  console.log(data); 
                if(data){

                $.each(data.path, function(i, item) {
                    var valuepath=data.path[i];
                    var valueid=data.gallery_item_id[i] ;
//                    alert(valuepath);
//                    alert(valueid);
                        $('#gallery_in_editPage').append('<div id="' + valueid + '"  style="float: left;display:block;"><div id="images"><img class="lazy_load" width="141px" height="106px" src='+"../"+"../"+"../"+"public/"+"Gallery/"+valuepath+' style="padding-right: 23px;padding-top: 16px;"/></div> <div id="check" ><input id="checked" class="check_gallery" type="checkbox"  value="' + valueid + '" name="check" style="margin-top: 6px;margin-left: 6px;width: 15px;height: 15px;"/></div></div>');      
                        $("#delete_btn").css('display','block');
                });      
                }
                    $(".preload").css('display','none');
                    $("#image_block").css('display','block');
                    $(".msg").css('display','block');
                    $("#gallery_items_upload").css('display','block');
                },
                error: function(data){
//                 console.log("error");
                }
              });  
    });
//     ***********************search_images on gallery*********************************    
 $('#search_images').click(function(e){
     $(".preload").css('display','block');
     $("#gallery_items_upload").css('display','none');
     $("#image_block").css('display','none');
         e.preventDefault(); 
         var id=$('select[name="gallery_list"]').val();
         var search= $('input[name="search"]').val();
         if($("#search_check").is(':checked')){
            $("#search_check").attr('value', 'true');
             var checkedValue = $('#search_check').val();
          }
        else{
          $("#search_check").attr('value', 'false');
            var checkedValue = $('#search_check').val();   
         }
         
      $.ajax({
        url: '../../gallery/search_gallery',     
        type: "GET",
        dataType: "json",
        data: {
              'gallery_id':id,
               'search' :search,
               'checkedValue':checkedValue,
            },  
        success:function(data) {
//             console.log(data);
                if(data){
                  $('#image_block').empty();  
                  var public_path =data.public_path;
                  $.each(data.gallery_Images, function(index,value) {        
                    var path_image=value.path_image; 
                    var id=value.id;
                       $('#image_block').append('<div class="oneImage" style="float: left;"><div id="images"><img width="141px" height="106px" src='+"../"+"../"+"../"+"public/"+"Gallery/"+path_image+' style="padding-right: 23px;padding-top: 16px;"/></div> <div id="check" ><input id="checked" class="source" type="checkbox"  value="' + id + '" name="check" style="margin-top: 6px;margin-left: 6px;width: 15px;height: 15px;"/></div></div>');                             
                  });
                   $(".preload").css('display','none');     
                   $("#gallery_items_upload").css('display','block');
                   $("#image_block").css('display','block');
                }
        },
         error: function(data){
//          console.log("error");
        }
    });//ajax end 

 });// end function

  //  ************************end search_images on  gallery*****************************************
  //  ************************ delete_images on specfic gallery*****************************************
   $('#delete_images').click(function(e){
       $(".preload").css('display','block');
       $("#delete_block").css('display','none');
        $('.delete_error').css('display','none');
       e.preventDefault(); // this prevents the form from submitting
       var checkbox = [];
        if($(".check_gallery").is(':checked'))
        {
            $(".check_gallery:checked").each(function(){
                checkbox.push($(this).val());
                alert(checkbox);
            });
        }
        if(!$(".check_gallery").is(':checked'))
         { $('.delete_error').empty();
            $('.delete_error').append("<div class=\"alert alert-danger text-center\" style='width: 66%;'>you must check at least one image</div>");
              checkbox=null;
//              alert(checkbox);
         }
     
      $.ajax({
        url: '../../gallery/delete_images',
        type: "get",
        data: {
         'gallery_items_id':checkbox,
        },       
        dataType: 'JSON',
        success: function (data) {
          console.log(data); // this is good
          if(data){
                   $('.delete_error').append("<div class=\"alert alert-success text-center\">The selected image is deleted successfully </div>");
                   if($(".check_gallery").is(':checked'))
                  {$.each(checkbox, function( index, value ) {
                               $("#"+value+"").remove();
                  });         
                } 
            }
        $(".preload").css('display','none');
        $("#delete_block").css('display','block');
        $('.delete_error').css('display','block');
        },
             error: function(data){
//            console.log("error");
        }
      });
    });
  //  ************************end delete_images on specfic gallery*****************************************  
  });// end document

 
 

