<?php
/**
 * Created by SessionManagerTest.php.
 */

namespace Zaraki;


class SessionManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SessionManager
     */
    private $target = null;
    private $session = null;

    public function setUp()
    {
        $this->session = \Phake::mock(Session::class);
        $this->target = new SessionManager($this->session);
    }

    public function testStash()
    {
        $this->target->set('hoge', 'fuga');
        $this->assertSame('fuga', $this->target->get('hoge'));
        $this->assertNull($this->target->get('hoge'));
    }

    public function testPermanent()
    {
        $this->target->setPermanent('hoge', 'fuga');
        $this->assertSame('fuga', $this->target->getPermanent('hoge'));
        $this->assertSame('fuga', $this->target->getPermanent('hoge'));
    }

    public function testRecursivePermanent()
    {
        $this->target->setPermanent('hoge.fuga', 'piyo');
        $this->assertSame([
            '__stash' => [],
            '__session' =>['hoge' => ['fuga' => 'piyo']],
        ], $this->target->getValues());
        $this->assertSame('piyo', $this->target->getPermanent('hoge.fuga'));
    }

    public function testRecursiveStash()
    {
        $this->target->set('hoge.fuga', 'piyo');
        $this->assertSame([
            '__stash' =>  ['hoge' => ['fuga' => 'piyo']],
            '__session' =>[],
        ], $this->target->getValues());
        $this->assertSame('piyo', $this->target->get('hoge.fuga'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRecursiveSetKeyNull()
    {
        $this->target->setRecursive([], null, 'piyo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRecursiveSetKeyEmptyArray()
    {
        $this->target->setRecursive([], [], 'piyo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRecursiveSetKeyArray()
    {
        $this->target->setRecursive([], ['hoge'=>'fuga'], 'piyo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRecursiveSetKeyObject()
    {
        $this->target->setRecursive(['hoge'], new \stdClass(), 'piyo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testQueryKeyNull()
    {
        $this->target->query(['hoge'], null, 'piyo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testQueryKeyEmptyArray()
    {
        $this->target->query(['hoge'], [], 'piyo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testQueryKeyArray()
    {
        $this->target->query(['hoge'], ['hoge'=>'fuga'], 'piyo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testQueryKeyObject()
    {
        $this->target->query(['hoge'], new \stdClass(), 'piyo');
    }
}
