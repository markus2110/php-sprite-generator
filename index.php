<?php

function __autoload($class_name) {
    $class = 'lib/'.$class_name.".php";
    include_once $class;
}


$sourcePath = dirname(__FILE__)."/test_icons/";
$savePath = dirname(__FILE__).'/generated/';

if(!file_exists($savePath)){
  mkdir($savePath, 0777, true);
}


$options = array(

    'SourcePath'  => dirname(__FILE__)."/test_icons/iconset1",

    'SourcePath'  => array(
        dirname(__FILE__)."/test_icons/iconset1",
        dirname(__FILE__)."/test_icons/iconset2",
    ),
    'SavePath'    => $savePath,
    #'SpriteMaxWidth' => 100,
//    'ScanSubDir'  => false,
//    'enableFilters' => false,
//    'SpriteImageOffset' => 0
);

$Sprite = new SpriteGenerator($options);
$Sprite->generate();
//$Sprite->setScanSubDir(true)
//        ->setEnableFilters(true)
//        ->setSpriteMaxWidth(250)
//        ->setSpriteImageOffset(0)
//        ->generate();

?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="generated/mysprite.min.css">
    
    <style>
      *{padding:0px;margin:0px;font-size: 100%}
      body{padding:10px;}
      textarea{width:90%;height:200px}
      
      a{text-decoration: none;padding:5px;}

      .mysprite{
          display: inline-block;
      }
    </style>
    
    <title>PHP Sprite generator</title>
  </head>
  <body>
    <h1>PHP Sprite generator<h1>
    
    <img src="data:image/png;base64,<?php echo base64_encode($Sprite->getSpriteImageSource()) ?>" />
    
    <hr />
    
    <h3>CSS</h3>
    <textarea><?php echo $Sprite->getCssData() ?></textarea>
    <br />

    <hr />

    <?php foreach($Sprite->getSpriteItems() as $className => $props) : ?>
    <button class="btn">
      <?php echo $className ?>
      <i class="<?php echo $Sprite->getCssPrefix() ?> <?php echo str_replace(".", " ", $className) ?>"></i>
    </button>
    <?php endforeach; ?>
    
  </body>
</html>


