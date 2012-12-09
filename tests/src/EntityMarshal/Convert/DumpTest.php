<?php

namespace EntityMarshal\Convert;

use ConcreteEntity;

require_once dirname(__FILE__) . '/../../../../src/EntityMarshal/Convert/Dump.php';

/**
 * Test class for Dump.
 * Generated by PHPUnit on 2012-09-23 at 21:47:01.
 */
class DumpTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ConcreteEntity
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->object = new ConcreteEntity($this->getSampleDataArray());
    }

    protected function getSampleDataArray()
    {
        return array(
            'isOk' => true,
            'var1' => 'test1',
            'var2' => 'test2',
            'var3' => 'test3',
            'var4' => 'test4',
        );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     *
     */
    public function testConvert()
    {
        $result = $this->object->convert(new Dump);

        $expected = <<<EOE
<pre style='color:#555;'>
<span style='color:#00a;'>ConcreteEntity</span> (5) {
    [<span style='color:#090;'>isOk</span>] <span style='color:#00a;'>boolean</span> (1) => <span style='color:#a00;'>true</span>
    [<span style='color:#090;'>var1</span>] <span style='color:#00a;'>string</span> (5) => <span style='color:#a00;'>"test1"</span>
    [<span style='color:#090;'>var2</span>] <span style='color:#00a;'>string</span> (5) => <span style='color:#a00;'>"test2"</span>
    [<span style='color:#090;'>var3</span>] <span style='color:#00a;'>string</span> (5) => <span style='color:#a00;'>"test3"</span>
    [<span style='color:#090;'>var4</span>] <span style='color:#00a;'>string</span> (5) => <span style='color:#a00;'>"test4"</span>
}
</pre>
EOE;

        $this->assertEquals(trim($expected), $result);
    }

    public function testConvertDefaultMaxDepth()
    {
        $root   = clone $this->object;
        $object = $root;

        for ($i = 0; $i < 10; $i++) {
            $object->set('var3', clone $this->object);
            $object = $object->get('var3');
        }

        $result = $root->convert(new Dump);

        $this->assertContains('... depth limit reached', $result);
    }

    public function testConvertCircularReference()
    {
        $root   = clone $this->object;
        $root->set('var3', clone $this->object);

        $child  = $root->get('var3');
        $child->set('var3', $root);

        $result = $root->convert(new Dump);

        $this->assertContains('... cirular reference omitted', $result);
    }

    public function testConvertArrayValue()
    {
        $root   = clone $this->object;
        $root->set('var3', array(
            'o1'    => clone $this->object,
            'o2'    => clone $this->object,
            'o3'    => clone $this->object,
            'o4'    => new \stdClass(array(
                'p1' => 'string1',
                'p2' => 123.123,
                'p3' => null,
                'p4' => new \stdClass(),
            )),
            'o5'    => null,
        ));

        echo $root->convert(new Dump);
    }


}


