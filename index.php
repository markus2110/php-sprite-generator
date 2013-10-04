<?php

require_once 'lib/SpriteGenerator.php';


$sourcePath = dirname(__FILE__)."/test_icons/";
$savePath = dirname(__FILE__).DS.'generated'.DS;

if(!file_exists($savePath))
  mkdir($savePath, 0777, true);


$options = array(
    'SourcePath'  => $sourcePath,
    'SavePath'    => $savePath,
    'ScanSubDir'  => false,
    'enableFilters' => false,
    'SpriteImageOffset' => 0
);

$Sprite = new SpriteGenerator($options);
$Sprite->setScanSubDir(true)
        ->setEnableFilters(true)
        ->setSpriteMaxWidth(500)
        ->setSpriteImageOffset(1)
        ->generate();


#Sprite::dbug($S);
#die;

?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="generated/MySprite.css">
    
    <style>
      textarea{width:90%;height:500px}
    </style>
    
    
  </head>
  <body>
    
    
    <img src="data:image/png;base64,<?php echo base64_encode($S->getSpriteImageSource()) ?>" />
    
    <hr />
    
    <textarea><?php echo $S->getCssData() ?></textarea>
    
    
    <div class="MySprite awsukiaw grey"></div>

    
    
    
  </body>
</html>


