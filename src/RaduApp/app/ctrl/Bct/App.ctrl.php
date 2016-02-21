<?php
if (!defined('_WEB')) {
	exit();
}

class Controller_Bct_App extends ControllerApp {


	public function Home() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$data['strings']['title'] = $data['facebook']['meta']['og']['title'] = 'Black Chicken Tapes';
		$data['strings']['description'] = $data['facebook']['meta']['og']['description'] = __('Otherground label from Eastern Europe.');
		
		$data['facebook']['meta']['og']['image'] = "{$data['url']['media']}bct/bct_logo.gif";
		
		$data['tradelist'] = $this->Module()->TradelistGet();
		
		$data['tradelist_last_update'] = $this->Module()->TradelistGetLastUpdate();
		
		$data['tradelist_total'] = $this->Module()->TradelistGetTotal();
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}
	
	public function Devel() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$this->Module()->Devel();
		
		return;
	}
	
	public function DiscogsPriceInfoProcedure() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$this->Module()->DiscogsPriceInfoProcedure();
		
		return;
	}
	
	public function DiscogsPriceInfoProcedureUpdatePrices() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$this->Module()->DiscogsPriceInfoProcedureUpdatePrices();
		
		return;
	}
}
?>