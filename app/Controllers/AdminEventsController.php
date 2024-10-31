<?php
namespace MyCloudPress\Controllers;

use MyCloudPress\Controllers\Controller;
use MyCloudPress\Models\User;
use MyCloudPress\Models\Usermeta;
use MyCloudPress\Models\Commentmeta;
use MyCloudPress\Models\Postmeta;
use MyCloudPress\Models\Termmeta;
use MyCloudPress\Models\TermRelationship;
use MyCloudPress\Models\TermTaxonomy;
use MyCloudPress\Models\Option;
use MyCloudPress\Services\SyncUserService;
use MyCloudPress\Services\SyncUsermetaService;
use MyCloudPress\Services\SyncCommentmetaService;
use MyCloudPress\Services\SyncCommentService;
use MyCloudPress\Services\SyncPostService;
use MyCloudPress\Services\SyncPostmetaService;
use MyCloudPress\Services\SyncTermService;
use MyCloudPress\Services\SyncTermmetaService;
use MyCloudPress\Services\SyncOptionService;

class AdminEventsController extends Controller {

	protected $apiKey;
	protected $userModel;
	protected $usermetaModel;
	protected $commentmetaModel;
	protected $postmetaModel;
	protected $termmetaModel;
	protected $termRelationshipModel;
	protected $termTaxonomyModel;
	protected $optionModel;

	public function __construct()
	{
		$this->apiKey = mcp_api_key();
		$this->userModel = new User($this->apiKey);
		$this->usermetaModel = new Usermeta($this->apiKey);
		$this->commentmetaModel = new Commentmeta($this->apiKey);
		$this->postmetaModel = new Postmeta($this->apiKey);
		$this->termmetaModel = new Termmeta($this->apiKey);
		$this->termRelationshipModel = new TermRelationship($this->apiKey);
		$this->termTaxonomyModel = new TermTaxonomy($this->apiKey);
		$this->optionModel = new Option($this->apiKey);
	}

	public function onSaveUser($user_id)
	{
		$user = $this->userModel->getUser($user_id);

		if(is_null($user)) {
			return;
		}

		$syncUserService = new SyncUserService($this->apiKey);
		$syncUserService->saveUser($user);
	}

	public function onDeleteUser($user_id)
	{
		
	}

	public function onSavePost($post_id)
	{
		$post = get_post($post_id);

		if(is_null($post)) {
			return ;
		}

		$syncPostService = new SyncPostService($this->apiKey);
		$syncPostService->savePost($post);
	}

	public function onDeletePost($post_id) {
		$syncPostService = new SyncPostService($this->apiKey);
		$syncPostService->deletePost($post_id);
	}

	public function onSavePostmeta($meta_id)
	{
		$meta = get_post_meta_by_id($meta_id);

		if(is_null($meta)) {
			return ;
		}

		$syncPostmetaService = new SyncPostmetaService($this->apiKey);
		$syncPostmetaService->save($meta);
	}

	public function onDeletePostmeta($meta_ids)
	{
		$syncPostmetaService = new SyncPostmetaService($this->apiKey);
		$syncPostmetaService->delete($meta_ids);
	}

	public function onSaveComment($comment_ID, $comment_approved=null)
	{
		$comment = get_comment($comment_ID);

		if(is_null($comment)) {
			return ;
		}

		$syncCommentService = new SyncCommentService($this->apiKey);
		$syncCommentService->saveComment($comment);
	}

	public function onDeleteComment($comment_id)
	{
		$syncCommentService = new SyncCommentService($this->apiKey);
		$syncCommentService->deleteComment($comment_id);
	}

	public function onCreateTerm($term_id, $taxonomy)
	{
		$term = get_term($term_id);

		if(is_null($term)) {
			return ;
		}

		$syncPostService = new SyncTermService($this->apiKey);
		$syncPostService->saveTerm($term);
	}

	public function onSaveTerm($term_id, $tt_id=null, $taxonomy=null)
	{
		$term = get_term($term_id);

		if(is_null($term)) {
			return ;
		}

		$syncPostService = new SyncTermService($this->apiKey);
		$syncPostService->saveTerm($term);
	}

	public function onDeleteTerm($term_id, $tt_id=null, $taxonomy=null, $deleted_term=null, $object_ids=null)
	{
		$syncTermService = new SyncTermService($this->apiKey);
		$syncTermService->deleteTerm($term_id);
	}

	
	public function onAddedTermRelationship($object_id, $tt_id=null, $taxonomy=null) {
		$term = $this->termRelationshipModel->getByObject($object_id);

		if(is_null($term)) {
			return ;
		}

		$syncTermService = new SyncTermService($this->apiKey);
		$syncTermService->saveTermRelationship($term);
	}

	
	public function onDeletedTermRelationship($object_id, $tt_id=null, $taxonomy=null) {
		$syncTermService = new SyncTermService($this->apiKey);
		$syncTermService->deleteTermRelationship($object_id);
	}

	
	public function onEditedTermTaxonomy($tt_id=null, $taxonomy=null) {
		$term = $this->termTaxonomyModel->getByObject($tt_id);

		if(is_null($term)) {
			return ;
		}

		$syncTermService = new SyncTermService($this->apiKey);
		$syncTermService->saveTermTaxonomy($term);
	}

	
	public function onDeletedTermTaxonomy($object_id, $tt_id=null, $taxonomy=null) {
		$syncTermService = new SyncTermService($this->apiKey);
		$syncTermService->deleteTermTaxonomy($object_id);
	}

	public function onSaveOption($option_name, $old_value=null, $new_value=null)
	{
		$option = $this->optionModel->getOption($option_name);

		$syncOptionService = new SyncOptionService($this->apiKey);
		if(!is_null($option)) {
			$syncOptionService->updateOption($option);
		}
	}

	public function onDeleteOption($option)
	{
		$syncOptionService = new SyncOptionService($this->apiKey);
		$syncOptionService->deleteOption($option);
	}

	public function onAddedCommentMeta($mid)
	{
		$commentmeta = $this->commentmetaModel->getCommentmeta($mid);

		$syncCommentmetaService = new SyncCommentmetaService($this->apiKey);
		if(!is_null($commentmeta)) {
			$syncCommentmetaService->save($commentmeta);
		}
	}

	public function onDeleteCommentMeta($meta_ids)
	{
		$syncCommentmetaService = new SyncCommentmetaService($this->apiKey);
		$syncCommentmetaService->delete($meta_ids);
	}

	public function onAddedTermMeta($mid)
	{
		$termmeta = $this->termmetaModel->getTermmeta($mid);
		
		$syncTermmetaService = new SyncTermmetaService($this->apiKey);
		$syncTermmetaService->save($termmeta);
	}

	public function onDeleteTermMeta($meta_ids)
	{
		$syncTermmetaService = new SyncTermmetaService($this->apiKey);
		$syncTermmetaService->delete($meta_ids);
	}

	public function onSaveUsermeta($mid)
	{
		$usermeta = $this->usermetaModel->getUsermeta($mid);
		
		$syncUsermetaService = new SyncUsermetaService($this->apiKey);
		$syncUsermetaService->save($usermeta);
	}

	public function onDeleteUsermeta($meta_ids)
	{		
		$syncUsermetaService = new SyncUsermetaService($this->apiKey);
		$syncUsermetaService->delete($meta_ids);
	}
}