/*var dragging = false;
$('#dragBar').mousedown(function(e){
   e.preventDefault();
   dragging = true;
    $(document).mousemove(function(e){
        var mousePos = e.pageX;
        var contentSize = $('#content').width();
        var contentOffset = $('#content').offset().left;

        var cursorPercentage = (mousePos - contentOffset) / contentSize;

        $('#editor').css("width",cursorPercentage*100 + "%");
        $('#preview').css("width",(1 - cursorPercentage) * 100 + "%");
   });
});

$(document).mouseup(function(e){
   if (dragging) 
   {
       var mousePos = e.pageX;
       var contentSize = $('#content').width();
       var contentOffset = $('#content').offset().left;

       var cursorPercentage = (mousePos - contentOffset) / contentSize;

       $('#editor').css("width",cursorPercentage*100 + "%");
       $('#preview').css("width",(1 - cursorPercentage) * 100 + "%");
       $(document).unbind('mousemove');
       dragging = false;
   }
});*/

var toggler = $(".categoryTitle");
// add click event to all toggler
toggler.click(function(){
    // get the next element
    var content = $(this).next();
    // toggle the content
    content.toggle();
    // toggle the plus/minus icon
    $(this).children("i").toggleClass("fa-plus-circle fa-minus-circle");
});
// start all content closed
for (var i = 0; i < toggler.length; i++) {
    $(toggler[i]).next().hide();
}