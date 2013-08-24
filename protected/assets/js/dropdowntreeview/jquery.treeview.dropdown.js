/* 
 * nsv-ru@ya.ru
 */


$(function(){
    
    (function($){
            $.fn.addtree = function(tvo){
                $('.my_treedit').removeClass('my_treedit');
                $(this).addClass('my_treedit');
                $('.mydtreeviewselected').removeClass('mydtreeviewselected');
                
                $('div.my_treedit').addClass('mydtreeviewselected');
                $(this).parents('div[mydtreeview]').addClass('mydtreeviewselected');
                ii=0;
                mydata='';
                atr='';                                          
                                            
                while ($('.mydtreeviewselected[param'+ii+']').length) { // Прохоимся по параметрам
                    atr=$('.mydtreeviewselected').attr('param'+ii);
                    if (ii>0) {
                        mydata+=',';
                    }
                    mydata+='"'+atr+'":"'+$('.my_treedit').attr(atr)+'"';
                    ii++;
                }
                mydata='{'+mydata+'}'; 
               // $('span').html(mydata); //?????????????????????????????????????????????????????????????????????
                var myjson=JSON.parse(mydata);
                mytype=$('div[mydtreeview='+i+']').attr('tree_type');
                myurl= $('div[mydtreeview='+i+']').attr('tree_url');
                
                //$('div[mydtreeview='+i+']').removeClass('mydtreeviewselected');
               $.ajax({
                   async:false,
                   beforeSend:function(){
                       $('.my_treedit').children('div').addClass('placeholder');
                   },
                   cashe:false,
                  complete:function(){
                      $('.my_treedit div').removeClass('placeholder');
                  },
                  data:myjson,
                  type:mytype,
                  url:myurl,
                  success: function(dat){
                      if ( $('div.my_treedit').length) {
                          $('div.my_treedit').addClass('mydtreeviewvisible');
                          $('div.my_treedit').html(dat);
                          $('div.my_treedit ul').treeview(tvo);
                          $('div.my_treedit').removeClass('mydtreeviewvisible');
                      } else {
                          $('.my_treedit').children('ul').remove();
                          var treeadd = $(dat).appendTo(".my_treedit");
                          $('.my_treedit').treeview({add:treeadd});
                          
                          //$('#my_treedit').attr('id','');
                      }
                  }
               })             
            $('.mydtreeviewselected  span').unbind('click');   
            $('.mydtreeviewselected').removeClass('mydtreeviewselected');            
           }




///////////////////////////////////////////////////////////////////////////////////////////////

/*
  * Плагин к Treeview (http://bassistance.de/jquery-plugins/jquery-plugin-treeview/)
 * 
 * 
 * Загружает и открывает/скрывает выпадающее дерево.
 * Деревое может загружаться полностью или подгружаться частями по технологии AJAX в формате HTML в виде маркированного списка.
 * 
 * 
 * Формат вызова: $(selector).dropdowntreeview(param,option,TreeViewOption)
 *   где:
 *   1.  param - праметры в формате JSON для запросов с помощъю AJAX 
 *        Пример: attr={'my_id':101, 'parent_id':89} то получим HTTP запрос с параметрами  my_id=101&parent_id=89
 *          
 *   2.  option - настройки в формате JSON 
 *        Возможные настройки:
 *                  type - 'post' или 'get' (по умолчиню 'post')
 *                  url - адрес для HTTP запроса дерева (ветки)
 *                  width - ширина поля выбора  (по умолчанию по ширине объекта к которому привязывается дерево)
 *                  height - высота поля выбора (по умолчнаю задана в CSS)
 *       Пример: option={'url'='/tree.php','height'='400px'}
 *    3. TreeViewOption - настройки Treeview (http://docs.jquery.com/Plugins/Treeview/treeview)  
 *    
 *                      
 *    Пример вызова:
 *          $('#mytree').dropdowntreeview({'my_id':101, 'parent_id':89},{'url'='/tree.php','height'='400px'},{'collapsed':true});                  
 *          
 *      в итоге получим получим HTTP запрос по адресу '/tree.php' с параметрами  my_id=101&parent_id=89 
 *      на что сервер должен ответить маркированным списком:
 *      1) в случае загрузки всего дерева полным маркированным списком.
 *      
 *  Пример: 
 *       
 *      <ul>
 *        <li>
 *          <span>Начало дерева</span>
 *          <ul>
 *              <li>
 *                <span>1 пункт</span>
 *             </li>
 *             <li>   
 *                <span>2 пункт</span>                
 *                <ul>
 *                  <li>
 *                      <span>1 подпункт пункта 2</span>
 *                      <span>2 подпункт пункта 2</span>                      
 *                      <span>3 подпункт пункта 2</span>*                      
 *                  </li>
 *                </ul>
 *             </li>
 *             <li>
 *                <span>3 пункт</span>                
 *             </li>   
 *          </ul>
 *        </li>
 *     </ul>   
 *
 *                    
 *   А в случае загрузки дерева частями :
 *                                     
  *      <ul>
 *        <li my_id='102' parent_id='101'>
 *          <span>Начало дерева</span>
 *          <ul class='temp'>
 *             <li class='temp'></li>
 *          </ul>                                           
 *       </li>
 *      </ul>                                                  
 *          
 *  где имена атрибутов тега  <li> равны именам param (праметры в формате JSON для запросов с помощъю AJAX  при инициализации дерева).
 *   Они необходимые для HTTP запроса для загрузки следующей части дерева при разворачивании уже загруженного узла.
 *  В случае вышеуказанного примера, при разворачивании узла 'Начало дерева' пойдёт HTTP запрос на сервер с именами параметров  
 *  указанных при инициализации дерева и значениями из одноименных атрибутов тега <li>.
 *  
 *  Пример HTTP запроса : my_id=102&parent_id=101
 *  
 *  Если пункт списка дерева имеет детей то необходимо добавить HTML констукцию в тег <li> после тега <SPAN> (как в вышеприведённом примере):
 *  
 *          <ul class='temp'>
 *             <li class='temp'></li>
 *          </ul>                                           
 *  
 *  Соответственно если детей нет то такую конструкцию добавлять не нужно.
 *  
 *    
 *    */
       $.fn.undropdowntreeview=function(){
          $(this).each(function(){// Пройдемся по объектам
              if ($(this).hasClass('myTreeView')) { //Если его действительно нужно растреить
                  myind=$(this).next('button.mytreeviewbutton[mybtreeview]').attr('mybtreeview');
                  $('div[mydtreeview='+myind+']').css('display','none');
                  $(this).next('button.mytreeviewbutton[mybtreeview]').css('display','none');
                  $(this).removeAttr('myTreeView');
                  $(this).unbind('click');
//                    $('div[mydtreeview='+myind+']').remove();
//                    $(this).next('button.mytreeviewbutton[mybtreeview]').remove();
              }
          })//$(this).each(function(){// Пройдемся по объектам
       }

       $.fn.dropdowntreeview=function(myattr,myparam,tvo){
           $(this).each(function(){ // Пробегаемся по выбранным объектам
               if ($('this').attr('id')==undefined) { //Если не ID
                   i=0;
                   while ($('button[mybtreeview='+i+']').length) { // Подбор 'хорошего' ID :)
                       i++;
                   }  
                   $('this').attr('id','mytreeeedit'+i).attr('mytreeviewid',i); //Запоминаем ID
               }

            $(this).addClass('myTreeView').attr('readonly','readonly');
            
            $('body').append('<div mydtreeview="'+i+'"></div>');
            $('body').append('<button type="button" class="mytreeviewclose" mydtreeview="'+i+'">▲</button>');
            ii=0;
            if (myattr === undefined) {
                 myparam = {};
            }
            for (p in myattr) { 
                if (p!='toJSONString') {
                $('div[mydtreeview='+i+']').attr(p,myattr[p]);
                $('div[mydtreeview='+i+']').attr('param'+ii,p);                
                ii++;
                }
            }
            if (myparam === undefined) {
                 myparam = {};
            } 
             if (myparam.type === undefined) {
                 myparam.type='POST';             
             }
             if (myparam.url === undefined) {
                 myparam.url=window.location.toString();           
             }

             if (myparam.src === undefined) {
                 myparam.src='';
             }
            
            
            for (p in myparam) {
                $('div[mydtreeview='+i+']').attr('tree_'+p,myparam[p]);
            }            

            $('div[mydtreeview='+i+']').addtree(tvo); 
             
            $(this).after('<button type=button class="mytreeviewbutton" mybtreeview="'+i+'">▼</button>');
            
          
            
            // Позиционирую DIV
            myleft=$(this).offset().left;
            mytop=$(this).offset().top;
            mywidth=$(this).outerWidth();
            myheight=$(this).outerHeight();
                        
            $('div[mydtreeview='+i+']')
                .css('left',myleft)
                .css('top',mytop+myheight)
                .css('min-width',mywidth)
                .css('max-width',mywidth);
                
           if (myparam.width!==undefined) {
                 $('div[mydtreeview='+i+']')
                    .css('width',myparam.width)
                    .css('min-width',myparam.width)
                    .css('max-width',myparam.width);
             }
             
           if (myparam.height!==undefined) {
                 $('div[mydtreeview='+i+']')
                    .css('height',myparam.height)
                    .css('min-height',myparam.height)
                    .css('max-height',myparam.height);
             }
             
           })//$(this).each(function(){ // Пробегаемся по выбранным объектам
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $('div[mydtreeview]').unbind('scroll');
            $('button[mybtreeview]').unbind('click');                       
            $('div[mydtreeview] span').die('dblclick');
            $('div[mydtreeview] span').die('click');
			$('div[mydtreeview]').die('mouseleave');
			$('div[mydtreeview]').die('mouseenter');
			$('.mytreeviewclose').die();
			$('.myTreeView').unbind();
           
           $('div[mydtreeview] span').live('dblclick', function(e){
               ii=0;
                myids=$(this).parents('div[mydtreeview]').attr('mydtreeview');
                $('button[mybtreeview='+myids+']').prev('.myTreeView').val($(this).html());
               while ($(this).parents('div[param'+ii+']').length) { //Пробежка по параметрам
                   myparams=$(this).parents('div[param'+ii+']').attr('param'+ii);
                   myvals=$(this).parent('li').attr(myparams);                  
                   $('button[mybtreeview='+myids+']').prev('.myTreeView').attr(myparams,myvals);
                   ii++;
               }
                $('div[mydtreeview]')
                    .css('display','none')
                    .removeClass('mydtreeviewvisible')
                    .attr('mytreeviewmouse','null');
                $('.mytreeviewclose[mydtreeview='+myids+']').css('display','none');
                $('.mytreeviewbutton[mybtreeview='+myids+']').prev('.myTreeView').focus();
                $('.mytreeviewbutton[mybtreeview='+myids+']').prev('.myTreeView').change();
           })   
           
           $('div[mydtreeview] span').live('click', function(e){
               myinf=$(this).parents('div[mydtreeview]').attr('mydtreeview');
               if ($(this).parents('div[mydtreeview]').attr('tree_oneclick')) {
                   $(this).dblclick();
               } else {
               $('div[mydtreeview='+myinf+'] .mytreeviewselected').removeClass('mytreeviewselected');
                $(this).addClass('mytreeviewselected');
                myleft=$(this).offset().left+$(this).outerWidth();
                mytop=$(this).offset().top;
                
                $('.mytreeviewclose[mydtreeview='+myinf+']')
                  .fadeTo(0,0)
                  .css('left',myleft)
                  .css('top', mytop);
                  //alert($('.mytreeviewclose[mydtreeview='+myinf+']').css('display'));
                if ($('.mytreeviewclose[mydtreeview='+myinf+']').css('display')=='none') {
                    $('.mytreeviewclose[mydtreeview='+myinf+']').css('display','block');
                }
                $('.mytreeviewclose[mydtreeview='+myinf+']')
                  .fadeTo(0,0.7);       
               }
           })           

         $('.collapsable-hitarea').live("click",function(){ // Загрузка по щелчку крестик   
             $('.mytreeviewclose').css('display','none')
           if  ($(this).siblings("ul").first().hasClass('temp')) {
               $(this).parent().addtree();
           }
         })
         
         $('div[mydtreeview]')
            .live('mouseleave',function(){         
                $(this).attr('mytreeviewmouse','leave');
            }).live('mouseenter',function(){
                $(this).attr('mytreeviewmouse','enter');
            })

$('*').click(function(){ //Прячем div если кликнуть мимо него
       
    if (!$(this).hasClass('mytreeviewbutton') & !$(this).hasClass('myTreeView')) {
        
        if  ($('div[mytreeviewmouse!=enter]').length) {        
            myid=$('div[mytreeviewmouse=leave]').attr('mydtreeview');
            $('.mytreeviewclose').css('display','none')
        }

        $('div[mytreeviewmouse=leave]')
                        .css('display','none')
                        .removeClass('mydtreeviewvisible')
                        .attr('mytreeviewmouse','null'); 
    }                    
})
         
$('.mytreeviewclose').live('click',function(){
    myid=$(this).attr('mydtreeview');
    $('div[mydtreeview='+myid+'] .mytreeviewselected').dblclick();
})
         
         $('div[mydtreeview] span').live('mouseover',function(){  // УСТАНОВКА ПОДСВЕТКИ ПУНКТОВ
             $(this).addClass('mytreeviewlights');
         })
         $('div[mydtreeview] span').live('mouseout',function(){ // сНЯТИЕ ПОДСВЕТКИ ПУНКТОВ
             $(this).removeClass('mytreeviewlights');
         })

         $('button[mybtreeview]').click(function(e){ // Клик по кнопке открытия/закрытия TreeView             
             
            myx=$(this).attr('mybtreeview');     
            
            $('div[mydtreeview]').each(function(){//Закрываем чужие TreeView
               if ($(this).attr('mydtreeview')!=myx) {
                   $(this).css('display','none');                   
                   $('button.mytreeviewclose').css('display','none');
               }
            })
            $('button.mytreeviewclose[mydtreeview='+myx+']').css('display','none')
            $('div[mydtreeview='+myx+']').toggle();
            if ($('div[mydtreeview='+myx+']').css('display')=='block'){
                $('div[mydtreeview='+myx+']').attr('mytreeviewmouse','leave')                                
            }          
           e.stopPropagation();         
           $(this).prev('.myTreeView').focus();
         })
         
         $('.myTreeView').click(function(e){              
           $(this).next().click();           
           e.stopPropagation();
         })
         
         
         $('div[mydtreeview]').attr('mydtreeviewfunc','true');
         
         
         $('div[mydtreeview]').scroll(function(){
             $('button.mytreeviewclose').css('display','none');
         })
         
       }//$.fn.myTreeVeiw=function(myatr,myparam){
    })(jQuery);
           
})//$(function(){

