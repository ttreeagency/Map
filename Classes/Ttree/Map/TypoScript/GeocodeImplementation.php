<?php
namespace Ttree\Map\TypoScript;

use Ttree\Map\Service\GeocoderService;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject;

/**
 * Convert the given adresse to longitude / latitude
 */
class GeocodeImplementation extends AbstractTypoScriptObject {

	/**
	 * @var GeocoderService
	 * @Flow\Inject
	 */
	protected $geocoderService;

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->tsValue('address');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return array
	 */
	public function evaluate() {
		$address = trim($this->getAddress());
		return $this->geocoderService->geocode($address);
	}

}