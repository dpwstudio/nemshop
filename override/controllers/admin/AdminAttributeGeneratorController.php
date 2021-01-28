<?php
/**
 * Admin Attribute Generator Controller override
 *
 * @category  Override
 * @package   AI-DelayedCombinations
 * @author    @ide-info <contact@ai-dev.fr>
 * @copyright 2014-2015 @ide-info
 * @license   Read the multi-language license license.txt
 *
 * @link      http://www.boutique.ai-dev.fr
 */
class AdminAttributeGeneratorController extends AdminAttributeGeneratorControllerCore
{
	/**
     * Set attributes impacts in DB
     *
     * @param integer $id_product Product id
     * @param array   $tab        List of choosen attributes
     *
     * @return boolean Result of operation
     */
	protected static function setAttributesImpacts($id_product, $tab)
	{
	    //  Delete old impacts
	    Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'attribute_impact WHERE id_product = '.$id_product);
	
		//	Create new ones
	    return parent::setAttributesImpacts($id_product, $tab);
	}
	
}
