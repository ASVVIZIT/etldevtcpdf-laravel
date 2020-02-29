<?php

namespace asvvizit\ETLDEVTCPDF;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Class EtlServiceProvider
 * @version 1.0
 * @package asvvizit\ETLDEVTCPDF
 */
class EtlServiceProvider extends LaravelServiceProvider
{
	protected $constantsMap = [
		'K_PATH_MAIN'                   => 'path_main',
		'K_PATH_URL'                    => 'path_url',
		'K_PATH_FONTS'                  => 'font_directory',
		'K_PATH_IMAGES'                 => 'image_directory',
		'PDF_HEADER_LOGO'               => 'header_logo',
		'PDF_HEADER_LOGO_WIDTH'         => 'header_logo_width',
		'K_PATH_CACHE'                  => 'path_cache',
		'K_BLANK_IMAGE'                 => 'blank_image',
		'PDF_PAGE_FORMAT'               => 'page_format',
		'PDF_PAGE_ORIENTATION'          => 'page_orientation',
		'PDF_CREATOR'                   => 'creator',
		'PDF_AUTHOR'                    => 'author',
		'PDF_HEADER_TITLE'              => 'header_title',
		'PDF_HEADER_STRING'             => 'header_string',
		'PDF_UNIT'                      => 'page_units',
		'PDF_MARGIN_HEADER'             => 'margin_header',
		'PDF_MARGIN_FOOTER'             => 'margin_footer',
		'PDF_MARGIN_TOP'                => 'margin_top',
		'PDF_MARGIN_BOTTOM'             => 'margin_bottom',
		'PDF_MARGIN_LEFT'               => 'margin_left',
		'PDF_MARGIN_RIGHT'              => 'margin_right',
		'PDF_FONT_NAME_MAIN'            => 'font_name_main',
		'PDF_FONT_SIZE_MAIN'            => 'font_size_main',
		'PDF_FONT_NAME_DATA'            => 'font_name_data',
		'PDF_FONT_SIZE_DATA'            => 'font_size_data',
		'PDF_FONT_MONOSPACED'           => 'foto_monospaced',
		'PDF_IMAGE_SCALE_RATIO'         => 'image_scale_ratio',
		'HEAD_MAGNIFICATION'            => 'head_magnification',
		'K_CELL_HEIGHT_RATIO'           => 'cell_height_ratio',
		'K_TITLE_MAGNIFICATION'         => 'title_magnification',
		'K_SMALL_RATIO'                 => 'small_ratio',
		'K_THAI_TOPCHARS'               => 'thai_topchars',
		'K_ETLDEVTCPDF_CALLS_IN_HTML'         => 'etldevtcpdf_calls_in_html',
		'K_ETLDEVTCPDF_THROW_EXCEPTION_ERROR' => 'etldevtcpdf_throw_exception',
		'K_TIMEZONE'                    => 'timezone',
	];

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$configPath = dirname(__FILE__) . '/../config/etldevtcpdf.php';
		$this->mergeConfigFrom($configPath, 'etldevtcpdf');
		$this->app->singleton('etldevtcpdf', function ($app) {
			return new ETLDEVTCPDF($app);
		});
	}

	public function boot()
	{
		if (!defined('K_ETLDEVTCPDF_EXTERNAL_CONFIG')) {
			define('K_ETLDEVTCPDF_EXTERNAL_CONFIG', true);
		}
		foreach ($this->constantsMap as $key => $value) {
			$value = Config::get('etldevtcpdf.' . $value, null);
			if (!is_null($value) && !defined($key)) {
				if (is_string($value) && strlen($value) == 0) {
					continue;
				}
				define($key, $value);
			}
		}
		$configPath = dirname(__FILE__) . '/../config/etldevtcpdf.php';
		if (function_exists('config_path')) {
			$targetPath = config_path('etldevtcpdf.php');
		} else {
			$targetPath = app()->basePath() . '/config/etldevtcpdf.php';
		}
		$this->publishes(array($configPath => $targetPath), 'config');
	}

	public function provides()
	{
		return ['pdf'];
	}
}
