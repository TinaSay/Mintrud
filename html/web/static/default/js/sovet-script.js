
  $(document).ready(function(){
    $(".play").append('<div class="play_btn"></div>')
    $(".play").click(function(){
      $(".group3").colorbox({rel:'group3', transition:"none", width:"80%", height:"80%", open:true, loop:false});
    });
  });
