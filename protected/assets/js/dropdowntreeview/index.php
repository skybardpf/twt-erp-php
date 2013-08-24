<?php
/*
 * nsv-ru@ya.ru
 */
if (array_key_exists("form", $_POST)) { // Если есть POST то возвращаю дерево    
    if ($_POST['form']=='full') { // Возвращаю дерево целиком
?>
<ul class="filetree">
    <li><span class="file">Пункт1</span></li>
    <li><span class="folder">Пункт2</span>
        <ul>
            <li><span class="file">Подпункт1</span></li>
            <li><span class="file">Подпункт2</span></li>           
            <li class="closed"><span class="folder">Подпункт3</span>
                <ul>
                    <li><span  class="file">Подподпункт1</span></li>
                    <li><span class="file">Подподпункт2</span></li>           
                    <li><span class="file">Подподпункт3</span></li>
                    <li><span class="file">Подподпункт4</span></li>                       
                </ul>
            </li>
            <li><span  class="file">Подпункт4</span></li>                       
        </ul>
    </li>
    <li><span>Пункт3</span></li>
</ul>
<?php
    } //    if ($_POST['type']=='full') { // // Возвращаю дерево целиком
    if ($_POST['form']=='portion') { // Возвращаю дерево частями       
        $x=$_POST['n'];
        $y=$_POST['n']+1;
        $z='<ul class="temp"><li class="temp"></li></ul>';
        //sleep(1);
?>
                <ul>
                    <li form='portion' n='<?php echo $y; ?>'><span>Пункт1-<?php echo $x; ?></span><?php echo $z; ?></li>
                    <li form='portion' n='<?php echo $y; ?>'><span>Пункт2-<?php echo $x; ?></span><?php echo $z; ?></li>
                    <li form='portion' n='<?php echo $y; ?>'><span>Пункт3-<?php echo $x; ?></span></li>                    
                </ul>
<?php
    }//if ($_POST['type']=='portion') { // Возвращаю дерево частями
    
} else { // Если простой запрос то выдаём страничку
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>dropdowntreeview</title>        
        <link href='jquery.treeview.css' type='text/css' rel='stylesheet'/>
        <link href='jquery.treeview.dropdown.css' type='text/css' rel='stylesheet'/> <!--  Мой стиль DropDown-->
        <script type='text/javascript' language='JavaScript'  src='jquery-1.4.2.js' ></script>
        <script type='text/javascript' language='JavaScript'  src='jquery.treeview.js' ></script>
        <script type='text/javascript' language='JavaScript'  src='jquery.treeview.edit.js' ></script>
        <script type='text/javascript' language='JavaScript'  src='jquery.treeview.dropdown.js' ></script> <!--  Мой скрипт DropDown-->       
    </head>
    <body>
        <script type="text/javascript">
        $(function(){
             $('#full').dropdowntreeview({'form':'full'});
             $('#portion').dropdowntreeview({'form':'portion','n':'0'},{'oneclick':'true','height':'600px'},{'collapsed':true});
        })//$(function(){
         </script>
  <style type="text/css">
      body {
          background-color: #dde;
      }
  
   legend { 
       font-size:  small;
       background-color: #fff;
       border: 1px dimgrey outset;
       padding:3px;
       color: #555;
   }
   fieldset {
       background-color: #eee;
       text-align:  center;
       color: navy;  
       padding:10px;       
       max-width: 1000px;      
   }
   pre {
       text-align: left;
       overflow: auto;
       max-height: 200px;
       max-width: 1000px;
   }
   input {
       margin: 10px 0;
       width: 200px;
   }
   .one {
       background-color: #fff;
   }
   .one legend {
       background-color: #eee;
   }
   h1 {
       font-size: x-large;
       color: darkblue;
   }
  </style>         
         <div align="center">
             <h1>Плагин Dropdowntreeview к плагину Treeview библиотеки jQuery</h1>
        <table>
           <tr>
               <td>
                   <fieldset class="one">
                       <legend>Пример - "Полностью подгружаемое дерево" </legend>
                       <input id="full"/>                                          
                   </fieldset>   
               </td>
               <td>
                    <fieldset class="one">
                       <legend>Пример - "'Бесконечное' дерево подгружаемое частями"</legend>
                       <input id="portion"/>                       
                   </fieldset>
               </td>
           </tr>
           <tr>
               <td>
                   <fieldset>
                       <legend>Код HTML</legend>
                       <div align="center">&lt;input id="full"/&gt;</div>
                   </fieldset>   
               </td>
               <td>
                   <fieldset>
                       <legend>Код HTML</legend>
                       <div align="center">&lt;input id="portion"/&gt;</div>
                   </fieldset>                  
               </td>
           </tr>         
           <tr>
               <td>
                   <fieldset>
                       <legend>Код JavaScript</legend>
                       $('#full').dropdowntreeview({'form':'full'});             
                   </fieldset>   
               </td>
               <td>
                   <fieldset>
                       <legend>Код JavaScript</legend>
                       <div align="center">$('#portion').dropdowntreeview({'form':'portion','n':'0'},{'oneclick':'true','height':'600px'},{'collapsed':true});</div>
                   </fieldset>                  
               </td>
           </tr>           
           <tr>
               <td colspan="2">
                   <fieldset>
                       <legend>Подготовительные мероприятия</legend>
                       <pre>
                        &lt;link href='jquery.treeview.css' type='text/css' rel='stylesheet'&gt;
                        &lt;link href='jquery.treeview.dropdown.css' type='text/css' rel='stylesheet'/&gt; &lt;!--  Мой стиль DropDown--&gt;
                        &lt;script type='text/javascript' language='JavaScript'  src='jquery-1.4.2.js'&gt;&lt;/script&gt;
                        &lt;script type='text/javascript' language='JavaScript'  src='jquery.treeview.js'&gt;&lt;/script&gt;
                        &lt;script type='text/javascript' language='JavaScript'  src='jquery.treeview.edit.js'&gt;&lt;/script&gt;
                        &lt;script type='text/javascript' language='JavaScript'  src='jquery.treeview.dropdown.js'&gt;&lt;/script&gt;    &lt;!--  Мой скрипт DropDown--&gt;                
                       </pre>
                   </fieldset>                      
               </td>
           </tr>
           <tr>
               <td colspan="2">
                   <fieldset>
                       <legend>Код примера</legend>
                       <pre>
<?php 
$prim=file('index.php');
foreach ($prim as $line_num => $line) {
    echo htmlspecialchars($line);
}
?>
                       </pre>
                   </fieldset>                      
               </td>
           </tr>  
           <tr>
               <td colspan="2">
                   <fieldset>
                       <legend>Документация</legend>
                       <pre>
<?php
$doc=file('instruction.txt');
foreach ($doc as $line_num => $line) {
    echo htmlspecialchars($line);
}
?>
                       </pre>
                   </fieldset>                      
               </td>
           </tr>
           <tr>
               <td colspan="2">
                   <fieldset>
                       <legend>Скачать</legend>
                       <a href="dropdowntreeview.7z"><h1>Скачать</h1></a>
                   </fieldset>                      
               </td>
           </tr>           
           
        </table>  
             <span>nsv-ru</span>@<span>ya</span>.<span>ru</span> ©
      </div>
    </body>
</html>
<?php
}
?>
