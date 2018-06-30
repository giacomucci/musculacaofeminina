<?php
class Av5_Correios_Model_Tracking {
    
    private $_ws			= NULL; // Endereço de acesso ao webservice
    private $_user			= NULL; // Login de acesso ao webservice
    private $_pass			= NULL; // Senha de acesso ao webservice
    private $_type			= NULL; // Tipo de exibição dos objetos: L (lista) ou F (intervalo)
    private $_result		= NULL; // Formato do retorno da consulta de objeto: T (todos os eventos) ou U (apenas o último evento)
    private $_language		= NULL; // Idioma do retorno da consulta: 101 (Português) ou 102 (Inglês)
    
    private function _init() {
        $helper = Mage::helper('av5_correios');
        $this->_ws = $helper->getConfig('webservices/tracking/url');
        $this->_user = $helper->getConfig('webservices/tracking/user');
        $this->_pass = $helper->getConfig('webservices/tracking/pass');
        $this->_type = $helper->getConfig('webservices/tracking/type');
        $this->_result = $helper->getConfig('webservices/tracking/result');
        $this->_language = $helper->getConfig('webservices/tracking/language');
    }
    
    public function events($code)
    {
        $this->_init();
        $object = array(
            'usuario'   => $this->_user,
            'senha'     => $this->_pass,
            'tipo'      => $this->_type,
            'resultado' => $this->_result,
            'lingua'    => $this->_language,
            'objetos'   => trim($code)
        );
        
        $client = new SoapClient($this->_ws);
        $events = $client->buscaEventos($object);
        
        return $events->return->objeto;
    }
    
}