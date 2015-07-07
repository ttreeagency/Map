<?php
namespace Ttree\Map\Command;

use Ttree\Map\Service\GeocoderService;
use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;

class GeocoderCommandController extends CommandController {

	/**
	 * @var ContextFactoryInterface
	 * @Flow\Inject
	 */
	protected $contextFactory;

	/**
	 * @var GeocoderService
	 * @Flow\Inject
	 */
	protected $geocodeService;

	/**
	 * Check report status
	 *
	 * @param string $workspace
	 */
	public function reverseCommand($workspace = 'live') {
		$context = $this->createContext($workspace);
		$flowQuery = new FlowQuery(array($context->getRootNode()));
		$flowQuery = $flowQuery->find('[instanceof Ttree.Map:BaseMap]');

		$count = $match = 0;
		/** @var NodeInterface $node */
		foreach ($flowQuery as $node) {
			$this->outputLine('  %s', array($node->getLabel()));
			if (trim($node->getProperty('longitude')) !== '' && trim($node->getProperty('latitude')) !== '') {
				continue;
			}
			$streetAddress = preg_replace('/CP[ ]?[0-9]*/m', '', $node->getProperty('streetAddress'));
			$address = sprintf('%s, %s %s', $streetAddress, $node->getProperty('postalCode'), $node->getProperty('addressLocality'));
			$coordinates = $this->geocodeService->geocode($address);
			if ($coordinates !== NULL) {
				$node->setProperty('longitude', $coordinates['longitude']);
				$node->setProperty('latitude', $coordinates['latitude']);
				++$match;
			}
			sleep(1);
			++$count;
		}

		$this->outputLine('Number of processed nodes: %d', [$count]);
		$this->outputLine('  with match: %d', [$match]);

	}

	/**
	 * Creates a content context for given workspace and language identifiers
	 *
	 * @param string $workspaceName
	 * @param array $languageIdentifiers
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function createContext($workspaceName, array $languageIdentifiers = NULL) {
		$contextProperties = array(
			'workspaceName' => $workspaceName,
			'invisibleContentShown' => TRUE,
			'inaccessibleContentShown' => TRUE
		);
		if ($languageIdentifiers !== NULL) {
			$contextProperties = array_merge($contextProperties, array(
				'dimensions' => array('language' => $languageIdentifiers)
			));
		}
		return $this->contextFactory->create($contextProperties);
	}

}