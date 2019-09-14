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
		return $this->fusionValue('map') ?: array();
	}

	/**
	 * @return float
	 */
	public function getLongitude() {
		$longitude = $this->fusionValue('longitude');
		if (is_numeric($longitude)) {
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
		$latitude = $this->fusionValue('latitude');
		if (is_numeric($latitude)) {
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
		return $this->fusionValue('coordinates');
	}

	/**
	 * @return integer
	 */
	public function getZoomLevel() {
		return (integer)$this->fusionValue('zoomLevel');
	}

	/**
	 * @return string
	 */
	public function getHeight() {
		return $this->fusionValue('height');
	}

	/**
	 * @return string
	 */
	public function getWidth() {
		return $this->fusionValue('width');
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
				'disableDefaultUI' => $this->fusionValue('disableDefaultUI'),
				'panControl' => $this->fusionValue('panControl'),
				'zoomControl' => $this->fusionValue('zoomControl'),
				'scaleControl' => $this->fusionValue('scaleControl')
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
