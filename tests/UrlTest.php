<?php
namespace Wa72\Url\Tests;

use Wa72\Url\Url;

class UrlTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Wa72\Url\Url::equalsQuery()
     */
    public function testEqualsQuery()
    {
        $url1 = Url::create('index.php?a=3&b=5');
        $url2 = new Url('index.php?b=5&a=3');
        $this->assertTrue($url1->equalsQuery($url2));

        $url1 = new Url('index.php?a=3&b=5&c=asdf');
        $url2 = Url::create('index.php?b=5&a=3');
        $this->assertFalse($url1->equalsQuery($url2));
    }

    /**
     * @covers \Wa72\Url\Url::makeAbsolute()
     */
    public function testMakeAbsolute()
    {
        $url1 = new Url('page.php?a=3&b=5');
        $url2 = Url::create('http://www.test.test/index.html');

        $this->assertEquals('http://www.test.test/page.php?a=3&b=5', (string) $url1->makeAbsolute($url2));
    }

    /**
     * @covers \Wa72\Url\Url::buildAbsolutePath()
     */
    public function testBuildAbsolutePath()
    {
        $p1 = 'page2.html';
        $p2 = '/pages/index.html';
        $this->assertEquals('/pages/page2.html', Url::buildAbsolutePath($p1, $p2));

        $p1 = 'page.php';
        $p2 = '/index.html';
        $this->assertEquals('/page.php', Url::buildAbsolutePath($p1, $p2));

        $p1 = 'a/b';
        $p2 = '/c/d/e';
        $this->assertEquals('/c/d/a/b', Url::buildAbsolutePath($p1, $p2));

        $p1 = '../images/1.gif';
        $p2 = '/pages/index.html';
        $this->assertEquals('/images/1.gif', Url::buildAbsolutePath($p1, $p2));

        $p1 = '../images/1.gif';
        $p2 = '/pages/';
        $this->assertEquals('/images/1.gif', Url::buildAbsolutePath($p1, $p2));

        $p1 = 'images/1.gif';
        $p2 = '/index.html';
        $this->assertEquals('/images/1.gif', Url::buildAbsolutePath($p1, $p2));

        $p1 = 'images/1.gif';
        $p2 = '/';
        $this->assertEquals('/images/1.gif', Url::buildAbsolutePath($p1, $p2));
    }

    /**
     * @covers \Wa72\Url\Url::normalizePath()
     */
    public function testNormalizePath()
    {
        $this->assertEquals('foo/bar', Url::normalizePath('./foo/bar'));
        $this->assertEquals('foo/bar', Url::normalizePath('foo/./bar'));
        $this->assertEquals('foo/bar', Url::normalizePath('foo/foo/../bar'));
        $this->assertEquals('foo/bar', Url::normalizePath('foo/asdf/qwer/../../bar'));
        $this->assertEquals('../bar', Url::normalizePath('../bar'));
    }

    /**
     * @covers \Wa72\Url\Url::getFilename()
     */
    public function testGetFilename()
    {
        $url = Url::create('/asdf/index.html');
        $this->assertEquals('index.html', $url->getFilename());

        $url = Url::create('/asdf/');
        $this->assertEquals('', $url->getFilename());

        $url = Url::create('/foo');
        $this->assertEquals('foo', $url->getFilename());

        $url = Url::create('foo');
        $this->assertEquals('foo', $url->getFilename());

        $url = Url::create('foo/');
        $this->assertEquals('', $url->getFilename());
    }
    /**
     * @covers \Wa72\Url\Url::getDirname()
     */
    public function testGetDirname()
    {
        $url = Url::create('/asdf/index.html');
        $this->assertEquals('/asdf', $url->getDirname());

        $url = Url::create('/asdf/');
        $this->assertEquals('/asdf', $url->getDirname());

        $url = Url::create('/foo');
        $this->assertEquals('/', $url->getDirname());

        $url = Url::create('foo');
        $this->assertEquals('.', $url->getDirname());

        $url = Url::create('foo/');
        $this->assertEquals('foo', $url->getDirname());
    }
}