<?php
class ControllerExtensionPaymentHomepay extends Controller {
	
	private $ocr = array();
	
	public function loadConfig(){
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$homepay = array(
				'uid' => $this->config->get('payment_homepay_usr_id'),
				'public_key' => $this->config->get('payment_homepay_public_key'),
				'amount' =>  $this->ocr['totalAmount'] = $this->toAmount(
						$this->currencyFormat($order_info['total'], $order_info['currency_code'])
						),
				'mode' => "0",
				'control' =>   $this->session->data['order_id'],
				'success_url' => urlencode($this->url->link('checkout/success', '', true)),
				'failure_url' => urlencode(''),
				'notify_url' => urlencode($this->url->link('extension/payment/homepay_notify'))
		);
		$homepay['crc'] = md5(join('', $homepay) . $this->config->get('payment_homepay_private_key'));
		
		return $homepay;
	}
		
	public function index(){

		$data = $this->loadConfig();
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data ['action'] = "https://homepay.pl/przelew/";
		$this->load->model('checkout/order');

		return $this->load->view ( 'extension/payment/homepay', $data );		
	}
	
	private function toAmount($value){
		
		return number_format($value * 100, 0, '', '');
	}
	
	private function currencyFormat($value, $currencyCode){
		
		return $this->currency->format($value, $currencyCode, '', false);
	}
}	