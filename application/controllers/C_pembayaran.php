<?
	class C_pembayaran extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model('M_pembayaran','model');
			$this->control = 'C_pembayaran';
		}

		public function index(){
			$this->load->view('v_pembayaran');
		}

		public function getTableData(){
			$data = $this->model->getTableData();
			echo json_encode($data);
		}

		public function inputTableData(){
			$status = false;
			$type = 'add';
			$this->form_validation->set_rules('pembayaran', 'Pembayaran', 'required|numeric');
			for($i=0;$i<count($_POST['buruh']);$i++){
				$this->form_validation->set_rules('buruh['.$i.']',"Buruh", "trim|required|numeric");
			}

			$this->form_validation->set_message('required', '{field} wajib diisi!');
			if (!$this->form_validation->run()) {
				for($i=0;$i<count($_POST['buruh']);$i++){
					$a = $_POST['array_buruh'][$i];
					$buruh[$a] = form_error('buruh['.$i.']', '<p class="mt-3 text-danger">', '</p>');
				}

				$json = array(
						'status' => $status,
						'type' => $type,
						'validation' => array(
								'pembayaran' 	 => form_error('pembayaran', '<p class="mt-3 text-danger">', '</p>'),
								'buruh'				 => $buruh,
							),
				);
			}else{
				$data_header = array(
					'pembayaran'	=> trim($this->input->post('pembayaran')),
				);

				for($i=0;$i<count($_POST['buruh']);$i++){
					$data_detail[] = array(
						'id_header'		=> '',
						'buruh'				=> trim($_POST['buruh'][$i]),
					);
				}
				$result = $this->model->inputTableData($data_header,$data_detail);
				if($result){$status = true;}

				$json = array(
					'status' => $status,
					'type' => $type,
					'validation' => array(),
				);
			}
			echo json_encode($json);
		}

		public function getEditTableData(){
			$data['header'] = $this->model->getEditTableData($this->input->get('id'));
			$data['detail'] = $this->model->getEditTableDataDetail($this->input->get('id'));
			echo json_encode($data);
		}

		public function editTableData(){
			$status = false;
			$type = 'edit';
			$this->form_validation->set_rules('pembayaran', 'Pembayaran', 'required|numeric');
			for($i=0;$i<count($_POST['buruh']);$i++){
				$this->form_validation->set_rules('buruh['.$i.']',"Buruh", "trim|required|numeric");
			}

			$this->form_validation->set_message('required', '{field} wajib diisi!');
			if (!$this->form_validation->run()) {
				for($i=0;$i<count($_POST['buruh']);$i++){
					$a = $_POST['array_buruh'][$i];
					$buruh[$a] = form_error('buruh['.$i.']', '<p class="mt-3 text-danger">', '</p>');
				}

				$json = array(
						'status' => $status,
						'type' => $type,
						'validation' => array(
								'pembayaran' 	 => form_error('pembayaran', '<p class="mt-3 text-danger">', '</p>'),
								'buruh'				 => $buruh,
							),
				);
			}else{
				$data_header = array(
					'id'					=> trim($this->input->post('id')),
					'pembayaran'	=> trim($this->input->post('pembayaran')),
				);

				for($i=0;$i<count($_POST['buruh']);$i++){
					$data_detail[] = array(
						'id'					=> trim($_POST['id_buruh'][$i]),
						'id_header'		=> trim($this->input->post('id')),
						'buruh'				=> trim($_POST['buruh'][$i]),
					);
				}

				$result = $this->model->editTableData($data_header,$data_detail);
				if($result){$status = true;}

				$json = array(
					'status' => $status,
					'type' => $type,
					'validation' => array(),
				);
			}
			echo json_encode($json);
		}

		public function deleteTableData(){
			$status = false;
			$type = 'delete';
			$result = $this->model->deleteTableData($this->input->get('id'));
			if($result){
				$status = true;
			}
			$json = array(
				'status' => $status,
				'type' 	 => $type,
			);
			echo json_encode($json);
		}
	}
?>
