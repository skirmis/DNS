<?php

namespace Badcow\DNS\Rdata;

/**
 * @see https://tools.ietf.org/html/rfc3123
 */
class APL implements RdataInterface
{
    use RdataTrait;

    const TYPE = 'APL';

    /**
     * @var \IPBlock[]
     */
    private $includedAddressRanges = [];

    /**
     * @var \IPBlock[]
     */
    private $excludedAddressRanges = [];

    /**
     * @param \IPBlock $ipBlock
     * @param bool     $included True if the resource exists within the range, False if the resource
     *                           is not within the range. I.E. the negation.
     */
    public function addAddressRange(\IPBlock $ipBlock, $included = true): void
    {
        if ($included) {
            $this->includedAddressRanges[] = $ipBlock;
        } else {
            $this->excludedAddressRanges[] = $ipBlock;
        }
    }

    /**
     * @return \IPBlock[]
     */
    public function getIncludedAddressRanges(): array
    {
        return $this->includedAddressRanges;
    }

    /**
     * @return \IPBlock[]
     */
    public function getExcludedAddressRanges(): array
    {
        return $this->excludedAddressRanges;
    }

    /**
     * {@inheritdoc}
     */
    public function output(): string
    {
        $string = '';
        foreach ($this->includedAddressRanges as $ipBlock) {
            $string .= (4 === $ipBlock->getVersion()) ? '1:' : '2:';
            $string .= (string) $ipBlock.' ';
        }

        foreach ($this->excludedAddressRanges as $ipBlock) {
            $string .= (4 === $ipBlock->getVersion()) ? '!1:' : '!2:';
            $string .= (string) $ipBlock.' ';
        }

        return rtrim($string, ' ');
    }
}
