<?php
/**
 * All VatNumberCheck plugin tests
 */
namespace App\Test\Case;

use Cake\Core\Plugin;
use Cake\TestSuite\TestCase;

class AllVatNumberCheckTest extends TestCase {

/**
 * Suite define the tests for this plugin
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All VatNumberCheck test');

		$path = Plugin::path('VatNumberCheck') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
