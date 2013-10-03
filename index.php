<?php

require_once 'lib/Sprite.php';


$sourcePath = dirname(__FILE__)."/test_icons/";
$savePath = dirname(__FILE__).DS.'generated'.DS;

mkdir($savePath, 0777, true);

$S = new Sprite();
$S->setSourceType('PATH');
$S->setSourcePath($sourcePath);
$S->setSavePath($savePath);
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
      textarea{width:90%;height:250px}
    </style>
    
    
  </head>
  <body>
    
    
    <img src="data:image/png;base64,<?php echo base64_encode($S->getSpriteImageSource()) ?>" />
    
    <hr />
    
    <textarea><?php echo $S->getCssData() ?></textarea>
    

    
    
    
  </body>
</html>


