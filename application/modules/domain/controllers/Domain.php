<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domain extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('domain/Domain_model','domain_model');
    }

    public function index()
    {
        $data['domains'] = $this->domain_model->get_all();
        $data['page'] = 'domain/index';
        $data['script'] = 'domain/index_script';

        $this->load->view('layout/main',$data);
    }

    public function create()
	{
		// validation rules
		$this->form_validation->set_rules('domain_name', 'Domain Name', 'required|trim|valid_url');
		$this->form_validation->set_rules('api_key', 'API Key', 'required|trim');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page'] = 'domain/form';
			$data['script'] = 'domain/form_script';

			$this->load->view('layout/main',$data);
		}
		else
		{
			$domain  = $this->input->post('domain_name', true);
			$api_key = $this->input->post('api_key', true);

			$data = [
				'domain_name' => $domain,
				'api_key'     => $api_key,
				'status'      => 1
			];

			$this->domain_model->insert($data);

			// API call
			$this->update_erp_api_key($domain,$api_key);

			$this->session->set_flashdata('success','Domain Added Successfully');
			redirect('api-domain');
		}
	}
	
	public function edit($id)
	{
		$domainData = $this->domain_model->get($id);

		if(!$domainData){
			show_404();
		}

		$this->form_validation->set_rules('domain_name', 'Domain Name', 'required|trim|valid_url');
		$this->form_validation->set_rules('api_key', 'API Key', 'required|trim');

		if ($this->form_validation->run() == FALSE)
		{
			$data['domain'] = $domainData;
			$data['page'] = 'domain/form';
			$data['script'] = 'domain/form_script';

			$this->load->view('layout/main',$data);
		}
		else
		{
			$domain  = $this->input->post('domain_name', true);
			$api_key = $this->input->post('api_key', true);

			$data = [
				'domain_name' => $domain,
				'api_key'     => $api_key,
			];

			$this->domain_model->update($id,$data);

			$response = $this->update_erp_api_key($domain,$api_key);

			if(isset($response['status']) && $response['status']){
				$this->session->set_flashdata('success',$response['message']);
			}else{
				$this->session->set_flashdata('error',$response['message'] ?? 'API failed');
			}

			redirect('api-domain');
		}
	}

    public function delete()
	{
		$id = $this->input->post('id');

		$this->domain_model->delete($id);

		echo json_encode([
			'status' => 'success'
		]);
	}
	public function change_status()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		$update = $this->domain_model->update_status($id,$status);

		if($update){
			echo json_encode(['status'=>'success']);
		}else{
			echo json_encode(['status'=>'error']);
		}
	}
	
	private function update_erp_api_key($domain,$api_key)
    {

        $url = $domain."/api/system/update_api_key";

        $post = [
            'api_key'=>$api_key
        ];
		
		$response = call_api_post($url, $post);
		
        return $response;

    }
}
