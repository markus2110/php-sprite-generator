<?php

require_once 'lib/Sprite.php';


$sourcePath = dirname(__FILE__)."/test_icons/";


$S = new Sprite();
$S->setSourceType('PATH');
$S->setSourcePath($sourcePath);
$S->setSavePath(dirname(__FILE__).DS.'css'.DS);
$S->setIncludeSubDir(true);
$S->generate();

#Sprite::dbug($S);
#die;

?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/MySprite.css">
    
    <style>
      html,*{margin:0px;padding:0px;}
      .button{

        border:solid 1px;
      }
      i{display: inline-block;margin-top:5px;}
      
    </style>
    
    
  </head>
  <body>
    
    
    <img src="data:image/png;base64,<?php echo base64_encode($S->getSpriteImageSource()) ?>" />
    
    <hr />
    

    
    
    
  </body>
</html>


