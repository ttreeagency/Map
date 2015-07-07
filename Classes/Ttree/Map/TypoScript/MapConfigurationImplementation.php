<?php
namespace Ttree\Map\TypoScript;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation;

/**
 *
 */
class MapConfigurationImplementation extends TemplateImplementation {

	/**
	 * @flow\inject(setting="styles")
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
		$coordinates = $this->getCoordinates();
		if (is_array($coordinates) && isset($coordinates['longitude'])) {
			return $coordinates['longitude'];
		}
		return $this->tsValue('longitude');
	}

	/**
	 * @return float
	 */
	public function getLatitude() {
		$coordinates = $this->getCoordinates();
		if (is_array($coordinates) && isset($coordinates['latitude'])) {
			return $coordinates['latitude'];
		}
		return $this->tsValue('latitude');
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