<?php


require_once '../lib/ItemLocator.php';


/**
 * Description of ItemLocatorTest
 *
 * @author Markus Sommerfeld <markus@simpleasthat.de>
 */
class ItemLocatorTest extends PHPUnit_Framework_TestCase{


    const MUST_HAVE_ICONS = 15;


    /**
     * @expectedException InvalidArgumentException
     */
    public function testException()
    {
        $a = new ItemLocator();
    }


    public function testSourcesStringAsArray()
    {
        $sources = "../test_icons/iconset1";
        $a = new ItemLocator($sources);

        $this->assertCount(1, $a->getSources());
        $this->assertArrayHasKey(0, $a->getSources());
    }


    public function testSourcesAsArray()
    {
        $sources = array(
            "../test_icons/iconset1",
            "../test_icons/iconset2"
        );
        $a = new ItemLocator($sources);

        $this->assertCount(2, $a->getSources());
        $this->assertArrayHasKey(1, $a->getSources());
    }


    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage NOT A DIRECTORY
     */
    public function testSourceNotExists()
    {
        $sources = array(
            "../test_icons/iconset1",
            "../test_icons/iconset4"
        );
        $a = new ItemLocator($sources);
    }


    public function testIsItemListIsArray(){
        $sources = array(
            "../test_icons/iconset1",
            "../test_icons/iconset2"
        );
        $a = new ItemLocator($sources);

        $this->assertInternalType('array', $a->getItems());
        
    }

    public function testCountIcons(){
        $sources = array(
            "../test_icons/iconset1",
            "../test_icons/iconset2"
        );
        $a = new ItemLocator($sources);
        $this->assertCount(self::MUST_HAVE_ICONS, $a->getItems());
    }


    public function testContainsBigUserGroupItem(){
        $sources = array(
            "../test_icons/iconset1",
            "../test_icons/iconset2"
        );
        $a = new ItemLocator($sources);
        $this->assertArrayHasKey('.big.user_group', $a->getItems());
    }

}
