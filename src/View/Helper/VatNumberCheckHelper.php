<?php
namespace App\View\Helper;

use App\View\Helper\AppHelper;
use App\View\Helper\FormHelper;
use App\View\Helper\HtmlHelper;

/**
 * VatNumberCheck Helper
 *
 * @property HtmlHelper $Html
 * @property FormHelper $Form
 */
class VatNumberCheckHelper extends AppHelper {

/**
 * An array of names of helpers to load.
 *
 * @var array
 */
	public $helpers = ['Html', 'Form'];

/**
 * The number of times this helper is called
 *
 * @var int
 */
	protected $_helperCount = 0;

/**
 * The css class name to trigger `check` logic.
 *
 * @var string
 */
	protected $_inputClass = 'vat-number-check';

/**
 * Generates a vat number check form field.
 *
 *  See `FormHelper::input`.
 *
 * @param string $fieldName This should be "Modelname.fieldname"
 * @param array $options Each type of input takes different options.
 * @return string Html output for a form field
 */
	public function input($fieldName, $options = []) {
		$this->_helperCount += 1;
		if ($this->_helperCount === 1) {
			$this->_addJs();
		}

		$options = array_merge($options, ['type' => 'text']);

		$class = $this->_inputClass;
		if (empty($options['class'])) {
			$options['class'] = $class;
		} else {
			$options['class'] = sprintf('%s %s', $options['class'], $class);
		}

		return $this->Form->input($fieldName, $options);
	}

/**
 * Adds the needed javascript to the DOM (once).
 *
 * @return void
 */
	protected function _addJs() {
		$checkUrl = $this->Url->build([
			'plugin' => 'vat_number_check', 'controller' => 'VatNumberChecks', 'action' => 'check', 'ext' => 'json'
		]);
		$checkImages = [
			'ok' => $this->Url->build('/vat_number_check/img/ok.png'),
			'failure' => $this->Url->build('/vat_number_check/img/failure.png'),
			'serviceUnavailable' => $this->Url->build('/vat_number_check/img/service-unavailable.png'),
		];

		$script = "
			/* jshint jquery:true */

			jQuery.noConflict();
			(function($) {
				$(function () {
					var options = {
						elementSelector: '" . sprintf('input.%s', $this->_inputClass) . "',
						checkUrl: '" . $checkUrl . "',
						checkImages: " . json_encode($checkImages) . ",
					};
					var vatNumberCheck = new VatNumberCheck(options);
				});
			})(jQuery);
		";

		$this->Html->script([
			'VatNumberCheck.jquery.min', 'VatNumberCheck.klass.min', 'VatNumberCheck.vat_number_check'
		], ['inline' => false, 'once' => true]);
		$this->Html->scriptBlock($script, ['inline' => false]);
	}

}
