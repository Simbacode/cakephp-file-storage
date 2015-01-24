<?php
namespace Burzum\FileStorage\Test\TestCase\Model\Table;

use Cake\Event\Event;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use Burzum\FileStorage\TestSuite\FileStorageTestCase;
use Burzum\FileStorage\Model\Table\ImageStorageTable;

/**
 * Image Storage Test
 *
 * @author Florian Krämer
 * @copyright 2012 - 2015 Florian Krämer
 * @license MIT
 */
class ImageStorageTest extends FileStorageTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Burzum\FileStorage.FileStorage'
	);

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Image = TableRegistry::get('Burzum/FileStorage.ImageStorage');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Image);
		TableRegistry::clear();
	}

/**
 * testProcessVersion
 *
 * @return void
 */
	public function testProcessVersion() {
		/*
		$entity = $this->Image->newEntity([
			'foreign_key' => 'test-1',
			'model' => 'Test',
			'file' => [
				'name' => 'titus.jpg',
				'size' => 332643,
				'tmp_name' => Plugin::path('Burzum/FileStorage') . DS . 'Test' . DS . 'Fixture' . DS . 'File' . DS . 'titus.jpg',
				'error' => 0
			]
		]);
		$result = $this->Image->save($entity);

		$result = $this->Image->find('first', array(
			'conditions' => array(
				'id' => $this->Image->getLastInsertId()
			)
		));

		$this->assertTrue(!empty($result) && is_array($result));
		$this->assertTrue(file_exists($this->testPath . $result['ImageStorage']['path']));

		$path = $this->testPath . $result['ImageStorage']['path'];
		$Folder = new Folder($path);
		$folderResult = $Folder->read();
		$this->assertEquals(count($folderResult[1]), 3);

		Configure::write('FileStorage.imageSizes.Test', array(
			't200' => array(
				'thumbnail' => array(
					'mode' => 'outbound',
					'width' => 200, 'height' => 200
				)
			)
		));
		TableRegistry::get('Burzum/FileStorage.ImageStorage')->generateHashes();

		$Event = new Event('ImageVersion.createVersion', $this->Image, array(
			'record' => $result,
			'storage' => StorageManager::adapter('Local'),
			'operations' => array(
				't200' => array(
					'thumbnail' => array(
						'mode' => 'outbound',
						'width' => 200, 'height' => 200
					)
				)
			)
		));

		CakeEventManager::instance()->dispatch($Event);

		$path = $this->testPath . $result['ImageStorage']['path'];
		$Folder = new Folder($path);
		$folderResult = $Folder->read();
		$this->assertEquals(count($folderResult[1]), 4);


		$Event = new Event('ImageVersion.removeVersion', $this->Image, array(
			'record' => $result,
			'storage' => StorageManager::adapter('Local'),
			'operations' => array(
				't200' => array(
					'thumbnail' => array(
						'mode' => 'outbound',
						'width' => 200, 'height' => 200
					)
				)
			)
		));

		CakeEventManager::instance()->dispatch($Event);

		$path = $this->testPath . $result['ImageStorage']['path'];
		$Folder = new Folder($path);
		$folderResult = $Folder->read();
		$this->assertEquals(count($folderResult[1]), 3);
		*/
	}

/**
 * testValidateImazeSize
 *
 * @return void
 */
	public function testValidateImazeSize() {
		//$this->Image->Behaviors->unload('FileStorage.UploadValidator');

		$this->Image->validate = array(
			'file' => array(
				'image' => array(
					'rule' => array(
						'validateImageSize', array(
							'height' => array('>=', 1000),
							'width' => array('<=', 1000))),
					'message' => 'Invalid image size'
				)
			)
		);

		$this->Image->set(array(
			'file' => array(
				'tmp_name' => Plugin::path('Burzum/FileStorage') . DS . 'Test' . DS . 'Fixture' . DS . 'File' . DS . 'titus.jpg')
			)
		);

		$this->assertFalse($this->Image->validates());


		$this->Image->validate = array(
			'file' => array(
				'image' => array(
					'rule' => array(
						'validateImageSize', array(
							'height' => array('<=', 1000),
							'width' => array('<=', 1000)
						)
					),
					'message' => 'Invalid image size'
				)
			)
		);

		$this->Image->set(array(
			'file' => array(
				'tmp_name' => Plugin::path('Burzum/FileStorage') . DS . 'Test' . DS . 'Fixture' . DS . 'File' . DS . 'titus.jpg')
			)
		);

		$this->assertTrue($this->Image->validates());

		$this->Image->validate = array(
			'file' => array(
				'image' => array(
					'rule' => array(
						'validateImageSize', array(
							'height' => array('>=', 100),
							'width' => array('>=', 100))),
					'message' => 'Invalid image size'
				)
			)
		);

		$this->Image->set(array(
			'file' => array(
				'tmp_name' => Plugin::path('Burzum/FileStorage') . DS . 'Test' . DS . 'Fixture' . DS . 'File' . DS . 'titus.jpg')
			)
		);

		$this->assertTrue($this->Image->validates());


		$this->Image->validate = array(
			'file' => array(
				'image' => array(
					'rule' => array(
						'validateImageSize', array(
							'width' => array('>=', 100))),
					'message' => 'Invalid image size'
				)
			)
		);

		$this->Image->set(array(
			'file' => array(
				'tmp_name' => Plugin::path('Burzum/FileStorage') . DS . 'Test' . DS . 'Fixture' . DS . 'File' . DS . 'titus.jpg')
			)
		);

		$this->assertTrue($this->Image->validates());


		$this->Image->validate = array(
			'file' => array(
				'image' => array(
					'rule' => array(
						'validateImageSize', array(
							'width' => array('==', 512))),
					'message' => 'Invalid image size'
				)
			)
		);

		$this->Image->set(array(
			'file' => array(
				'tmp_name' => Plugin::path('Burzum/FileStorage') . DS . 'Test' . DS . 'Fixture' . DS . 'File' . DS . 'titus.jpg')
			)
		);

		$this->assertTrue($this->Image->validates());
	}
}