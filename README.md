php-sprite-generator
====================

PHP library to generate a sprite image with necessary css classes


Useage
======

Use option setters
-------------------
    $Sprite = new SpriteGenerator();
    $Sprite->setScanSubDir(true);
    $Sprite->setEnableFilters(true);
    $Sprite->setSpriteMaxWidth(500);
    $Sprite->setSpriteImageOffset(1);
    $Sprite->generate();

Method chaining
---------------
    $Sprite = new SpriteGenerator();
    $Sprite->setScanSubDir(true)
    ->setEnableFilters(true);
    ->setSpriteMaxWidth(500);
    ->setSpriteImageOffset(1);
    ->generate();


or set constructor options
---------------------------
    $options = array(
    'SourcePath'  => $sourcePath,
    'SavePath'    => $savePath,
    'ScanSubDir'  => false,
    'enableFilters' => false,
    'SpriteImageOffset' => 0
    );
    $Sprite = new SpriteGenerator($options);
    $Sprite->->generate();