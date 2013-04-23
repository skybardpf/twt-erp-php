$(document).ready(function(){
    $('.shakeitbaby').live('click', function(){
        ShakeItBaby.run(666);
    });
});
window.ShakeItBaby = {
    timer: null,
    run: function(interval) {
        if (this.timer) {
            clearInterval(this.timer);
        } else {
            this.timer = setInterval(function(){
                $('body *').each(function(){
                    var $rnd = Math.random()*8-4;
                    $(this).css({
                        'transform': 'rotate('+$rnd+'deg)'
                    })
                });
            }, interval);
        }
    }
}