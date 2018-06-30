<?php
/**
 * AV5 Tecnologia
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL).
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Shipping (Frete)
 * @package    Av5_Correios
 * @copyright  Copyright (c) 2013 Av5 Tecnologia (http://www.av5.com.br)
 * @author     AV5 Tecnologia <anderson@av5.com.br>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Av5_Correios_Model_Carrier_CorreiosMethod
 *
 * @category   Shipping
 * @package    Av5_Correios
 * @author     AV5 Tecnologia <anderson@av5.com.br>
 */

class Av5_Correios_Model_Carrier_CorreiosMethod extends Mage_Shipping_Model_Carrier_Abstract {

    /**
     * Código de operação do módulo
     * @var string
     */
	protected $_code 				= 'av5_correios';
    
	/**
	 * Variáveis de controle de CEPs
	 * @var string
	 */
    protected $_from				= NULL; // CEP de origem
    protected $_to					= NULL; // CEP de destino
    
    /**
     * Controle de retorno de operações
     * @var Mage_Shipping_Model_Rate_Result / Mage_Shipping_Model_Tracking_Result
     */
    protected $_result				= NULL; 
    
    /**
     * Controles do pacote
     */
    protected $_value				= NULL; // Valor do pedido
    protected $_weight				= NULL; // Peso total do pedido
    protected $_freeWeight			= NULL; // Peso total do frete grátis
    protected $_cubic				= NULL; // Peso cúbico total - PAC
    
    /**
     * Configurações diversas
     */
    protected $_maxOrderValue		= NULL; // Valor máximo do Pedido
    protected $_minOrderValue		= NULL; // Valor mínimo do Pedido
    protected $_maxWeight			= NULL; // Peso máximo permitido
    protected $_weightType			= NULL; // Peso máximo permitido
    protected $_defHeight			= NULL; // Altura padrão de pacotes
    protected $_defWidth			= NULL; // Comprimento padrão de pacotes
    protected $_defDepth			= NULL; // Largura padrão de pacotes
    protected $_postingMethods		= NULL; // Métodos de envio disponíveis
    protected $_title				= NULL; // Título do método de envio
    protected $_handlingFee			= NULL; // Taxa de envio
    protected $_request				= NULL; // Controle de requisição
    protected $_pacCodes			= NULL; // Serviços PAC
    
    /**
     * Verifica se o país está dentro da área atendida 
     * 
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return boolean
     */
    protected function _checkCountry(Mage_Shipping_Model_Rate_Request $request) {
    	$from = Mage::getStoreConfig('shipping/origin/country_id', $this->getStore());
    	$to = $request->getDestCountryId();
    	if ($from != "BR" || $to != "BR"){
    		Mage::helper('av5_correios')->log('Fora da área de atendimento');
    		return false;
    	}
    	
    	return true;
    }
    
    /**
     * Recupera, formata e verifica se os CEPs de origem e destino estão 
     * dentro do padrão correto
     * 
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return boolean
     */
    protected function _checkZipCode(Mage_Shipping_Model_Rate_Request $request) {
    	$this->_from = Mage::helper('av5_correios')->_formatZip(Mage::getStoreConfig('shipping/origin/postcode', $this->getStore()));
    	$this->_to = Mage::helper('av5_correios')->_formatZip($request->getDestPostcode());
    	
    	if(!$this->_from){
    		Mage::helper('av5_correios')->log('Erro com CEP de origem');
    		return false;
    	}
    	
    	if(!$this->_to){
    		Mage::helper('av5_correios')->log('Erro com CEP de destino');
    		$this->_throwError('zipcodeerror', 'CEP Inválido', __LINE__);
    		return false;
    	}
    	
    	return true;
    }
    
    protected function _init(Mage_Shipping_Model_Rate_Request $request){
    	if (!$this->isActive()) {
    		Mage::helper('av5_correios')->log('Módulo Desabilitado');
    		return false;
    	}
    	
    	if (!$this->_checkCountry($request)) return false;
    
    	if (!$this->_checkZipCode($request)) return false;
    	
    	$this->_title = $this->getConfigData('title');
    	$this->_weightType = $this->getConfigData('weight_type');
    	$this->_defHeight = $this->getConfigData('default_height');
    	$this->_defWidth = $this->getConfigData('default_width');
    	$this->_defDepth = $this->getConfigData('default_depth');
    	$this->_maxWeight = $this->_fixWeight($this->getConfigData('maxweight'));
    	$this->_maxOrderValue = $this->getConfigData('max_order_value');
    	$this->_minOrderValue = $this->getConfigData('min_order_value');
    	$this->_postingMethods = explode(",", $this->getConfigData('posting_methods'));
    	$this->_handlingFee = $this->getConfigData('handling_fee');
    	$this->_showDelivery = $this->getConfigData('show_delivery');
    	$this->_addDeliveryDays = $this->getConfigData('add_delivery_days');
    	$this->_declaredValue = $this->getConfigData('declared_value');
    	$this->_receivedWarning = $this->getConfigData('received_warning');
    	$this->_ownerHands = $this->getConfigData('owner_hands');
    	$this->_pacCodes = explode(",", $this->getConfigData('pac_codes'));
    	
    	$this->_result = Mage::getModel('shipping/rate_result');
    	$this->_value = $request->getBaseCurrency()->convert($request->getPackageValue(), $request->getPackageCurrency());
    	$this->_weight = $this->_fixWeight($request->getPackageWeight());
    	$this->_freeWeight = $this->_fixWeight($request->getFreeMethodWeight());
    	
    	return true;
    }
    
    /**
     * Checa se o valor do pedido está entre as faixas permitidas de valores
     * @return boolean
     */
    protected function _checkValueRange() {
    	if($this->_value < $this->_minOrderValue || $this->_value > $this->_maxOrderValue) {
    		$this->_throwError('valueerror', 'Valor do pacote fora dos limites permitidos', __LINE__);
    		return false;
    	}
    	return true;
    }
    
    /**
     * Corrige o peso informado com base na medida de peso configurada
     * @param string|int|float $weight
     * @return double
     */
    protected function _fixWeight($weight) {
    	$result = $weight;
    	if ($this->_weightType == 'gr') {
    		$result = number_format($weight/1000, 2, '.', '');
    	}
    	
    	return $result;
    }
    
    /**
     * Verifica se o peso do pacote está dentro do mínimo e máximo permitidos
     * @return boolean
     */
    protected function _checkWeightRange() {
    	if ($this->_weight > $this->_maxWeight) { // Checa se o peso excede o limite máximo
    		$this->_throwError('maxweighterror', 'Limite de peso excedido', __LINE__);
    		return false;
    	}
    	
    	if ($this->_weight <= 0) { // Checa se o peso do pacote é inferior a zero
    		$this->_throwError('weightzeroerror', 'Pacote com peso inferior ao permitido', __LINE__);
    		return false;
    	}
    	
    	return true; 
    }
    
    /**
     * Retorna mensagem de erro
     *
     * @param $message string
     * @param $log     string
     * @param $line    int
     * @param $custom  string
     */
    protected function _throwError($message, $log = null, $line = 'NO LINE', $custom = null){
    
    	$this->_result = null;
    	$this->_result = Mage::getModel('shipping/rate_result');
    
    	// Get error model
    	$error = Mage::getModel('shipping/rate_result_error');
    	$error->setCarrier($this->_code);
    	$error->setCarrierTitle($this->getConfigData('title'));
    
    	if(is_null($custom)){
    		//Log error
    		Mage::helper('av5_correios')->log($this->_code . ' [' . $line . ']: ' . $log);
    		$error->setErrorMessage($this->getConfigData($message));
    	}else{
    		//Log error
    		Mage::helper('av5_correios')->log($this->_code . ' [' . $line . ']: ' . $log);
    		$error->setErrorMessage(sprintf($this->getConfigData($message), $custom));
    	}
    
    	// Apend error
    	$this->_result->append($error);
    }
    
    /**
     * Calcula o peso cúbico do pacote
     */
    protected function _getCubicWeight(){
    	$cubicWeight = 0;
    	$items = Mage::getModel('checkout/cart')->getQuote()->getAllVisibleItems();
    	$maxH = $this->getConfigData('cubic_validation/max_height');
    	$minH = $this->getConfigData('cubic_validation/min_height');
    	$maxW = $this->getConfigData('cubic_validation/max_width');
    	$minW = $this->getConfigData('cubic_validation/min_width');
    	$maxD = $this->getConfigData('cubic_validation/max_depth');
    	$minD = $this->getConfigData('cubic_validation/min_depth');
    	$sumMax = $this->getConfigData('cubic_validation/sum_max');
    	$coefficient = $this->getConfigData('cubic_validation/coefficient');
    	$validate = $this->getConfigData('validate_dimensions');
    	foreach($items as $item){
    		$product = $item->getProduct();
    		$width = (!$product->getVolumeComprimento()) ? $this->_defWidth : $product->getVolumeComprimento();
    		$height = (!$product->getVolumeAltura()) ? $this->_defHeight : $product->getVolumeAltura();
   			$depth = (!$product->getVolumeLargura()) ? $this->_defDepth : $product->getVolumeLargura();
   			
   			if ($validate && ($height > $maxH || $height < $minH || $depth > $maxD || $depth < $minD || $width > $maxW || $width < $minW || ($height+$depth+$width) > $sumMax)) {
   				return false;
   			}
    		
   			$cubicWeight += (($width * $depth * $height) / $coefficient) * $item->getQty(); // Calcula o peso cúbico do item atual
    	}
    
    	$this->_cubic = $this->_fixWeight($cubicWeight);
    	
    	return true;
    }
    
    /**
     * Adiciona os valores de envio para o retorno
     *
     * @param $shipping_method string
     * @param $shippingPrice float
     * @param $correiosReturn array
     */
    protected function _appendShippingReturn($shipping_method, $shippingPrice = 0, $delivery = 0){
    	if (strlen($shipping_method) == 4) {
    		$shipping_method = (string)'0' . $shipping_method;
    	}
    	
    	$method = Mage::getModel('shipping/rate_result_method');
    	$method->setCarrier($this->_code);
    	$method->setCarrierTitle($this->_title);
    	$method->setMethod($shipping_method);
    
    	$shippingCost = $shippingPrice;
    	$shippingPrice = $shippingPrice + $this->_handlingFee;
    
    	$methodTitle = $this->getConfigData('serv_' . $shipping_method . '/name');
    	$methodTerm = $this->getConfigData('serv_' . $shipping_method . '/term');
    	
    	if ($custom_name = $this->getConfigData('custom_name_' . $shipping_method)) {
    	    $methodTitle = $custom_name;
    	}
    	
    	if($shipping_method == $this->getConfigData('acobrar_code')){
    		$methodTitle .= ' ( R$' . number_format($shippingPrice, 2, ',', '.') . ' )';
    		$shippingPrice = 0;
    	}
    
    	if ($this->_showDelivery){
    		if($delivery  > 0){
    			$method->setMethodTitle(sprintf($this->getConfigData('msgprazo'), $methodTitle, (int)($delivery + $this->_addDeliveryDays)));
    		} else {
    			$method->setMethodTitle(sprintf($this->getConfigData('msgprazo'), $methodTitle, (int)($methodTerm + $this->_addDeliveryDays)));
    		}
    	} else {
    		$method->setMethodTitle($methodTitle);
    	}
    
    	$method->setPrice($shippingPrice);
    	$method->setCost($shippingCost);
    
   		$this->_result->append($method);
    }
    
   	public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
		// Inicializa operações
   		if($this->_init($request) === false) return false;

		// Verifica a faixa de valor do pedido
        if(!$this->_checkValueRange()) return $this->_result;
		
        // Verifica a faixa de peso do pacote
		if(!$this->_checkWeightRange()) return $this->_result;
             
        // Calcula o peso cúbico
        if($this->_getCubicWeight() === false){
        	$this->_throwError('dimensionerror', 'Dimension error', __LINE__);
            return $this->_result;
        }

        // Recupera as cotações de frete
        $this->_request = $request;
        $this->_getQuotes();

        // Use descont codes
        $this->_updateFreeMethodQuote($request);

        return $this->_result;
   	}
   	
   	/**
   	 * Recupera cotações de frete
   	 *
   	 * @return object
   	 */
   	protected function _getQuotes(){
   		$quotes = Mage::getResourceModel('av5_correios/carrier_correios');
   		$this->_request->setPostingMethods($this->_postingMethods);
   		$this->_request->setPacCodes($this->_pacCodes);
   		$this->_request->setFixedPackageWeight($this->_fixWeight($this->_request->getPackageWeight()));
   		$this->_request->setCubicPackageWeight($this->_cubic);
   		foreach ($quotes->getRates($this->_request) as $rate) {
   			$method = $rate['servico'];
   			if (strlen($method) == 4) {
   				$method = (string)'0' . $method;
   			}
   			$this->_appendShippingReturn($method, $rate['valor'], $rate['prazo']);
   		}
   		
   		return $this->_result;
   	}
   	
   	/**
   	 * Informa se que o módulo aceita rastreamento
   	 *
   	 * @return boolean true
   	 */
   	public function isTrackingAvailable() {
   		return true;
   	}
   	
   	/**
   	 * Retorna as informações do rastreador
   	 *
   	 * @param mixed $tracking
   	 * @return mixed
   	 */
   	public function getTrackingInfo($tracking) {
   		$result = $this->getTracking($tracking);
   		if ($result instanceof Mage_Shipping_Model_Tracking_Result){
   			if ($trackings = $result->getAllTrackings()) {
   				return $trackings[0];
   			}
   		} elseif (is_string($result) && !empty($result)) {
   			return $result;
   		}
   		return false;
   	}
   	
   	/**
   	 * Retorna o rastreamento
   	 *
   	 * @param array $trackings
   	 * @return Mage_Shipping_Model_Tracking_Result
   	 */
   	public function getTracking($trackings) {
   		$this->_result = Mage::getModel('shipping/tracking_result');
   		foreach ((array) $trackings as $code) {
   			$this->_getTracking($code);
   		}
   		return $this->_result;
   	}
   	
   	/**
   	 * Recupera os dados de rastreamento direto dos Correios
   	 *
   	 * @param string $code
   	 * @return boolean
   	 */
   	protected function _getTracking($code) {
   		$error = Mage::getModel('shipping/tracking_result_error');
   		$error->setTracking($code);
   		$error->setCarrier($this->_code);
   		$error->setCarrierTitle($this->getConfigData('title'));
   		$error->setErrorMessage($this->getConfigData('urlerror'));

   		$events = Mage::getModel('av5_correios/tracking')->events($code);
   		
   		if(isset($events->erro)) {
   		    $error->setErrorMessage($events->erro);
   			$this->_result->append($error);
   			return false;
   		}
   		
   		$progress = array();
   		foreach($events->evento as $event) {
   			$locale = new Zend_Locale('pt_BR');
   			$date = new Zend_Date($event->data, 'dd/MM/YYYY', $locale);
   			
   			$location = $event->local;
   			if ($location) $location .= " - ";
   			$location .= $event->cidade;
   			if ($location) $location .= "/";
   			$location .= $event->uf; 
   			
   			$track = array(
   				'deliverydate' => $date->toString('YYYY-MM-dd'),
   				'deliverytime' => $event->hora,
   				'deliverylocation' => htmlentities($location),
   				'status' => htmlentities($event->descricao),
   				'activity' => htmlentities($event->descricao)
   			);
   	
   			if (isset($event->detalhe)) {
   				$track['activity'] .= ' - ' . htmlentities($event->detalhe);
   			}
   			
   			$progress[] = $track;
   		}
   	
   		if (!empty($progress)) {
   			$track = $progress[0];
   			$track['progressdetail'] = $progress;
   	
   			$tracking = Mage::getModel('shipping/tracking_result_status');
   			$tracking->setTracking($code);
   			$tracking->setCarrier('correios');
   			$tracking->setCarrierTitle($this->getConfigData('title'));
   			$tracking->addData($track);
   	
   			$this->_result->append($tracking);
   			return true;
   		} else {
   			$this->_result->append($error);
   			return false;
   		}
   	}
   	
   	/**
   	 * Define o CEP como obrigatório
   	 *
   	 * @return boolean
   	 */
   	public function isZipCodeRequired($countryId = null)
   	{
   		return true;
   	}
	
   	/**
   	 * Retorna a lista de serviços permitidos
   	 *
   	 * @return array
   	 */
   	public function getAllowedMethods()
   	{
   		return array($this->_code => $this->getConfigData('title'));
   	}
   	
   	/**
   	 * Gera frete grátis para um produto
   	 *
   	 * @param string $freeMethod
   	 * @return void
   	 */
   	protected function _setFreeMethodRequest($freeMethod)
   	{
   		// Set request as free method request
   		$this->_freeMethodRequest = true;
   		$this->_freeMethodRequestResult = Mage::getModel('shipping/rate_result');
   	
   		$this->_postMethods = $freeMethod;
   		$this->_postMethodsExplode = array($freeMethod);
   	
   		// Tranform free shipping weight
   		$this->_freeWeight = $this->_fixWeight($this->_freeWeight);
   	
   		$this->_weight = $this->_freeWeight;
   		$this->_cubic = $this->_freeWeight;
   	}
}