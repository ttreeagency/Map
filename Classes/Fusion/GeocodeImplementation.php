<?php
namespace Ttree\Map\Fusion;

use Ttree\Map\Service\GeocoderService;
use Neos\Flow\Annotations as Flow;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

/**
 * Convert the given adresse to longitude / latitude
 */
class GeocodeImplementation extends AbstractFusionObject {

	/**
	 * @var GeocoderService
	 * @Flow\Inject
	 */
	protected $geocoderService;

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->fusionValue('address');
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
