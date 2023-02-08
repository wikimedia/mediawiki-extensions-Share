<?php

use MediaWiki\MediaWikiServices;

class ShareHooks {
	public static function onSidebarBeforeOutput( Skin $skin, &$sidebar ) {
		$services = MediaWikiServices::getInstance();

		$config = $services->getConfigFactory()->makeConfig( 'Share' );
		$extensionAssetsPath = $config->get( 'ExtensionAssetsPath' );
		$shareFacebook = $config->get( 'ShareFacebook' );
		$shareTwitter = $config->get( 'ShareTwitter' );
		$shareLinkedin = $config->get( 'ShareLinkedIn' );
		$shareTumblr = $config->get( 'ShareTumblr' );
		$shareReddit = $config->get( 'ShareReddit' );
		$shareEmail = $config->get( 'ShareEmail' );
		$shareUseBasicButtons = $config->get( 'ShareUseBasicButtons' );
		$shareUsePlainLinks = $config->get( 'ShareUsePlainLinks' );

		// Get title
		$query = $skin->getRequest()->getQueryValues();
		if ( isset( $query['title'] ) ) {
			unset( $query['title'] );
		}

		// Allows us to get the full URL path without having to use $_SERVER and such
		$currenturl = $skin->getTitle()->getFullURL( $query, false, PROTO_CANONICAL );

		// Allow us to store preferences on users so that users can enable or disable the sidebar and to check if user has permission to see sidebar links
		$user = $skin->getUser();
		$permissionManager = $services->getPermissionManager();
		$userOptionsLookup = $services->getUserOptionsLookup();

		// Check if the user has the right to see the links
		if ( $permissionManager->userHasRight( $user, 'viewsharelinks' ) ) {
			// If Share is disabled in the User preferences then don't show (Default is enabled)
			if ( !$userOptionsLookup->getOption( $user, 'sharesidebar', 0 ) ) {
				// 'Full' Mode - Displays buttons straight from each platform's social plugin library
				if ( !$shareUseBasicButtons && !$shareUsePlainLinks ) {
					if ( $shareFacebook ) {
						$sidebar['share-header'][] = [
							'html' => '<iframe src="https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2F' . urlencode( $currenturl ) . '&layout=button&size=small&width=67&height=20&appId" width="67" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>',
						];
					}

					if ( $shareTwitter ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://twitter.com/share" class="twitter-share-button" rel="nofollow" data-dnt="true" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>',
						];
					}

					if ( $shareLinkedIn ) {
						$sidebar['share-header'][] = [
							'html' => '<script src="https://platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script> <script type="IN/Share" data-url="https://www.linkedin.com"></script>',
						];
					}

					if ( $shareTumblr ) {
						$sidebar['share-header'][] = [
							'html' => '<a class="tumblr-share-button" href="https://www.tumblr.com/share"></a><script id="tumblr-js" async src="https://assets.tumblr.com/share-button.js"></script>',
						];
					}
				}

				// 'Sidebar images' mode - Doesn't load the button from each platform's social plugin library but instead displays images saying "Share"
				if ( $shareUseBasicButtons && !$shareUsePlainLinks ) {
					if ( $shareEmail ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="mailto:%20?body=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/email.png" alt="'.wfMessage( 'share-email' )->plain().'" width="90" height="30"></a>',
						];
					}

					if ( $shareFacebook ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/facebook.png" alt="'.wfMessage( 'share-facebook' )->plain().'" width="90" height="30"></a>',
						];
					}

					if ( $shareLinkedIn ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/linkedin.png" alt="'.wfMessage( 'share-linkedin' )->plain().'" width="90" height="30"></a>',
						];
					}

					if ( $shareReddit ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.reddit.com/submit?url=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/reddit.png" alt="'.wfMessage( 'share-reddit' )->plain().'" width="90" height="30"></a>',
						];
					}

					if ( $shareTumblr ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.tumblr.com/share/link?url=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/tumblr.png" alt="'.wfMessage( 'share-tumblr' )->plain().'" width="90" height="30"></a>',
						];
					}

					if ( $shareTwitter ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.twitter.com/share?url=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/twitter.png" alt="'.wfMessage( 'share-twitter' )->plain().'" width="90" height="30"></a>',
						];
					}
				}

				// 'Plain Sidebar Links' mode - Displays all "Share" buttons as plain sidebar links as if they were any other link in the sidebar
				if ( !$shareUseBasicButtons && $shareUsePlainLinks ) {
					if ( $shareFacebook ) {
						$sidebar['share-header'][] = [
							'text' => wfMessage( 'share-facebook' )->plain(),
							'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $currenturl ),
							'title' => wfMessage( 'share-facebook' )->plain(),
							'id' => 'n-facebookshare',
						];
					}

					if ( $shareTwitter ) {
						$sidebar['share-header'][] = [
							'text' => wfMessage( 'share-twitter' )->plain(),
							'href' => 'https://www.twitter.com/share?url=' . urlencode( $currenturl ),
							'title' => wfMessage( 'share-twitter' )->plain(),
							'id' => 'n-twittershare',
						];
					}

					if ( $shareEmail ) {
						$sidebar['share-header'][] = [
							'text' => wfMessage( 'share-email' )->plain(),
							'href' => 'mailto:%20?body=' . urlencode( $currenturl ),
							'title' => wfMessage( 'share-email' )->plain(),
							'id' => 'n-emailshare',
						];
					}

					if ( $shareLinkedIn ) {
						$sidebar['share-header'][] = [
							'text' => wfMessage( 'share-linkedin' )->plain(),
							'href' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $currenturl ),
							'title' => wfMessage( 'share-linkedin' )->plain(),
							'id' => 'n-linkedinshare',
						];
					}

					if ( $shareReddit ) {
						$sidebar['share-header'][] = [
							'text' => wfMessage( 'share-reddit' )->plain(),
							'href' => 'https://www.reddit.com/submit?url=' . urlencode( $currenturl ),
							'title' => wfMessage( 'share-reddit' )->plain(),
							'id' => 'n-redditshare',
						];
					}

					if ( $shareTumblr ) {
						$sidebar['share-header'][] = [
							'text' => wfMessage( 'share-tumblr' )->plain(),
							'href' => 'https://www.tumblr.com/share/link?url=' . urlencode( $currenturl ),
							'title' => wfMessage( 'share-tumblr' )->plain(),
							'id' => 'n-tumblrshare',
						];
					}
				}
			}
		}
	}

	public static function onGetPreferences( User $user, array &$preferences ) {
		$preferences['sharesidebar'] = [
			'type' => 'toggle',
			'label-message' => 'share-preftoggle',
			'section' => 'rendering',
		];
	}
}
