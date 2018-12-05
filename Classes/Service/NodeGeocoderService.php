<?php
namespace Ttree\Map\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMapper;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Ttree\Map\Domain\Model\Address;

/**
 * Node Geocoder Service
 *
 * @Flow\Scope("singleton")
 * @api
 */
class NodeGeocoderService {

	/**
	 * @var GeocoderService
	 * @Flow\Inject
	 */
	protected $geocoderService;

	/**
	 * @var PropertyMapper
	 * @Flow\Inject
	 */
	protected $propertyMapper;

	/**
	 * @param NodeInterface $node
	 * @return void
	 */
	public function geocode(NodeInterface $node) {
		if (!$node->getNodeType()->isOfType('Ttree.Map:PointOfInterest')) {
			return;
		}
		$address = $this->getAddressFromNode($node);
		if ($address === NULL) {
			return;
		}
		$coordinates = $this->geocoderService->geocode($address);
		if ($coordinates !== NULL) {
			$node->setProperty('reversedLongitude', $coordinates['longitude']);
			$node->setProperty('reversedLatitude', $coordinates['latitude']);
		}
	}

	/**
	 * @param NodeInterface $node
	 * @param string $propertyName
	 * @todo add settings to configured watched properties
	 */
	public function handleNodePropertyChanged(NodeInterface $node, $propertyName) {
		if (!$node->getNodeType()->isOfType('Ttree.Map:PointOfInterest') || !in_array($propertyName, ['streetAddress', 'addressLocality', 'addressLocality'])) {
			return;
		}
		$this->geocode($node);
	}

	/**
	 * @param NodeInterface $node
	 * @return string
	 */
	protected function getAddressFromNode(NodeInterface $node) {
		$address = $this->propertyMapper->convert($node, Address::class);
		$address = trim($address);

		return $address !== '' ? $address : NULL;
	}

}
