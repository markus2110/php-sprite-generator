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
        ->setSpriteMaxWidth(100)
        ->setSpriteImageOffset(0)
        ->setCssMinimize(true)
        ->generate();

$Sprite->getLessData();
#Sprite::dbug($S);
#die;

?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="generated/MySprite.css">
    
    <style>
      textarea{width:90%;height:200px}
    </style>
    
    <title>PHP Sprite generator</title>
  </head>
  <body>
    <h1>PHP Sprite generator<h1>
    
    
    <img src="data:image/png;base64,<?php echo base64_encode($Sprite->getSpriteImageSource()) ?>" />
    
    <hr />
    
    <h3>CSS</h3>
    <textarea><?php echo $Sprite->getCssData() ?></textarea>
    
    <h3>LESS</h3>
    <textarea><?php echo $Sprite->getLessData() ?></textarea>    
    
    
    
    
  </body>
</html>


