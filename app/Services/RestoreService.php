<?php

namespace MyCloudPress\Services;

use MyCloudPress\Models\User;
use MyCloudPress\Models\Usermeta;
use MyCloudPress\Models\Option;
use MyCloudPress\Models\Post;
use MyCloudPress\Models\Postmeta;
use MyCloudPress\Models\Comment;
use MyCloudPress\Models\Commentmeta;
use MyCloudPress\Models\Term;
use MyCloudPress\Models\Termmeta;
use MyCloudPress\Models\TermTaxonomy;
use MyCloudPress\Models\TermRelationship;

class RestoreService extends Service {

	protected $apiKey;
	protected $userModel;
	protected $usermetaModel;
	protected $postModel;
	protected $postmetaModel;
	protected $commentModel;
	protected $commentmetaModel;
	protected $termModel;
	protected $termmetaModel;
	protected $termTaxonomyModel;
	protected $termRelationshipModel;
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
		$this->termModel = new Term($this->apiKey);
		$this->termmetaModel = new Termmeta($this->apiKey);
		$this->termTaxonomyModel = new Termtaxonomy($this->apiKey);
		$this->termRelationshipModel = new Termrelationship($this->apiKey);
		$this->optionModel = new Option($this->apiKey);
	}

	
	public function restoreUsers()
	{
		$remoteUsers = $this->userModel->getRemoteAllUsers();

		$users = $this->userModel->restoreUsersToSite($remoteUsers);

		return $users;
	}

	
	public function restoreUsermeta()
	{
		$remoteUsermeta = $this->usermetaModel->getRemoteAllUsermeta();

		$usermeta = $this->usermetaModel->restoreUsermetaToSite($remoteUsermeta);

		return $usermeta;
	}

	
	public function restorePosts()
	{
		$remotePosts = $this->postModel->getRemoteAllPosts();

		$posts = $this->postModel->restorePostsToSite($remotePosts);

		return $posts;
	}

	
	public function restorePostmeta()
	{
		$remotePostmeta = $this->postmetaModel->getRemoteAllPostmeta();

		$posts = $this->postmetaModel->restorePostmetaToSite($remotePostmeta);

		return $posts;
	}

	
	public function restoreComments()
	{
		$remoteComments = $this->commentModel->getRemoteAllComments();

		$posts = $this->commentModel->restoreCommentsToSite($remoteComments);

		return $posts;
	}

	
	public function restoreCommentmeta()
	{
		$remoteCommentmeta = $this->commentmetaModel->getRemoteAllCommentmeta();

		$commentmeta = $this->commentmetaModel->restoreCommentmetaToSite($remoteCommentmeta);

		return $commentmeta;
	}

	
	public function restoreTerms()
	{
		$remoteTerm = $this->termModel->getRemoteAllTerms();

		$term = $this->termModel->restoreTermsToSite($remoteTerm);

		return $term;
	}

	
	public function restoreTermmeta()
	{
		$remoteTermmeta = $this->termmetaModel->getRemoteAllTermmeta();

		$termmeta = $this->termmetaModel->restoreTermmetaToSite($remoteTermmeta);

		return $termmeta;
	}

	
	public function restoreTermTaxonomy()
	{
		$remoteTermTaxonomy = $this->termTaxonomyModel->getRemoteAllTermTaxonomy();

		$termTaxonomy = $this->termTaxonomyModel->restoreTermTaxonomyToSite($remoteTermTaxonomy);

		return $termTaxonomy;
	}

	
	public function restoreTermRelationship()
	{
		$remoteTermRelationship = $this->termRelationshipModel->getRemoteAllTermRelationship();

		$termRelationship = $this->termRelationshipModel->restoreTermRelationshipToSite($remoteTermRelationship);

		return $termRelationship;
	}

	
	public function restoreOptions()
	{
		$remoteOptions = $this->optionModel->getRemoteAllOptions();

		$options = $this->optionModel->restoreOptionsToSite($remoteOptions);

		return $options;
	}

	
	public function run()
	{
		$this->restoreUsers();
		$this->restoreUsermeta();
		$this->restorePosts();
		$this->restorePostmeta();
		$this->restoreComments();
		$this->restoreCommentmeta();
		$this->restoreTerms();
		$this->restoreTermmeta();
		$this->restoreTermTaxonomy();
		$this->restoreTermRelationship();
		$this->restoreOptions();
	}
}