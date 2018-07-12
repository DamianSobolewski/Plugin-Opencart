<?php
class ControllerExtensionPaymentHomepay extends Controller {

    const VERSION = '1.0.0';

    private $error = array();
    private $settings = array();

    public function index() {
        $this->load->language('extension/payment/homepay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_homepay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        $data['text_info'] = sprintf($this->language->get('text_info'), self::VERSION);

        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $data['error_usr_id'] = isset($this->error['usr_id']) ? $this->error['usr_id'] : '';
        $data['error_public_key'] = isset($this->error['public_key']) ? $this->error['public_key'] : '';
        $data['error_private_key'] = isset($this->error['private_key']) ? $this->error['private_key'] : '';


        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['payment_homepay_status'] = isset($this->request->post['payment_homepay_status']) ?
        	$this->request->post['payment_homepay_status'] : $this->config->get('payment_homepay_status');
        
        $data['payment_homepay_total'] = isset($this->request->post['payment_homepay_total']) ?
            $this->request->post['payment_homepay_total'] : $this->config->get('payment_homepay_total');

        $data['payment_homepay_geo_zone_id'] = isset($this->request->post['payment_homepay_geo_zone_id']) ?
            $this->request->post['payment_homepay_geo_zone_id'] : $this->config->get('payment_homepay_geo_zone_id');

        $data['payment_homepay_usr_id'] = isset($this->request->post['payment_homepay_usr_id']) ?
            $this->request->post['payment_homepay_usr_id'] : $this->config->get('payment_homepay_usr_id');

        $data['payment_homepay_public_key'] = isset($this->request->post['payment_homepay_public_key']) ?
            $this->request->post['payment_homepay_public_key'] : $this->config->get('payment_homepay_public_key');

        $data['payment_homepay_private_key'] = isset($this->request->post['payment_homepay_private_key']) ?
            $this->request->post['payment_homepay_private_key'] : $this->config->get('payment_homepay_private_key');


        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/homepay', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/homepay', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);       
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/homepay', $data));

    }

    protected function validate(){
        if (!$this->user->hasPermission('modify', 'extension/payment/homepay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['payment_homepay_usr_id']) {
            $this->error['usr_id'] = $this->language->get('error_usr_id');
        }
        if (!$this->request->post['payment_homepay_public_key']) {
            $this->error['public_key'] = $this->language->get('error_public_key');
        }
        if (!$this->request->post['payment_homepay_private_key']) {
            $this->error['private_key'] = $this->language->get('error_private_key');
        }
        
        return !$this->error;
    }

    public function install(){
        $this->load->model('extension/payment/homepay');
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('payment_homepay', $this->settings);
    }

    public function uninstall(){
        $this->load->model('extension/payment/homepay');
        $this->load->model('setting/setting');

        $this->model_setting_setting->deleteSetting('payment_homepay');
    }

}
