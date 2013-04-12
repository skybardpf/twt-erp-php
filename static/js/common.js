$(document).ready(function(){
    $('.shakeitbaby').live('click', function(){
        ShakeItBaby(666);
    });
});

function ShakeItBaby(timer){
    setInterval(function(){
        $('body *').each(function(){
            var $rnd = Math.random()*4-2;
            $(this).css({
                'transform': 'rotate('+$rnd+'deg)'
            })
        });
    }, timer);
}