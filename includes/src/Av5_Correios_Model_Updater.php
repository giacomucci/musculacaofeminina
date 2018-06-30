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
 * Av5_Correios_Model_Updater
 *
 * @category   Shipping
 * @package    Av5_Correios
 * @author     AV5 Tecnologia <anderson@av5.com.br>
 */

class Av5_Correios_Model_Updater extends Varien_Object {
	
	/**
	 * Propriedades da classe 
	 */
	protected $_code				= "av5_correios";
	protected $_from				= NULL; // CEP de origem
	protected $_wsUrl				= NULL; // URL do webservice
	protected $_login				= NULL; // Login do Webservice (Contrato)
	protected $_password			= NULL; // Senha do webservice (Contrato)
	protected $_defHeight			= NULL; // Altura padrão de pacotes
	protected $_defWidth			= NULL; // Comprimento padrão de pacotes
	protected $_defDepth			= NULL; // Largura padrão de pacotes
	protected $_weightType			= NULL; // Medida de peso padrão
	protected $_maxWeight			= NULL; // Peso máximo permitido
	protected $_updateFrequency		= NULL; // Frequencia de atualização da tabela
	protected $_postingMethods		= NULL; // Serviços de postagem
	protected $_deleteCodes			= NULL; // Códigos de erro para exclusão do serviço da base
	protected $_limitRecords		= NULL; // Número de registros atualizados por iteração
	protected $_ownerHands			= NULL; // Entrega em mãos próprias
	protected $_receivedWarning		= NULL; // Aviso de recebimento
	protected $_declaredValue		= NULL; // Valor declarado
	protected $_initiated			= false; // Controla se as variáveis já foram inicializadas
	
	/**
	 * Inicializa as propriedades da classe
	 */
	protected function _init() {
		if (!$this->_initiated) {
			$this->_wsUrl = $this->getConfigData('webservices/price/url');
			$this->_login = $this->getConfigData('login');
			$this->_password = $this->getConfigData('password');
			$this->_defHeight = $this->getConfigData('default_height');
			$this->_defWidth = $this->getConfigData('default_width');
			$this->_defDepth = $this->getConfigData('default_depth');
			$this->_weightType = $this->getConfigData('weight_type');
			$this->_maxWeight = $this->_fixWeight($this->getConfigData('max_weight'));
			$this->_updateFrequency = $this->getConfigData('update_frequency');
			$this->_postingMethods = $this->getConfigData('posting_methods');
			$this->_deleteCodes = explode(",",$this->getConfigData('delete_codes'));
			$this->_limitRecords = $this->getConfigData('limit_records');
			$this->_ownerHands = ($this->getConfigData('owner_hands')) ? 'S' : 'N';
			$this->_receivedWarning = ($this->getConfigData('received_warning')) ? 'S' : 'N';
			$this->_declaredValue = $this->getConfigData('declared_value');
			$this->_from = Mage::helper('av5_correios')->_formatZip(Mage::getStoreConfig('shipping/origin/postcode', $this->getStore()));
			$this->_initiated = true;
		}
	}
	
	/**
	 * Recupera configurações do módulo
	 * @param string $field
	 * @return boolean, mixed, string, NULL
	 */
	public function getConfigData($field)
	{
		if (empty($this->_code)) {
			return false;
		}
		$path = 'carriers/'.$this->_code.'/'.$field;
		return Mage::getStoreConfig($path, $this->getStore());
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
	 * Retorna todos os serviços habilitados na loja
	 * @return array
	 */
	public function allServices(){
		$this->_init();
		return explode(',',$this->_postingMethods);
	} 
	
	/**
	 * Executa atualização de tabela de preços do serviço informado
	 * @param string $services
	 */
	public function update($services=null) {
		$this->_init();
		
		$model = Mage::getResourceModel('av5_correios/carrier_correios');
		
		if (!is_array($services) && !is_numeric($services) && !is_string($services)) {
			$services = $this->_postingMethods;
		}
		
		$totalSuccess = $totalErrors = 0;
		$cep_origem = $this->_from;
		$client = new SoapClient($this->_wsUrl);
		foreach($model->listServices($services, $this->_updateFrequency, $this->_limitRecords) as $row) {
			$params = array(
					"nCdEmpresa" 			=> $this->_login,
					"sDsSenha" 				=> $this->_password,
					"nCdFormato"			=> "1",
					"nCdServico"			=> (string)$row['servico'],
					"nVlComprimento"		=> $this->_defWidth,
					"nVlAltura"				=> $this->_defHeight,
					"nVlLargura"			=> $this->_defDepth,
					"nVlDiametro"			=> "20",
					"sCepOrigem"			=> $this->_from,
					"sCdMaoPropria"			=> $this->_ownerHands,
					"sCdAvisoRecebimento"	=> $this->_receivedWarning,
					"nVlPeso"				=> $row['peso'],
					"sCepDestino"			=> $row['cep_destino_ref']
			);
			
			if ($row['servico'] == $this->getConfigData('acobrar_code')) { // SEDEX A COBRAR
				$params["nVlValorDeclarado"] = "1";
			} else {
				$params["nVlValorDeclarado"] = $this->_declaredValue;
			}
				
			try {
				$content = $client->CalcPrecoPrazo($params);
				$xml = $content->CalcPrecoPrazoResult->Servicos;
			} catch(Exception $e) {
				Mage::helper('av5_correios')->log("AV5_Correios Erro: " . $e->getMessage() . ' para ' . $row['servico'] . ':' . $row['nome']);
				$totalErrors++;
				continue;
			}
			
			if($xml) {
				Mage::helper('av5_correios')->log('XML: ' . var_export($xml,true));
				$cServico = $xml->cServico;
				if (!is_array($xml->cServico)) {
					$cServico = array($xml->cServico);
				}
				foreach($cServico as $servico) {
					if ($servico->Erro == "0") {
						try {
							$data = array();
							$data['valor'] = str_replace(",",".",$servico->Valor);
							$data['prazo'] = $servico->PrazoEntrega;
							$data['lastupdate'] = date('Y-m-d H:i:s');
							$model->updateService($row['id'],$data);
							$totalSuccess++;
						} catch (Exception $e) {
							Mage::helper('av5_correios')->log("Erro: " . $e->getMessage() . " - CEP: " . $cep_destino . ' para ' . $row['servico'] . ':' . $row['nome']);
							$totalErrors++;
						}
					} else {
						if (in_array($servico->Erro,$this->_deleteCodes)) {
							$model->deleteService($row['id']);
						}
						Mage::helper('av5_correios')->log("Erro: " . $servico->Erro . " >> " . $servico->MsgErro." – ".$row['cep_destino_ini']." – ".$row['cep_destino_fim']." – ".$row['cep_destino_ref']);
						$totalErrors++;
					}
	  			}
			} else {
				Mage::helper('av5_correios')->log("Erro: Correios fora do ar.");
				$totalErrors++;
			}
		}
		return array("success" => $totalSuccess, "errors" => $totalErrors);
	}
	
	/**
	 * Retorna a lista de serviços que precisam de atualização
	 * @return array
	 */
	public function toUpdate() {
		$this->_init();
		$model = Mage::getResourceModel('av5_correios/carrier_correios');
		return $model->toUpdate($this->_postingMethods, $this->_updateFrequency);
	}
	
	/**
	 * Retorna o numero de registros desatualizados de um serviço
	 * @param Zend_Db_Table_Row
	 */
	public function hasToUpdate($method){
		$this->_init();
		$model = Mage::getResourceModel('av5_correios/carrier_correios');
		return $model->hasToUpdate($method, $this->_updateFrequency);
	}
	
	/**
	 * Retorna o número de registros atualizados de um serviço
	 * @param Zend_Db_Table_Row
	 */
	public function updatedCount($method){
		$this->_init();
		$model = Mage::getResourceModel('av5_correios/carrier_correios');
		return $model->updatedCount($method, $this->_updateFrequency);
	}
	
	public function getServiceName($service) {
		return $this->getConfigData('serv_' . $service . '/name');
	}
	
	public function stillUpdate($service) {
		$model = Mage::getResourceModel('av5_correios/carrier_correios');
		$result = $model->hasToUpdate($service, $this->_updateFrequency);
		
		if ($result['total'] > 0) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Popula a tabela de preços com os dados básicos para os serviços selecionados
	 */
	public function populate() {
		$this->_init();
		$model = Mage::getResourceModel('av5_correios/carrier_correios');
		
		$postingMethods = explode(',', $this->_postingMethods);
		$methods = array();
		
		if (is_array($postingMethods)) {
			foreach ($postingMethods as $method) {
				$methods[] = $this->_getMethodData($method);
			}
		} else {
			$methods[] = $this->_getMethodData($postingMethods);
		}
		
		$model->populate($methods, $this->_from);
	}
	
	private function _getMethodData($method) {
	    $name = $this->getConfigData('serv_' . $method . '/name');
	    $term = $this->getConfigData('serv_' . $method . '/term');
	    $weight = $this->getConfigData('serv_' . $method . '/maxweight');
	    $minWeight = $this->getConfigData('serv_' . $method . '/minweight');
	    return array($method,$name,$term,$weight,$minWeight);
	}
}