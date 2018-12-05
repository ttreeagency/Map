<?php
namespace Ttree\Map;

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;

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
		$dispatcher->connect('Neos\ContentRepository\Domain\Model\Node', 'nodePropertyChange', 'Ttree\Map\Service\NodeGeocoderService', 'handleNodePropertyChanged');
	}

}
