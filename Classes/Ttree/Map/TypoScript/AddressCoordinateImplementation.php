<?php
namespace Ttree\Map\TypoScript;

use Geocoder\Provider\GoogleMaps;
use Ivory\HttpAdapter\CurlHttpAdapter;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject;

/**
 * Convert the given adresse to longitude / latitude
 */
class AddressCoordinateImplementation extends AbstractTypoScriptObject {

	/**
	 * @var GoogleMaps
	 */
	protected $geocoder;

	/**
	 * @return void
	 */
	public function initializeObject() {
		$curl = new CurlHttpAdapter();
		$this->geocoder = new GoogleMaps($curl);
	}

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
		$coordinates = $this->geocoder->geocode($address);
		if (!$coordinates->count()) {
			return NULL;
		}
		$address = $coordinates->first();

		$data = [
			'longitude' => $address->getLongitude(),
			'latitude' => $address->getLatitude()
		];
		return $data;
	}

}