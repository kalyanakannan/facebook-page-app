<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class for access facebook apis
class Facebookapi {

	private $fb;
	private $access_token;

	public function __construct()
    {
    	//get CI instance
    	$this->ci =& get_instance();
    	//load database
    	$this->ci->load->model('posts_model');
    	//load facebook config files
    	$this->ci->config->load('facebook');

        $this->fb = new \Facebook\Facebook([
		  'app_id' =>  $this->ci->config->item('app_id'),
		  'app_secret' => $this->ci->config->item('app_secret'),
		  'default_graph_version' => $this->ci->config->item('default_graph_version')
		]);
    }

    function getPosts($page_id)
    {
    	try {
    		//return  $this->fb->getApp()->getAccessToken()->getValue();
    		$fields = 'fields=picture,message,description,created_time,likes.summary(true).limit(0),comments.summary(true).limit(0)';
    		// get current date
    		$today = Carbon\Carbon::now();
    		//calculate date of 30 days before
    		$before_days = $today->copy();
    		$before_days = $before_days->subDays($this->ci->config->item('days'));

    		//conver to timestamp query string
    		$since = "since=".$before_days->timestamp;
    		$until = "until=".$today->timestamp;

    		//get posts
    		$posts = $this->fb->get($page_id.'/posts?'.$since.'&'.$until.'&'.$fields,
		        $this->fb->getApp()->getAccessToken()->getValue()
		    );

 //   		return $posts;
		    $postsEdge = $posts->getGraphEdge();

		    $all_posts = array();

		    //get all page post in single array
		    do
		    {
		    	foreach ($postsEdge as $post) {
	    			$fb_post = $post->asArray();
	    			//get likes count of post
		    		$post_likes = $this->fb->get($fb_post['id'].'/likes?summary=1',
				        $this->fb->getApp()->getAccessToken()->getValue()
				    )->getBody();
			    	$post_likes = json_decode($post_likes);
			    	//get comments count of post
			    	$post_comments = $this->fb->get($fb_post['id'].'/comments?summary=1',
				        $this->fb->getApp()->getAccessToken()->getValue()
				    )->getBody();
			    	$post_comments = json_decode($post_comments);

			    	$post = array();
			    	$post['post_id'] = $fb_post['id'];
					$post['page_id'] = $page_id;
					$post['title'] = $fb_post['message'];
					if(array_key_exists('description',$fb_post))
						$post['description'] = $fb_post['description'];
					else
						$post['description'] = "";
					if(array_key_exists('picture',$fb_post))
						$post['image_url'] = $fb_post['picture'];
					else
						$post['image_url'] = "";
					$post['likes'] = $post_likes->summary->total_count;
					$post['comments_count'] = $post_comments->summary->total_count;
					$dt = Carbon\Carbon::instance($fb_post['created_time']);
					$post['published_date'] = $dt->toDateTimeString();

					$insert_results = $this->ci->posts_model->insert_or_update_post($post);
					
		    		array_push($all_posts,$post); 
		    	}

		    } while($postsEdge = $this->fb->next($postsEdge));

	        return $all_posts;

    	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}
		catch(Exception $e)
		{
			return $e;
		}
    }
}