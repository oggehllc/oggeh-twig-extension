<?php
	/**
	 * Install Twig 1.x for php 5.3:
	 * composer require twig/twig:~1.0
	 * 
	 * Install Twig 2.x for php 5.4+:
	 * composer require twig/twig:~2.0
	 */
	
	require_once 'vendor/autoload.php';
	//Twig_Autoloader::register(); // uncomment for php 5.3
	$loader = new Twig_Loader_Filesystem('tpl');
	$twig = new Twig_Environment($loader, array(
		//'debug' => true, // uncomment for debug
		//'cache' => 'tmp'
		'cache' => false
	));
	//$twig->addExtension(new Twig_Extension_Debug()); // uncomment for debug

	/**
	 * Load OGGEH Extension
	 */
	require_once 'oggeh.twig.php';
	$twig->addRuntimeLoader(new RuntimeLoader());
	$twig->addExtension(new OGGEH_Twig_Extension());

	/**
	 * Parse URL
	 */
	$rtl_langs = array('ar', 'fa');
	$direction = 'ltr';
	$url_lang = 'en';
	$url_module = '';
	$url_child_id = '';
	$url_extra_id = '';
	$uri = $_SERVER['REQUEST_URI'];
	$pieces = parse_url($uri);
	$path = trim($pieces['path'], '/');
	$segments = explode('/', $path);
	if (count($segments)>0) {
		if (strlen($segments[0])>0) {
			$url_lang = $segments[0];
			$direction = (in_array($url_lang, $rtl_langs)) ? 'rtl' : 'ltr';
		}
		if (count($segments)>1) {
			$url_module = $segments[1];
			if (count($segments)>2) {
				$url_child_id = $segments[2];
				if (count($segments)>3) {
					$url_extra_id = $segments[3];
				}
			}
		}
	}

	/**
	 * Configure Developer Access
	 */
	OGGEH::configure('domain', 'domain.ltd');
	OGGEH::configure('api_key', '57ff136718d176aae148c8ce9aaf6817');
	OGGEH::configure('lang', $url_lang);
	// Enable development environment
	OGGEH::configure('api_secret', 'ca58365e824a4135a3c537b8f362a863');
	OGGEH::configure('sandbox', true);
	OGGEH::configure('i18n', array(
		'home'=>array(
			'en'=>'Home',
			'ar'=>'الرئيسية'
		),
		'menu'=>array(
			'en'=>'Menu',
			'ar'=>'القائمة'
		),
		'album'=>array(
			'en'=>'Gallery',
			'ar'=>'معرض الصور'
		),
		'all-news'=>array(
			'en'=>'News',
			'ar'=>'الأخبار'
		),
		'learn-more'=>array(
			'en'=>'Learn more',
			'ar'=>'اقرأ المزيد'
		),
		'highlights'=>array(
			'en'=>'Highlights',
			'ar'=>'مقتطفات'
		),
		'latest-news'=>array(
			'en'=>'Latest News',
			'ar'=>'آخر الأخبار'
		),
		'get-in-touch'=>array(
			'en'=>'Get in Touch',
			'ar'=>'تواصل معنا'
		),
		'contact-us'=>array(
			'en'=>'Contact us',
			'ar'=>'اتصل بنا'
		),
		'request-quote'=>array(
			'en'=>'Request a Quote',
			'ar'=>'استعلام عن الأسعار'
		),
		'continue-reading'=>array(
			'en'=>'Continue Reading',
			'ar'=>'اقرأ المزيد'
		),
		'showing-results-for'=>array(
			'en'=>'Showing results for',
			'ar'=>'عرض نتائج البحث عن'
		),
		'search-not-found'=>array(
			'en'=>'Not resuts found',
			'ar'=>'لا توجد نتائج'
		),
		'page'=>array(
			'en'=>'page',
			'ar'=>'صفحة'
		),
		'news'=>array(
			'en'=>'news',
			'ar'=>'خبر'
		),
		'your-name'=>array(
			'en'=>'Your Name',
			'ar'=>'الاسم'
		),
		'email-address'=>array(
			'en'=>'Email',
			'ar'=>'البريد الالكتروني'
		),
		'message'=>array(
			'en'=>'Message',
			'ar'=>'الرسالة'
		),
		'send-inquiry'=>array(
			'en'=>'Send',
			'ar'=>'ارسل'
		),
		'submit'=>array(
			'en'=>'Submit',
			'ar'=>'تسجيل'
		),
		'reset'=>array(
			'en'=>'Reset',
			'ar'=>'إفراغ'
		),
		'form-success'=>array(
			'en'=>'Thanks for your enquiry, we\'ll be in touch shortly.',
			'ar'=>'شكرا جزيلا، سيتم الاتصال بك في أقرب وقت.'
		),
		'form-error'=>array(
			'en'=>'Please fill in all fields correctly.',
			'ar'=>'رجاء ملء جميع الحقول بشكل صحيح.'
		),
	  'category'=>array(
	    'en'=>'Category',
	    'ar'=>'التصنيف'
	  ),
	  'client'=>array(
	    'en'=>'Client',
	    'ar'=>'العميل'
	  ),
	  'page-not-found'=>array(
	    'en'=>'The page you were looking for doesn\'t appear to exist',
	    'ar'=>'الصفحة التي تبحث عنها غير موجودة'
	  ),
	  'back-to-home'=>array(
	    'en'=>'Go back to home page',
	    'ar'=>'العودة الى الصفحة الرئيسية'
	  ),
	  'under-maintenance'=>array(
	    'en'=>'Under Maintenance',
	    'ar'=>'تحت الاشناء'
	  ),
	  'under-maintenance-message'=>array(
	    'en'=>'We\'re updating our content, come back later!',
	    'ar'=>'نقوم بتحديث المحتوى، عد لزيارتنا لاقحا!'
	  )
	));

	/**
	 * Render App
	 */
	if ($url_module != '' && !is_file('tpl/'.$url_module.'.html') || strlen($url_lang) != 2) {
		$url_module = '404';
	} elseif ($url_module == 'index' || $url_module == '') {
		$url_module = 'home';
	}
	echo $twig->render($url_module.'.html', array(
		'uri'=>$uri,
		'lang'=>$url_lang,
		'dir'=>$direction,
		'module'=>$url_module,
		'child_id'=>$url_child_id,
		'extra_id'=>$url_extra_id
	));

	/**
	 * NOTES
	 * this script must be called only once per page request
	 * make sure to include a valid favicon
	 * avoid using blank or invalid source path in img tags
	 */
	//error_log('request made on: '.date('H:i:s d/m/Y'));
?>
