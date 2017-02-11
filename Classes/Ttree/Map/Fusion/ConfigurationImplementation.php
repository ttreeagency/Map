<?php
namespace Ttree\Map\Fusion;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Fusion\FusionObjects\TemplateImplementation;

/**
 * Google Map Configuration TS implementation
 */
class ConfigurationImplementation extends TemplateImplementation {

	/**
	 * @Flow\InjectConfiguration("styles")
	 * @var array
	 */
	protected $styles;

	/**
	 * @return array
	 */
	public function getMap() {
		return $this->tsValue('map') ?: array();
	}

	/**
	 * @return float
	 */
	public function getLongitude() {
		$longitude = $this->tsValue('longitude');
		if (is_float($longitude)) {
			return $longitude;
		}
		$coordinates = $this->getCoordinates();
		if (is_array($coordinates) && isset($coordinates['longitude'])) {
			return $coordinates['longitude'];
		}
		return NULL;
	}

	/**
	 * @return float
	 */
	public function getLatitude() {
		$latitude = $this->tsValue('latitude');
		if (is_float($latitude)) {
			return $latitude;
		}
		$coordinates = $this->getCoordinates();
		if (is_array($coordinates) && isset($coordinates['latitude'])) {
			return $coordinates['latitude'];
		}
		return NULL;
	}

	/**
	 * @return array
	 */
	public function getCoordinates() {
		return $this->tsValue('coordinates');
	}

	/**
	 * @return integer
	 */
	public function getZoomLevel() {
		return (integer)$this->tsValue('zoomLevel');
	}

	/**
	 * @return string
	 */
	public function getHeight() {
		return $this->tsValue('height');
	}

	/**
	 * @return string
	 */
	public function getWidth() {
		return $this->tsValue('width');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string
	 */
	public function evaluate() {
		$configuration = [];
		$configuration['styles'] = $this->getStylesConfiguration();
		$configuration['map'] = array(
			'width' => $this->getWidth(),
			'height' => $this->getHeight(),
			'longitude' => $this->getLongitude(),
			'latitude' => $this->getLatitude(),
			'options' => array(
				'zoom' => $this->getZoomLevel(),
				'disableDefaultUI' => $this->tsValue('disableDefaultUI'),
				'panControl' => $this->tsValue('panControl'),
				'zoomControl' => $this->tsValue('zoomControl'),
				'scaleControl' => $this->tsValue('scaleControl')
			)
		);

		return json_encode($configuration);
	}

	/**
	 * @return array
	 */
	protected function getStylesConfiguration() {
		$styles = array();
		if (!is_array($this->styles)) {
			return array();
		}
		foreach ($this->styles as $preset => $style) {
			if (isset($style['settings']) && is_array($style['settings'])) {
				$styles[$preset] = $style['settings'];
			}
		}

		return $styles;
	}

}
