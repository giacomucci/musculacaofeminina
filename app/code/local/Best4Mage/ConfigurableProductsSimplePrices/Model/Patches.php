<?php 

class Best4Mage_ConfigurableProductsSimplePrices_Model_Patches extends Mage_Core_Model_Abstract
{
	/**
	 * Use to store applied patches.
	 *
	 * @var array
	 */
	public $appliedPatches = array();

	/**
	 * Use to hold location reference to  `applied.patches.list` file.
	 *
	 * @var string
	 */
	private $patchFile;

	/**
	 * Constructor
	 *
	 * Use to load the applied patches array.
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->patchFile = Mage::getBaseDir('etc') . DS . 'applied.patches.list';
		$this->_loadPatchFile();
	}

	/**
	 * Use to get patches.
	 *
	 * @return string
	 */
	public function getPatches()
	{
		return $this->appliedPatches;
	}
	
	/**
	 * Use to load the patches array with applied patches.
	 *
	 * @return void
	 */
	protected function _loadPatchFile()
	{
		$ioAdapter = new Varien_Io_File();
		if (!$ioAdapter->fileExists($this->patchFile)) {
		    return;
		}
		$ioAdapter->open(array('path' => $ioAdapter->dirname($this->patchFile)));
		$ioAdapter->streamOpen($this->patchFile, 'r');
		while ($buffer = $ioAdapter->streamRead()) {
		    if(stristr($buffer,'|')){
		    	list($date, $patch, $magentoVersion, $patchVersion) = array_map('trim', explode('|', $buffer));
		    	$this->appliedPatches[] = $patch;
		    }
		}
		$ioAdapter->streamClose();
	}
}