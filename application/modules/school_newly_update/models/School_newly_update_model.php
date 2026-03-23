<?php
class School_newly_update_model extends CI_Model {

    function get_all()
    {
        return $this->db->get(SCHOOL_NEWLY_UPDATES)->result();
    }

    function get($id)
    {
        return $this->db->where('id',$id)->get(SCHOOL_NEWLY_UPDATES)->row();
    }

    function insert($data)
    {
        return $this->db->insert(SCHOOL_NEWLY_UPDATES,$data);
    }

    function update($id,$data)
    {
        return $this->db->where('id',$id)->update(SCHOOL_NEWLY_UPDATES,$data);
    }

    function delete($id)
    {
        return $this->db->where('id',$id)->delete(SCHOOL_NEWLY_UPDATES);
    }
	public function update_status($id,$status)
	{
		return $this->db
			->where('id',$id)
			->update(SCHOOL_NEWLY_UPDATES,['status'=>$status]);
	}

}