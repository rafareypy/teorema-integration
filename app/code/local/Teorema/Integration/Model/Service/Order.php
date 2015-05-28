<?php
class Teorema_Integration_Model_Service_Order extends Teorema_Integration_Model_Service
{

  function __construct(){
      parent::__construct();
  }

  public function getOrderToTeorema(){

  }



  public function sendOrderMagentoToTeorema($order = null){

    #verificar se cliente ja existe no Webservice
    #verificar esquema do tipo de frete no magento
    #Verificar o esquema de venda tipo frete


    $service = Mage::getModel('teorema_integration/service_customer');

    $customers = $service->getAllCustomersToTeorema() ;

    #fazendo teste com outro codigo de cliente, que esta no teorema, porem não foi cadastrado pelo modulo de integração
    $cliforcodigo = "0002513" ;

    $paramsProducts = array();

    $items = $order->getAllItems();

    $totals = 0 ;

    $i = 0;
    foreach($items as $item){
        $_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $item->getSku());

        #verificar
        $paramsProducts[$i]['VENDAQUANTIDADEENTREGA'] = $item->getQtyOrdered();


        $paramsProducts[$i]['UNIDADEUNIDADE'] = 'UN';
        $paramsProducts[$i]['EMPRESAITEM'] = '0001';
        //$paramsProducts[$i]['TRANSACAO'] = ''; não é necesario
        $paramsProducts[$i]['VENDAVALORUNITARIO'] = $_product->getFinalPrice();
        $paramsProducts[$i]['GRUPOFISCALCODIGO'] = '050';  //Verificar o que é?


        $paramsProducts[$i]['VENDANUMEROLANCAMENTO'] = str_pad($i+1, 5, '0', STR_PAD_LEFT);

        #verificar
        //$paramsProducts[$i]['VENDASEQUENCIA'] = '';

        $paramsProducts[$i]['VENDADESCONTOPERCENTUAL'] = 0;
        $paramsProducts[$i]['VENDADESCONTOVALOR'] = 0;
        $paramsProducts[$i]['VENDAIPIALIQUOTA'] = 0;
        $paramsProducts[$i]['VENDAVALORUNITARIOCALCULADO'] = 0;
        $paramsProducts[$i]['VENDAQUANTIDADE'] = (int)$item->getQtyOrdered();
        $paramsProducts[$i]['VENDAITEMDESCRICAO'] = $_product->getName();
        $paramsProducts[$i]['VENDAVALORUNITARIOLIQUIDO'] = $_product->getFinalPrice();
        $paramsProducts[$i]['ITEMREDUZIDO'] = $item->getSku(); //sku do produto é o mesmo itemreduzido do produto teorema

        $totals += $_product->getFinalPrice() * (int)$item->getQtyOrdered();

        $i++;
      }




    $params = array(
        'VENDAVALORIPI'=> 0,
        'METODO'=>'ecomPedidoAltera',
        'VENDATIPO'=>'P',
        'CLIFORCODIGO'=> $cliforcodigo,
        'VENDASTATUS'=>'N',
        'SENHA'=> $this->password_md5,

        'VENDAVALORFRETE'=> number_format($order->getShippingAmount(), 2, ',', ''),
        'VENDASDETAIL'=> $paramsProducts,
        'VENDASISTEMA'=>'W',
        'VENDAHORAEMISSAO'=> date("H:i:s"),
        'VENDAVALORTOTAL'=> $order->getGrandTotal(),
        'USUARIO'=> $this->user,
        'EMPRESAVENDEDOR'=>'0001',
        'VENDADATALANCAMENTO'=> date("Y-m-d"),
        'VENDASITUACAO'=>'A',
        'VENDEDORCODIGO'=>'00001',
        'VENDAVALORTOTALLIQUIDO'=> 25 ;// number_format($order->getGrandTotal(), 2, ',', ''),
        //'VENDAVALORTOTALLIQUIDO'=> '25,00',

        'EMPRESACLIFOR'=>'0001',
        'VENDADATAEMISSAO'=> date("Y-m-d"),
        'VENDANUMERO'=> $order->getIncrementId(), #verificar se precisa enviar..?

        'EMPRESAMOVIMENTO'=>'0001',
        'SENHA_REF'  => $this->password ,
        'VENDAVALORDESCONTO'=> 0,
        'CONDICAOCODIGO'=>'0001'  #este valor foi contrado no banco teorema
      );



      //campos que não esta no manual, porem são campos no modelo   br.inf.teorema.pitagoras.business.model.entity.VendaMasterBasic:


      ////vendaSubTipo
      //vendaValorAcrescimo
      //vendaValorDescontoEspecial
      //vendaValorEntrada
      //vendaBaseCalculo
      //vendaCNDEspecialIndice
      //vendaCNDEspecialEntrada
      //todos CND
      //operacaoCodigo

      //vendaSupervCliforSituacao
      //vendaImpressao
      //vendaDataEmbarque
      //vendaDataPrevistaEntrega
      //vendaNumeroRequisicao

      var_dump(json_encode($params));

      echo "<br/><br/><br/><br/>";

      $value = $this->connectionGet($params);

      var_dump($value);
      die();
  }





}
