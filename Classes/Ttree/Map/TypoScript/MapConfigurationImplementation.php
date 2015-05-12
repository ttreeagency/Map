<?php
namespace Ttree\Map\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Ttree.Map".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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
	public function getDefaultLongitude() {
		return $this->tsValue('defaultLongitude');
	}

	/**
	 * @return float
	 */
	public function getDefaultLatitude() {
		return $this->tsValue('defaultLatitude');
	}

	/**
	 * @return integer
	 */
	public function getDefaultZoomLevel() {
		return (integer)$this->tsValue('defaultZoomLevel');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string
	 */
	public function evaluate() {
		$configuration = [];
		$configuration['styles'] = $this->getStylesConfiguration();
		/** @var NodeInterface $map */
		$map = $this->getMap();
		$configuration['map'] = array(
			'width' => $map->getProperty('width'),
			'height' => $map->getProperty('height'),
			'longitude' => $map->getProperty('longitude') ?: $this->getDefaultLongitude(),
			'latitude' => $map->getProperty('latitude') ?: $this->getDefaultLatitude(),
			'options' => array(
				'zoom' => (integer)$map->getProperty('zoomlevel') ?: $this->getDefaultZoomLevel(),
				'disableDefaultUI' => (boolean)$map->getProperty('disableDefaultUI'),
				'panControl' => (boolean)$map->getProperty('panControl'),
				'zoomControl' => (boolean)$map->getProperty('zoomControl'),
				'scaleControl' => (boolean)$map->getProperty('scaleControl')
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