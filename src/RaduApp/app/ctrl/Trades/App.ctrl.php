<?php
/**
 * $Id$
 *
 * App controller.
 *
 * Module: Trades
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage TradesModule
 * @version    $Revision$
 * @since      Version 7
 * @access     public
 * @filesource
 */
if (!defined('_WEB')) {
    exit();
}

/**
 * Controller_Trades_App
 * App Controller for Trades module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage TradesModule
 */
class Controller_Trades_App extends ControllerApp {

    public function Tradelist() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $data['strings']['title'] = $data['facebook']['meta']['og']['title'] = __('Trades');
        $data['strings']['description'] = $data['facebook']['meta']['og']['description'] = __('Looking for vinyl records. Any genre, any period. Offering records, CDs and fanzines in exchange.');
        
        $data['facebook']['meta']['og']['image'] = "{$data['url']['media']}trades.jpg";
        
        $data['categories'] = $this->Module()->GetCategories();
        
        $data['tradelist'] = $this->Module()->GetTradelist($data['url'], $data['path']);
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }
}
?>