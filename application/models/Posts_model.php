<?php

class Posts_model extends CI_Model {

	public function __construct()	{
	  $this->load->database(); 
	}

	public function get_posts($post_count=NULL,$comment_count=NULL,$sort_by=NULL) {

		//get post by count
		//$this->db->from('posts');

		if($sort_by && (strtoupper($sort_by) =='ASC' || strtoupper($sort_by) =='DESC'))
	  		$this->db->order_by("likes", $sort_by);

		if($post_count && !$comment_count) {
			$query = $this->db->get('posts', $post_count);
		}
		elseif($comment_count && !$post_count)
		{
			$query = $this->db->get_where('posts',  array('comments_count >=' => $comment_count));
		}
		elseif ($post_count && $comment_count) {
			$query = $this->db->get_where('posts',  array('comments_count >=' => $comment_count),$post_count);
		}
		else
		{
			$query = $this->db->get('posts');
		}

	  	

	  	return $query->result();
	}

	public function insert_or_update_post($post)
	{
		$query = $this->db->get_where('posts',  array('post_id' => $post['post_id']));
		$count = $query->num_rows();
		if($count==0)
			$this->db->insert('posts', $post); 
		else
			$this->update_post($post);
	}

	public function update_post($post)
	{
	    $this->db->where('post_id', $post['post_id']);
	    unset($post['post_id']);
	    $this->db->update('posts', $post);
	    return true;
	}

	public function delete_post($post_id)
	{
		$this->db->where('post_id', $post_id);
   		$result = $this->db->delete('posts');
   		return $result;
	}

	public function search_posts($q)
	{
		$this->db->where('MATCH (description) AGAINST ("'.$q.'")', NULL, FALSE);
		$query = $this->db->get('posts');
		return $query->result();
	}
}