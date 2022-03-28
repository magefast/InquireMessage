<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

namespace Dragonfly\InquireMessage\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class Button extends Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Context              $context,
        Registry             $registry,
        ScopeConfigInterface $scopeConfig,
        array                $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return false|mixed
     */
    private function getCurrentProduct()
    {
        $product = $this->registry->registry('current_product');

        if ($product && $product->getId()) {
            return $product;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $currentProduct = $this->getCurrentProduct();

        if ($currentProduct == false) {
            return false;
        }

        if ($currentProduct->isAvailable() == false) {
            return false;
        }

        $settings = $this->scopeConfig->isSetFlag('inquiremessage/settings/status');
        if ($settings == true) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getUriString()
    {
        $phone = $this->scopeConfig->getValue('inquiremessage/settings/phone_number');
        $phone = str_replace(['+', '-', ' '], '', $phone);

        $text = $this->scopeConfig->getValue('inquiremessage/settings/text');
        $text = urlencode($text);

        return 'https://wa.me/' . $phone . '?text=' . $text;
    }

}