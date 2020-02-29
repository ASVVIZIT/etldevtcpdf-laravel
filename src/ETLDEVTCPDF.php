<?php

namespace asvvizit\ETLDEVTCPDF;

use Illuminate\Support\Facades\Config;

class ETLDEVTCPDF
{
    protected static $format;

    protected $app;
    /** @var  ETLDEVTCPDFHelper */
    protected $tcpdf;

    public function __construct($app)
    {
        $this->app = $app;
        $this->reset();
    }

    public function reset()
    {
        $class = Config::get('etldevtcpdf.use_fpdi') ? FpdiETLDEVTCPDFHelper::class : ETLDEVTCPDFHelper::class;

        $this->tcpdf = new $class(
            Config::get('etldevtcpdf.page_orientation', 'P'),
            Config::get('etldevtcpdf.page_units', 'mm'),
            static::$format ? static::$format : Config::get('etldevtcpdf.page_format', 'A4'),
            Config::get('etldevtcpdf.unicode', true),
            Config::get('etldevtcpdf.encoding', 'UTF-8'),
            false, // Diskcache is deprecated
            Config::get('etldevtcpdf.pdfa', false)
        );
    }

    public static function changeFormat($format)
    {
        static::$format = $format;
    }

    public function __call($method, $args)
    {
        if (method_exists($this->tcpdf, $method)) {
            return call_user_func_array([$this->tcpdf, $method], $args);
        }
        throw new \RuntimeException(sprintf('the method %s does not exists in ETLDEVTCPDF', $method));
    }

    public function setHeaderCallback($headerCallback)
    {
        $this->tcpdf->setHeaderCallback($headerCallback);
    }

    public function setFooterCallback($footerCallback)
    {
        $this->tcpdf->setFooterCallback($footerCallback);
    }
}
