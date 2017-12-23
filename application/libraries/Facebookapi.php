<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// class for access facebook apis
class Facebookapi {

	private $fb;
	private $access_token;

	public function __construct()
    {
    	$this->ci =& get_instance();
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

		    do
		    {
		    	foreach ($postsEdge as $post) {
	    			$post_array = $post->asArray();
		    		$post_likes = $this->fb->get($post_array['id'].'/likes?summary=1',
				        $this->fb->getApp()->getAccessToken()->getValue()
				    )->getBody();
			    	$post_likes = json_decode($post_likes);

			    	$post_comments = $this->fb->get($post_array['id'].'/comments?summary=1',
				        $this->fb->getApp()->getAccessToken()->getValue()
				    )->getBody();
			    	$post_comments = json_decode($post_comments);

			    	$post_array['likes'] = $post_likes->summary->total_count;
			    	$post_array['comments'] = $post_comments->summary->total_count;
		    		array_push($all_posts,$post_array); 
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
    }
}