<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Framework\App\Config\Scope;

interface ReaderPoolInterface
{
    /**
     * Retrieve reader by scope
     *
     * @param string $scopeType
     * @return ReaderInterface|null
     */
    public function getReader($scopeType);
}
