<?php
namespace Everyman\Neo4j;

class PropertyContainerTest extends \PHPUnit_Framework_TestCase
{
	protected $client = null;
	protected $entity = null;

	public function setUp()
	{
		$this->client = $this->getMock('Everyman\Neo4j\Client', array(), array(), '', false);
		$this->entity = $this->getMock('Everyman\Neo4j\PropertyContainer',
			array('delete','save','load'), array($this->client));
	}

	public function testProperties_PropertyNotSet_ReturnsNull()
	{
		$this->assertNull($this->entity->getProperty('notset'));
	}

	public function testProperties_PropertySet_ReturnsValue()
	{
		$this->entity->setProperty('somekey','someval');
		$this->assertEquals('someval', $this->entity->getProperty('somekey'));
	}

	public function testProperties_PropertyRemoved_ReturnsNull()
	{
		$this->entity->setProperty('somekey','someval');
		$this->entity->removeProperty('somekey');
		$this->assertNull($this->entity->getProperty('somekey'));
	}

	public function testProperties_BatchSet_ReturnsValues()
	{
		$this->entity->setProperties(array(
			'somekey' => 'someval',
			'yakey' => 'yaval',
		));
		$this->assertEquals('someval', $this->entity->getProperty('somekey'));
		$this->assertEquals('yaval', $this->entity->getProperty('yakey'));
	}

	public function testProperties_GetAllProperties_ReturnsValues()
	{
		$this->entity->setProperties(array(
			'somekey' => 'someval',
			'yakey' => 'yaval',
		));
		$this->assertEquals(array(
			'somekey' => 'someval',
			'yakey' => 'yaval',
		), $this->entity->getProperties());
	}

	public function testProperties_LazyLoad_OnlyLoadsTheFirstTime()
	{
		$this->entity->expects($this->once())
			->method('load');
		
		$this->entity->setId(123);
		$this->entity->getProperties();
		$this->entity->getProperties();
	}
}
