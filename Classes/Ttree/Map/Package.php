<?php
namespace Ttree\Map;

use TYPO3\Flow\Core\Bootstrap;
use TYPO3\Flow\Package\Package as BasePackage;

/**
* The Ttree Map Package
*/
class Package extends BasePackage {

	/**
	 * @param Bootstrap $bootstrap The current bootstrap
	 * @return void
	 */
	public function boot(Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect('TYPO3\TYPO3CR\Domain\Model\Node', 'nodePropertyChange', 'Ttree\Map\Service\NodeGeocoderService', 'handleNodePropertyChanged');
	}

}