<?php
namespace VatNumberCheck\Controller;

use VatNumberCheck\Controller\AppController;

use Cake\Event\Event;

/**
 * VatNumberChecks Controller
 *
 * @property VatNumberCheck.VatNumberCheck $VatNumberCheck
 */
class VatNumberChecksController extends AppController {

/**
 * An array of names of components to load.
 *
 * @var array
 */
	public $components = ['RequestHandler'];

/**
 * Called before the controller action.
 *
 * @return void
 */
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);

		if (in_array($this->request->action, ['check'], true)) {
			// Disable Security component checks
			if ($this->components()->has('Security')) {
				$this->components()->unload('Security');
			}

			// Allow action without authentication
			if ($this->components()->has('Auth')) {
				$this->Auth->allow($this->request->action);
			}

		}


	}

/**
 * Checks a given vat number (from POST data).
 *
 * @return void
 */
	public function check() {
		$vatNumber = $this->request->data('vatNumber');
		debug($this->VatNumberCheck);
		debug($this->VatNumberChecks);
		die();
		$vatNumber = $this->VatNumberChecks->normalize($vatNumber);

		$jsonData = array_merge(compact('vatNumber'), ['status' => 'failure']);
		try {
			$vatNumberValid = $this->VatNumberChecks->check($vatNumber);
			if ($vatNumberValid) {
				$jsonData = array_merge(compact('vatNumber'), ['status' => 'ok']);
			}
		} catch (InternalErrorException $e) {
			$this->response->statusCode(503);
		}

		$this->set(compact('jsonData'));
		$this->set('_serialize', 'jsonData');
	}

}
