<?php
namespace Ttree\Map\Service;

use Geocoder\Exception\NoResult;
use Geocoder\Provider\GoogleMaps;
use Ivory\HttpAdapter\CurlHttpAdapter;
use Neos\Flow\Annotations as Flow;

/**
 * Geocoder Service
 *
 * @Flow\Scope("singleton")
 * @api
 */
class GeocoderService {

    /**
     * @var GoogleMaps
     * @Flow\Inject()
     */
	protected $geocoder;

	/**
	 * @var array
	 */
	protected $runtimeCache = [];

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
