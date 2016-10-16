<?php

namespace Vovayatsyuk\Alsoviewed\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Check is ip address is in ignore list
     *
     * @return boolean
     */
    public function isIpAddressIgnored($ipAddress = null)
    {
        if (!$this->scopeConfig->getValue('alsoviewed/log/ignore_ip_address')) {
            return false;
        }
        if (!$ignoredIpAddresses = $this->scopeConfig->getValue('alsoviewed/log/ignored_ip_address')) {
            return false;
        }

        if (null === $ipAddress) {
            $ipAddress = $this->_remoteAddress->getRemoteAddress();
            if (!$ipAddress) {
                return false;
            }
        }

        $ignoredIpAddresses = explode(',', $ignoredIpAddresses);
        return in_array($ipAddress, $ignoredIpAddresses);
    }

    /**
     * Check is user agent is in ignore list
     *
     * @return boolean
     */
    public function isUserAgentIgnored($userAgent = null)
    {
        if (!$this->scopeConfig->getValue('alsoviewed/log/ignore_user_agent')) {
            return false;
        }
        if (!$ignoredUserArgents = $this->scopeConfig->getValue('alsoviewed/log/ignored_user_agent')) {
            return false;
        }

        if (null === $userAgent) {
            $userAgent = $this->_httpHeader->getHttpUserAgent();
            if (!$userAgent) {
                return false;
            }
        }

        $regexp = '/' . trim($ignoredUserArgents, '/') . '/';
        return (bool)@preg_match($regexp, $userAgent);
    }
}
