$(document).ready(function(){

    'use strict';
   
    // dashboard
    $('.toggle-info').click(function (){
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

        if($(this).hasClass('selected'))
            $(this).html('<i class="fa fa-plus"></i>');
        else
        $(this).html('<i class="fa fa-minus"></i>');
    })




    $('[placeholder]').focus(function (){
        $(this).attr('data' , $(this).attr('placeholder'));
        $(this).attr('placeholder', '');

    }).blur(function (){
        $(this).attr('placeholder', $(this).attr('data'));
    });

    // add asterisk on reguired field

    $('.input').each(function (){
        if($(this).attr('required') === "required"){
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    // convert password field to text field on hover

    var passfield=$('#pass');

    $('#show-pass').hover(function(){
       passfield.attr('type','text');
        $(this).css('color','black');

    }, function() {
        passfield.attr('type','password');
    }); 
    
    // confirmation message in button
    $('confirm').click(function (){
         
        return confirm('Are You Sure');
    });
    
    // category view optiona
    $('.cat h3').click(function (){

        $(this).next('.full-view').fadeToggle(200);

    });
    $('.option span').click(function (){
        $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view')==='full'){
            $('.cat .full-view').fadeIn(200);
        } 
        else{
            $('.cat .full-view').fadeOut(200);
        }
    });

});