<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Teorema
 * @package     Teorema_Integration
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @generator   http://www.mgt-commerce.com/kickstarter/ Mgt Kickstarter
 */

class Teorema_Integration_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function standardizeName($str){
        $str = mb_strtolower($str, 'UTF-8');
        $str = trim($str);
        $arr = explode(" ", $str);

        $str = array_map(function($e){
            if(in_array($e, array('da','de','do','das','dos')))
                return $e;
            return ucwords($e);
        }, $arr);

        return implode(" ", $str);
    }

    public function stateAbbreviation($state){

        switch($state){
            case "Acre":
                return 'AC';
            case "Alagoas":
                return 'AL';
            case "Amapá":
                return 'AP';
            case "Amazonas":
                return 'AM';
            case "Bahia":
                return 'BA';
            case "Ceará":
                return 'CE';
            case "Distrito Federal":
                return 'DF';
            case "Espírito Santo":
                return 'ES';
            case "Goiás":
                return 'GO';
            case "Maranhão":
                return 'MA';
            case "Mato Grosso":
                return 'MT';
            case "Mato Grosso do Sul":
                return 'MS';
            case "Minas Gerais":
                return 'MG';
            case "Pará":
                return 'PA';
            case "Paraíba":
                return 'PB';
            case "Paraná":
                return 'PR';
            case "Pernambuco":
                return 'PE';
            case "Piauí":
                return 'PI';
            case "Rio de Janeiro":
                return 'RJ';
            case "Rio Grande do Norte":
                return 'RN';
            case "Rio Grande do Sul":
                return 'RS';
            case "Rondônia":
                return 'RO';
            case "Roraima":
                return 'RR';
            case "Santa Catarina":
                return 'SC';
            case "São Paulo":
                return 'SP';
            case "Sergipe":
                return 'SE';
            case "Tocantins":
                return 'TO';
        }

    }

}
