<?php

namespace MyCloudPress\Services;

use MyCloudPress\Models\Comment;
use MyCloudPress\Models\Commentmeta;
use MyCloudPress\Models\Option;
use MyCloudPress\Models\Post;
use MyCloudPress\Models\Postmeta;
use MyCloudPress\Models\User;
use MyCloudPress\Models\Usermeta;
use MyCloudPress\Services\Service;

class WpSiteStatsService extends Service {
	
	protected $apiKey;

	protected $userModel;
	protected $usermetaModel;
	protected $postModel;
	protected $postmetaModel;
	protected $commentModel;
	protected $commentmetaModel;
	protected $optionModel;

	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
		$this->userModel = new User($this->apiKey);
		$this->usermetaModel = new Usermeta($this->apiKey);
		$this->postModel = new Post($this->apiKey);
		$this->postmetaModel = new Postmeta($this->apiKey);
		$this->commentModel = new Comment($this->apiKey);
		$this->commentmetaModel = new Commentmeta($this->apiKey);
		$this->optionModel = new Option($this->apiKey);
	}

	public function run()
	{
		$countUsers = $this->userModel->countUsers();
		$countUsermeta = $this->usermetaModel->countUsermeta();
		$countPosts = $this->postModel->countPosts();
		$countPostmeta = $this->postmetaModel->countPostmeta();
		$countComments = $this->commentModel->countComments();
		$countCommentmeta = $this->commentmetaModel->countCommentmeta();
		$countOptions = $this->optionModel->countOptions();

		return [
			'users' => $countUsers,
			'usermeta' => $countUsermeta,
			'posts' => $countPosts,
			'postmeta' => $countPostmeta,
			'comments' => $countComments,
			'commentmeta' => $countCommentmeta,
			'options' => $countOptions,
		];
	}
}