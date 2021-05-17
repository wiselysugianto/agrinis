<?php
class M_pembayaran extends CI_Model {
	public function __construct(){}

	public function getTableData(){
		$this->db->select('a.id,a.pembayaran,count(*) as jumlah_buruh');
		$this->db->from('pembayaran_header a');
		$this->db->join('pembayaran_detail b','b.id_header=a.id');
		$this->db->group_by('a.id,a.pembayaran');
		$this->db->order_by('a.id','desc');
		return $this->db->get()->result_array();
	}

	public function inputTableData($data_header,$data_detail){
			$this->db->trans_start();
			$this->db->insert('pembayaran_header', $data_header);
			$id_header = $this->db->insert_id();

			for($i=0;$i<count($data_detail);$i++){
				$data_detail[$i]['id_header'] = $id_header;
			}

			$this->db->insert_batch('pembayaran_detail',$data_detail);
			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				return false;
			}
			return true;
	}

	public function getEditTableData($id){
		$this->db->select('*');
		$this->db->from('pembayaran_header');
		$this->db->where('id',$id);
		return $this->db->get()->row_array();
	}

	public function getEditTableDataDetail($id){
		$this->db->select('*');
		$this->db->from('pembayaran_detail');
		$this->db->where('id_header',$id);
		return $this->db->get()->result_array();
	}

	public function editTableData($data_header,$data_detail){
		$this->db->trans_start();
		$this->db->where('id',$data_header['id']);
		$this->db->update('pembayaran_header',$data_header);

		$this->db->where('id_header',$data_header['id']);
		$this->db->delete('pembayaran_detail');

		$this->db->insert_batch('pembayaran_detail',$data_detail);
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}
		return true;
	}

	public function deleteTableData($id){
		$this->db->trans_start();
		$this->db->where('id', $id);
		$this->db->delete('pembayaran_header');
		if($this->db->affected_rows() == 0){
			return false;
		}
		$this->db->trans_complete();
		return true;
	}

}
?>
