<?php
namespace Ttree\Map\Service;

use Geocoder\Exception\NoResult;
use Geocoder\Provider\GoogleMaps;
use Ivory\HttpAdapter\CurlHttpAdapter;
use TYPO3\Flow\Annotations as Flow;

/**
 * Geocoder Service
 *
 * @Flow\Scope("singleton")
 * @api
 */
class GeocoderService {

	/**
	 * @var GoogleMaps
	 */
	protected $geocoder;

	/**
	 * @var array
	 */
	protected $runtimeCache = [];

	/**
	 * @return void
	 */
	public function initializeObject() {
		$curl = new CurlHttpAdapter();
		$this->geocoder = new GoogleMaps($curl);
	}

	/**
	 * @param string $address
	 * @return array
	 */
	public function geocode($address) {
		try {
			$address = trim($address);
			$cacheKey = md5($address);
			if (isset($this->runtimeCache[$cacheKey])) {
				return $this->runtimeCache[$cacheKey];
			}
			$coordinates = $this->geocoder->geocode($address);
			if (!$coordinates->count()) {
				return NULL;
			}
			$address = $coordinates->first();

			$data = [
				'longitude' => $address->getLongitude(),
				'latitude' => $address->getLatitude()
			];
			$this->runtimeCache[$cacheKey] = $data;
			return $data;
		} catch (NoResult $exception) {
			return NULL;
		}
	}

}