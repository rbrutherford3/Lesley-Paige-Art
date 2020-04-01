<?php

function definepaths() {
	$ds = DIRECTORY_SEPARATOR;
	$dsHTML = '/';
	$svrroot = $_SERVER['DOCUMENT_ROOT'];
	if (substr(__DIR__, -1) == $ds) {
		$root = __DIR__;
		$rootHTML = $dsHTML;
	}
	else {
		$root = __DIR__ . $ds;
		if (substr($svrroot, -1) == $ds) {
			$dirstart = strlen($svrroot) - 1;
		}
		else {
			$dirstart = strlen($svrroot);
		}
		$rootHTML = str_replace($ds, $dsHTML, substr($root, $dirstart));
	}
	
	$ext = 'png';

	$img = $root . 'img' . $ds;
	$imgHTML = $rootHTML . 'img' . $dsHTML;
	$header = $img . 'header' . $ds;
	$headerHTML = $imgHTML . 'header' . $dsHTML;
	$css = $root . 'css' . $ds;
	$cssHTML = $rootHTML . 'css' . $dsHTML;
	$js = $root . 'js' . $ds;
	$jsHTML = $rootHTML . 'js' . $dsHTML;
	$admin = $root . 'admin' . $ds;
	$adminHTML = $rootHTML . 'admin' . $dsHTML;
	$upload = $admin . 'upload' . $ds;
	$uploadHTML = $adminHTML . 'upload' . $dsHTML;

	$cssmain = $css . 'main.css';
	$cssmainHTML = $cssHTML . 'main.css';
	$csstext = $css . 'text.css';
	$csstextHTML = $cssHTML . 'text.css';
	$cssadmin = $admin . 'admin.css';
	$cssadminHTML = $adminHTML . 'admin.css';
	$bootstrapcss = $css . 'bootstrap.min.css';
	$bootstrapcssHTML = $cssHTML . 'bootstrap.min.css';
	$bootstrapjs = $js . 'bootstrap.min.js';
	$bootstrapjsHTML = $jsHTML . 'bootstrap.min.js';
	
	$stampfull = $admin . 'stamp.png';
	$stampfullHTML = $adminHTML . 'stamp.png';

	$favicon = $img . 'favicon.ico';
	$faviconHTML = $imgHTML . 'favicon.ico';
	$photofull = $img . 'photo.jpg';
	$photofullHTML = $imgHTML . 'photo.jpg';

	$photo = $header . 'photo.png';
	$photoHTML = $headerHTML . 'photo.png';
	$stamp = $header . 'stamp.png';
	$stampHTML = $headerHTML . 'stamp.png';
	$title = $header . 'title.png';
	$titleHTML = $headerHTML . 'title.png';
	$titlesmall = $header . 'titlesmall.png';
	$titlesmallHTML = $headerHTML . 'titlesmall.png';

	$originals = $img . 'originals' . $ds;
	$originalsHTML = $imgHTML . 'originals' . $dsHTML;
	$formatted = $img . 'formatted' . $ds;
	$formmatedHTML = $imgHTML . 'formatted' . $dsHTML;
	$cropped = $img . 'cropped' . $ds;
	$croppedHTML = $imgHTML . 'cropped' . $dsHTML;
	$watermarked = $img . 'watermarked' . $ds;
	$watermarkedHTML = $imgHTML . 'watermarked' . $dsHTML;
	$thumbnails = $img . 'thumbnails' . $ds;
	$thumbnailsHTML = $imgHTML . 'thumbnails' . $dsHTML;

	define('DS', $ds);
	define('DS_HTML', $dsHTML);
	define('EXT', $ext);

	define('ROOT', $root);
	define('ROOT_HTML', $rootHTML);
	define('IMG', $img);
	define('IMG_HTML', $imgHTML);
	define('HEADER', $header);
	define('HEADER_HTML', $headerHTML);
	define('CSS', $css);
	define('CSS_HTML', $cssHTML);
	define('JS', $js);
	define('JS_HTML', $jsHTML);
	define('ADMIN', $admin);
	define('ADMIN_HTML', $adminHTML);
	define('UPLOAD', $upload);
	define('UPLOAD_HTML', $uploadHTML);

	define('CSS_MAIN', $cssmain);
	define('CSS_MAIN_HTML', $cssmainHTML);
	define('CSS_TEXT', $csstext);
	define('CSS_TEXT_HTML', $csstextHTML);
	define('CSS_ADMIN', $cssadmin);
	define('CSS_ADMIN_HTML', $cssadminHTML);
	define('BOOTSTRAP_CSS', $bootstrapcss);
	define('BOOTSTRAP_CSS_HTML', $bootstrapcssHTML);
	define('BOOTSTRAP_JS', $bootstrapjs);
	define('BOOTSTRAP_JS_HTML', $bootstrapjsHTML);
	
	define('STAMP_FULL', $stampfull);
	define('STAMP_FULL_HTML', $stampfullHTML);	

	define('FAVICON', $favicon);
	define('FAVICON_HTML', $faviconHTML);
	define('PHOTO_FULL', $photofull);
	define('PHOTO_FULL_HTML', $photofullHTML);

	define('PHOTO', $photo);
	define('PHOTO_HTML', $photoHTML);
	define('STAMP', $stamp);
	define('STAMP_HTML', $stampHTML);
	define('TITLE', $title);
	define('TITLE_HTML', $titleHTML);
	define('TITLE_SMALL', $titlesmall);
	define('TITLE_SMALL_HTML', $titlesmallHTML);

	define('ORIGINALS', $originals);
	define('ORIGINALS_HTML', $originalsHTML);
	define('FORMATTED', $formatted);
	define('FORMATTED_HTML', $formmatedHTML);
	define('CROPPED', $cropped);
	define('CROPPED_HTML', $croppedHTML);
	define('WATERMARKED', $watermarked);
	define('WATERMARKED_HTML', $watermarkedHTML);
	define('THUMBNAILS', $thumbnails);
	define('THUMBNAILS_HTML', $thumbnailsHTML);
	
	//TEMPORARY:
	define('UPLOAD_ORIGINAL', 'original');
	define('UPLOAD_FORMATTED','formatted');
	define('UPLOAD_CROPPED', 'cropped');
	define('UPLOAD_WATERMARKED', 'watermarked');
	define('UPLOAD_THUMBNAIL', 'thumbnail');
}

definepaths();

?>
