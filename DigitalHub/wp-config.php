<?php
/**
 * إعدادات الووردبريس الأساسية
 *
 * عملية إنشاء الملف wp-config.php تستخدم هذا الملف أثناء التنصيب. لا يجب عليك
 * استخدام الموقع، يمكنك نسخ هذا الملف إلى "wp-config.php" وبعدها ملئ القيم المطلوبة.
 *
 * هذا الملف يحتوي على هذه الإعدادات:
 *
 * * إعدادات قاعدة البيانات
 * * مفاتيح الأمان
 * * بادئة جداول قاعدة البيانات
 * * المسار المطلق لمجلد الووردبريس
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** إعدادات قاعدة البيانات - يمكنك الحصول على هذه المعلومات من مستضيفك ** //

/** اسم قاعدة البيانات لووردبريس */
define( 'DB_NAME', 'digitalhub' );

/** اسم مستخدم قاعدة البيانات */
define( 'DB_USER', 'root' );

/** كلمة مرور قاعدة البيانات */
define( 'DB_PASSWORD', '' );

/** عنوان خادم قاعدة البيانات */
define( 'DB_HOST', 'localhost' );

/** ترميز قاعدة البيانات */
define( 'DB_CHARSET', 'utf8mb4' );

/** نوع تجميع قاعدة البيانات. لا تغير هذا إن كنت غير متأكد */
define( 'DB_COLLATE', '' );

/**#@+
 * مفاتيح الأمان.
 *
 * استخدم الرابط التالي لتوليد المفاتيح {@link https://api.wordpress.org/secret-key/1.1/salt/}
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'I6FAGGGb@k3.D/s@u!4HJe3]{C| >CU4+|{o|8G07*1{}153_c,]ez;r:%B0RD^i' );
define( 'SECURE_AUTH_KEY',  'W3s !`<5~|!_){mn!oKopxF}idkm]DR}|@sG=_3rzn<tp~(Ps{[nU,8iT:Fr=A[m' );
define( 'LOGGED_IN_KEY',    '[49*&P,hz(*)Ynx500SPVwNX;a.GYRm+cd<lCZ[.(XWSHjin;F~~x64}5Py6!I/?' );
define( 'NONCE_KEY',        'nCUax}WzX)ycqlk!nccHX*oVDev}uBt=A5#,1u{nLR$)Iq-zUM(tl>.edllk$hh/' );
define( 'AUTH_SALT',        '*%cTu?[YqdgqP3/36k)Di-E@-UkFb=gmEB4X,=8jo0R7O<0k6juS<N?X4JM/2,)$' );
define( 'SECURE_AUTH_SALT', 'V}Ds|%PDl5b]qlif2Xr8<)snFdWM?{xVkd]j<]Lt _]V|:H;3X/HGwq^QAmnb]AH' );
define( 'LOGGED_IN_SALT',   '+fI,L`>BPRkQ<EsFz*2n8_K~iJic=5-CwP[sm`P`|sl)HM&102&dHXT0/<CfuJSH' );
define( 'NONCE_SALT',       'PD(P9St1FidmNNkPrsqUI&^)?Lf}mK`|j|fl:W1YwHEmgfG#x^g46J{l4ObAcA~t' );

/**#@-*/

/**
 * بادئة الجداول في قاعدة البيانات.
 *
 * تستطيع تركيب أكثر من موقع على نفس قاعدة البيانات إذا أعطيت لكل موقع بادئة جداول مختلفة
 * يرجى استخدام حروف، أرقام وخطوط سفلية فقط!
 */
$table_prefix = 'wp_';

/**
 * للمطورين: نظام تشخيص الأخطاء
 *
 * قم بتغييرالقيمة، إن أردت تمكين عرض الملاحظات والأخطاء أثناء التطوير.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* هذا هو المطلوب، توقف عن التعديل! نتمنى لك التوفيق. */

/** المسار المطلق لمجلد ووردبريس. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** إعداد متغيرات الووردبريس وتضمين الملفات. */
require_once ABSPATH . 'wp-settings.php';
