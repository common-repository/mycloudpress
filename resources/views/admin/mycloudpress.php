<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div style="display: none" id="backedup-success" class="updated notice">
	    <p>Backed up successfully!</p>
	</div>
    <div style="display: none" id="restore-success" class="updated notice">
	    <p>Restored successfully!</p>
	</div>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					<div id="dashboard_right_now" class="postbox" style="display: block">
						<h2 class="hndle" style="cursor: initial"><span>Sync Stats</span></h2>
						<div class="inside">
							<div class="main">
								<ul>
									<li class="user-count"><span id="user-stats"></span></li>
									<li class="user-count"><span id="usermeta-stats"></span></li>
									<li class="post-count"><span id="post-stats"></span></li>
									<li class="post-count"><span id="postmeta-stats"></span></li>
									<li class="comment-count"><span id="comment-stats"></span></li>
									<li class="comment-count"><span id="commentmeta-stats"></span></li>
									<li class="page-count"><span id="option-stats"></span></li>
								</ul>
								<p id="wp-version-message"><span id="wp-version">MyCloudPress <?php echo MCP_BBTW_VERSION; ?></span></p>
							</div>
						</div>
					</div>
				</div>

				<div class="meta-box-sortables ui-sortable">
					<div id="dashboard_right_now" class="postbox" style="display: block">
						<h2 class="hndle" style="cursor: initial"><span>Your Site Stats</span></h2>
						<div class="inside">
							<div class="main">
								<ul>
									<li class="user-count"><span id="user-site-stats"></span></li>
									<li class="user-count"><span id="usermeta-site-stats"></span></li>
									<li class="post-count"><span id="post-site-stats"></span></li>
									<li class="post-count"><span id="postmeta-site-stats"></span></li>
									<li class="comment-count"><span id="comment-site-stats"></span></li>
									<li class="comment-count"><span id="commentmeta-site-stats"></span></li>
									<li class="page-count"><span id="option-site-stats"></span></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="postbox-container-2" class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					<div id="dashboard_quick_press" class="postbox">
						<h2 class="hndle ui-sortable-handle"><span>Syncing controls</span></h2>
						<div class="inside">
							<form name="post" action="" method="post" id="quick-press" class="initial-form hide-if-no-js">
								<p class="submit">
									<input type="submit" name="backup" id="backup-button" class="button button-primary" value="Backup" data-nonce="<?php echo wp_create_nonce( 'mycloudpress_backup' ); ?>">
									<input type="submit" name="restore" id="restore-button" class="button button-secondary" value="Restore" data-nonce="<?php echo wp_create_nonce( 'mycloudpress_restore' ); ?>">
									<span class="spinner" style="float: none"></span>
									<br class="clear">
								</p>
							</form>
						</div>
					</div>
				</div>
				<div class="meta-box-sortables ui-sortable">
					<div id="dashboard_right_now" class="postbox">
						<h2 class="hndle ui-sortable-handle"><span>Syncing FAQs</span></h2>
						<div class="inside">
							<div class="main">
								<p><b>Do I still own my data?</b></p>
								<p><b>Yes</b> you own every part of your data! We just hold it for you, kind of like a cloud for storing your files.</p>
								<p><b>Do you charge to store my data?</b></p>
								<p>We have a totally free version. If you exceed the storage limits we do have plans!</p>
								<p><b>Do you use/distribute anything with my data?</b></p>
								<p><b>No</b> we don't use your data in any way accept to show your data to you or restore the data on your behalf! You are fully in control of your data!</p>
								<p><b>How does it work?</b></p>
								<p>When you update content or create content it's automatically synced with the cloud. You have peace of mind that if you lose your database you have a copy of it on the cloud! The database is the heart of WordPress!</p>
								<p><b>I already backup my WordPress?</b></p>
								<p>That's great you can't have too many ways to get your blog or website back up & running! When you put a ton of hours into blogging & filling the internet with more data you don't want that data to be gone in a matter of seconds! Think of it like your second home!</p>
								<p><b>Why can't I schedule my backup?</b></p>
								<p>You don't need a scheduler, it happens in realtime! When you create something it's replicated on the cloud, it's like having your blog or website in two diffrent places but one is shown to the internet & one is private!</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>