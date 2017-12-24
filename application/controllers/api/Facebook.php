<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;



class Facebook extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
        

        $this->load->library('format');
	}

	public function posts_get()
	{
		//list all post
		
		//possibilities of filters key
		$api_filter_keys = ['posts_count','comment_count','sort_by'];
		//get user input keys
		$user_filter_key = array_keys($this->get());
		//find diff b/w user input key and possibilities key
		$diff_filter_key = array_diff($user_filter_key, $api_filter_keys);

		if(count($this->get()) > 3)
			$this->response([
                'status' => FALSE,
                'message' => 'Check your url'
            ], REST_Controller::HTTP_BAD_REQUEST);

		elseif(count($this->get()) <= 3 && $diff_filter_key)
		{
			$this->response([
                'status' => FALSE,
                'message' => 'Check your url'
            ], REST_Controller::HTTP_BAD_REQUEST);
		}
		else
		{
			
			if($this->get('posts_count'))
			$page_count = trim($this->get('posts_count'));
			else
				$page_count = NULL;
			if($this->get('comment_count'))
				$comment_count = trim($this->get('comment_count'));
			else
				$comment_count = NULL;
			if ($this->get('sort_by')) 
				$sort_by = trim($this->get('sort_by'));
			else
				$sort_by = NULL;
				$this->response($this->posts_model->get_posts($page_count,$comment_count,$sort_by)	, REST_Controller::HTTP_OK);
		}
	}

	public function deletePost_post()
	{
		//possibilities of input key
		$api_input_keys = ['post_id'];
		//get user input keys
		$user_input_key = array_keys($this->post());
		//find diff b/w user input key and possibilities key
		$diff_filter_key = array_diff($user_input_key, $api_input_keys);
		
		if(count($user_input_key) > 1)
			$this->response([
                'status' => FALSE,
                'message' => 'Bad request'
            ], REST_Controller::HTTP_BAD_REQUEST);

		elseif(count($user_input_key) <= 1 && $diff_filter_key)
		{
			$this->response([
                'status' => FALSE,
                'message' => 'Bad request'
            ], REST_Controller::HTTP_BAD_REQUEST);
		}
		else
		{
			$post_id = $this->post('post_id');
			$this->response($this->posts_model->delete_post($post_id),REST_Controller::HTTP_OK);
		}
		
	}

	public function updatePost_post()
	{
		$post = array();
		if($this->post('post_id'))
			$post['post_id'] = $this->post('post_id');
		if($this->post('title'))
			$post['title'] = $this->post('title');
		if($this->post('description'))
			$post['description'] = $this->post('description');
		$this->response($this->posts_model->update_post($post),REST_Controller::HTTP_OK);

	}

	//api for get 3o posts from facebook page based on page id
	public function fetchData_post()
	{
		//possibilities of input key
		$api_input_keys = ['page_id'];
		//get user input keys
		$user_input_key = array_keys($this->post());
		//find diff b/w user input key and possibilities key
		$diff_filter_key = array_diff($user_input_key, $api_input_keys);
		
		if(count($user_input_key) > 1)
			$this->response([
                'status' => FALSE,
                'message' => 'Bad request'
            ], REST_Controller::HTTP_BAD_REQUEST);

		elseif(count($user_input_key) <= 1 && $diff_filter_key)
		{
			$this->response([
                'status' => FALSE,
                'message' => 'Bad request'
            ], REST_Controller::HTTP_BAD_REQUEST);
		}
		else
		{
			if($this->post('page_id'))
				$page_id = $this->post('page_id');
			$this->response($this->facebookapi->getPosts($page_id),REST_Controller::HTTP_OK);
		}
	}

	public function searchPosts_get()
	{
		//possibilities of filters key
		$api_filter_keys = ['q'];
		//get user input keys
		$user_filter_key = array_keys($this->get());
		//find diff b/w user input key and possibilities key
		$diff_filter_key = array_diff($user_filter_key, $api_filter_keys);

		if(count($this->get()) > 1)
			$this->response([
                'status' => FALSE,
                'message' => 'Check your url'
            ], REST_Controller::HTTP_BAD_REQUEST);

		elseif(count($this->get()) <= 1 && $diff_filter_key)
		{
			$this->response([
                'status' => FALSE,
                'message' => 'Check your url'
            ], REST_Controller::HTTP_BAD_REQUEST);
		}
		else
		{
			
			if($this->get('q'))
			$query = trim($this->get('q'));
			else
				$query = NULL;
			$this->response($this->posts_model->search_posts($query), REST_Controller::HTTP_OK);
		}
	}
}
