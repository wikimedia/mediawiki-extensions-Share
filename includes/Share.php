<?php

use MediaWiki\MediaWikiServices;

class ShareHooks {
	public static function onSidebarBeforeOutput( Skin $skin, &$sidebar ) {
		$services = MediaWikiServices::getInstance();

		$config = $services->getConfigFactory()->makeConfig( 'Share' );
		$extensionAssetsPath = $config->get( 'ExtensionAssetsPath' );
		$shareEmail = $config->get( 'ShareEmail' );
		$shareFacebook = $config->get( 'ShareFacebook' );
		$shareLinkedin = $config->get( 'ShareLinkedIn' );
		$shareReddit = $config->get( 'ShareReddit' );
		$shareTelegram = $config->get( 'ShareTelegram' );
		$shareTumblr = $config->get( 'ShareTumblr' );
		$shareTwitter = $config->get( 'ShareTwitter' );
		$shareVK = $config->get( 'ShareVK' );
		$shareWeibo = $config->get( 'ShareWeibo' );
		$shareWhatsApp = $config->get( 'ShareWhatsApp' );
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
							'html' => '<a href="mailto:%20?body=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/email.png" alt="'.$skin->msg( 'share-email' )->escaped().'" width="90" height="30"></a>',
						];
					}

					if ( $shareFacebook ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/facebook.png" alt="'.$skin->msg( 'share-facebook' )->escaped().'" width="90" height="30"></a>',
						];
					}

					if ( $shareLinkedIn ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/linkedin.png" alt="'.$skin->msg( 'share-linkedin' )->escaped().'" width="90" height="30"></a>',
						];
					}

					if ( $shareReddit ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.reddit.com/submit?url=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/reddit.png" alt="'.$skin->msg( 'share-reddit' )->escaped().'" width="90" height="30"></a>',
						];
					}

					if ( $shareTumblr ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.tumblr.com/share/link?url=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/tumblr.png" alt="'.$skin->msg( 'share-tumblr' )->escaped().'" width="90" height="30"></a>',
						];
					}

					if ( $shareTwitter ) {
						$sidebar['share-header'][] = [
							'html' => '<a href="https://www.twitter.com/share?url=' . urlencode( $currenturl ).'"><img src="'.$extensionAssetsPath.'/Share/resources/images/twitter.png" alt="'.$skin->msg( 'share-twitter' )->escaped().'" width="90" height="30"></a>',
						];
					}
				}

				// 'Plain Sidebar Links' mode - Displays all "Share" buttons as plain sidebar links as if they were any other link in the sidebar
				if ( !$shareUseBasicButtons && $shareUsePlainLinks ) {
					if ( $shareEmail ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-email' )->escaped(),
							'href' => 'mailto:%20?body=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-email' )->escaped(),
							'id' => 'n-emailshare',
						];
					}

					if ( $shareFacebook ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-facebook' )->escaped(),
							'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-facebook' )->escaped(),
							'id' => 'n-facebookshare',
						];
					}

					if ( $shareLinkedIn ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-linkedin' )->escaped(),
							'href' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-linkedin' )->escaped(),
							'id' => 'n-linkedinshare',
						];
					}

					if ( $shareReddit ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-reddit' )->escaped(),
							'href' => 'https://www.reddit.com/submit?url=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-reddit' )->escaped(),
							'id' => 'n-redditshare',
						];
					}

					if ( $shareTelegram ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-telegram' )->escaped(),
							'href' => 'https://t.me/share/url?url=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-telegram' )->escaped(),
							'id' => 'n-telegramshare',
						];
					}

					if ( $shareTumblr ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-tumblr' )->escaped(),
							'href' => 'https://www.tumblr.com/share/link?url=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-tumblr' )->escaped(),
							'id' => 'n-tumblrshare',
						];
					}

					if ( $shareTwitter ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-twitter' )->escaped(),
							'href' => 'https://www.twitter.com/share?url=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-twitter' )->escaped(),
							'id' => 'n-twittershare',
						];
					}

					if ( $shareVK ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-vk' )->escaped(),
							'href' => 'https://vk.com/share.php?url=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-vk' )->escaped(),
							'id' => 'n-weiboshare',
						];
					}

					if ( $shareWeibo ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-weibo' )->escaped(),
							'href' => 'https://service.weibo.com/share/share.php?url=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-weibo' )->escaped(),
							'id' => 'n-weiboshare',
						];
					}

					if ( $shareWhatsApp ) {
						$sidebar['share-header'][] = [
							'text' => $skin->msg( 'share-whatsapp' )->escaped(),
							'href' => 'https://wa.me/?text=' . urlencode( $currenturl ),
							'title' => $skin->msg( 'share-whatsapp' )->escaped(),
							'id' => 'n-whatsappshare',
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
