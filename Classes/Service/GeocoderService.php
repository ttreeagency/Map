<?php
namespace Ttree\Map\Service;

use Geocoder\Geocoder;
use Geocoder\Query\GeocodeQuery;
use Neos\Flow\Annotations as Flow;

/**
 * Geocoder Service
 *
 * @Flow\Scope("singleton")
 * @api
 */
class GeocoderService {

    /**
     * @var Geocoder
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
            $coordinates = $this->geocoder->geocodeQuery(GeocodeQuery::create($address));
			if (!$coordinates->count()) {
				return NULL;
			}
			$address = $coordinates->first();

			$data = [
				'longitude' => $address->getCoordinates()->getLongitude(),
				'latitude' => $address->getCoordinates()->getLatitude()
			];
			$this->runtimeCache[$cacheKey] = $data;
			return $data;
		} catch (\Geocoder\Exception\Exception $exception) {
			return NULL;
		}
	}
}
