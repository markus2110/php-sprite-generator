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

      .btn{
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        background-color: #F5F5F5;
        background-repeat: repeat-x;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) #B3B3B3;
        border-radius: 4px 4px 4px 4px;
        border-style: solid;
        border-width: 1px;
        color: #333333;
        cursor: pointer;
        display: inline-block;
        font-size: 14px;
        line-height: 20px;
        margin-bottom: 5px;
        padding: 4px 12px;
        text-align: center;
        vertical-align: middle;     
        
        
      }
    .btn i{
      display: inline-block;
      vertical-align: text-top;
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
    <br /><br />
    
    <?php foreach($Sprite->getSpriteItems() as $className => $props) : ?>
    <button class="btn">
      <?php echo $className ?> 
      <i class="<?php echo $Sprite->getCssPrefix() ?> <?php echo str_replace(".", " ", $className) ?>"></i>
    </button>  
    <?php endforeach; ?>
    
  </body>
</html>


