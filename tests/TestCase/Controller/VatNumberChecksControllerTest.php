<?php
namespace App\Test\Case\Controller;

use VatNumberCheck\Controller\VatNumberChecksController;

/**
 * VatNumberChecksController Test Case
 *
 * @property VatNumberCheck.VatNumberChecksController $VatNumberChecks
 */
class VatNumberChecksControllerTest extends IntegrationTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		
	];

/**
 * testCheck method
 *
 * @return void
 */
	public function testCheck() {
		// Post request, correct vat

		$VatNumberChecks = $this->generate('VatNumberCheck.VatNumberChecks');
		$VatNumberChecks->VatNumberCheck = TableRegistry::get('VatNumberCheck.VatNumberCheck');

		$data = ['vatNumber' => 'NL820345672B01'];

		$result = $this->get(
			'/vat_number_check/vat_number_checks/check.json',
			['return' => 'contents', 'data' => $data, 'method' => 'post']
		);
		$expected = array_merge($data, ['status' => 'ok']);

		// Test response body
		$this->assertIdentical($expected, json_decode($result, true));

		$result = $VatNumberChecks->response->statusCode();
		$expected = 200;

		// Test response code
		$this->assertIdentical($expected, $result);

		// Get request

		$VatNumberChecks = $this->generate('VatNumberCheck.VatNumberChecks');
		$VatNumberChecks->VatNumberCheck = TableRegistry::get('VatNumberCheck.VatNumberCheck');

		$data = ['vatNumber' => ''];

		$result = $this->get(
			'/vat_number_check/vat_number_checks/check.json',
			['return' => 'contents']
		);
		$expected = array_merge($data, ['status' => 'failure']);

		$this->assertIdentical($expected, json_decode($result, true));

		// Post request, incorrect vat

		$VatNumberChecks = $this->generate('VatNumberCheck.VatNumberChecks');
		$VatNumberChecks->VatNumberCheck = TableRegistry::get('VatNumberCheck.VatNumberCheck');

		$data = ['vatNumber' => 'NL820345672B02'];

		$result = $this->get(
			'/vat_number_check/vat_number_checks/check.json',
			['return' => 'contents', 'data' => $data, 'method' => 'post']
		);
		$expected = array_merge($data, ['status' => 'failure']);

		$this->assertIdentical($expected, json_decode($result, true));

		// Post request, correct vat, timeout

		$VatNumberChecks = $this->generate('VatNumberCheck.VatNumberChecks', [
			'models' => [
				'VatNumberCheck.VatNumberCheck' => ['getUrlContent']
			]
		]);
		$VatNumberChecks->VatNumberCheck->expects($this->any())->method('getUrlContent')->will($this->returnValue(false));

		$data = ['vatNumber' => 'NL820345672B01'];

		$result = $this->get(
			'/vat_number_check/vat_number_checks/check.json',
			['return' => 'contents', 'data' => $data, 'method' => 'post']
		);
		$expected = array_merge($data, ['status' => 'failure']);

		// Test response body
		$this->assertIdentical($expected, json_decode($result, true));

		$result = $VatNumberChecks->response->statusCode();
		$expected = 503;

		// Test response code
		$this->assertIdentical($expected, $result);
	}

}
