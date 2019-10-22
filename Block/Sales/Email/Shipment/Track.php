<?php
/**
 * Copyright © Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * See COPYING.txt for license details.
 */
namespace Faonni\TrackingLink\Block\Sales\Email\Shipment;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\ResourceModel\Order\Shipment\Track\CollectionFactory as TrackCollectionFactory;
use Faonni\TrackingLink\Helper\Data as TrackingLinkHelper;

/**
 * Track block
 */
class Track extends Template
{
    /**
     * Tracking helper
     *
     * @var \Faonni\TrackingLink\Helper\Data
     */
    protected $helper;

    /**
     * Track collection
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\Shipment\Track\Collection
     */
    protected $tracksCollection;

    /**
     * Track collection factory
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\Shipment\Track\CollectionFactory
     */
    protected $trackCollectionFactory;

    /**
     * Initialize block
     *
     * @param Context $context
     * @param TrackingLinkHelper $helper
     * @param TrackCollectionFactory $trackCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        TrackingLinkHelper $helper,
        TrackCollectionFactory $trackCollectionFactory,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->trackCollectionFactory = $trackCollectionFactory;

        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Retrieve tracking url
     *
     * @param \Magento\Shipping\Model\Order\Track $track
     * @return string|null
     */
    public function getTrackingUrl($track)
    {
        $carrierCode = $track->getCarrierCode();

        $trackingUrl = null;

        if (preg_match('/royalmail/i', $carrierCode)) {
            $trackingUrl = $this->helper->getCarrierUrl('royalmail', (string)$track->getStoreId());
        }

        else if (preg_match('/dhl/i', $carrierCode)) {
            $trackingUrl = $this->helper->getCarrierUrl('dhl', (string)$track->getStoreId());
        }

        else if (preg_match('/fedex/i', $carrierCode)) {
            $trackingUrl = $this->helper->getCarrierUrl('fedex', (string)$track->getStoreId());
        }

        else if (preg_match('/dpd/i', $carrierCode)) {
            $trackingUrl = $this->helper->getCarrierUrl('dpd', (string)$track->getStoreId());
        }

        else if (preg_match('/usps/i', $carrierCode)) {
            $trackingUrl = $this->helper->getCarrierUrl('usps', (string)$track->getStoreId());
        }

        else if (preg_match('/ups/i', $carrierCode)) {
            $trackingUrl = $this->helper->getCarrierUrl('ups', (string)$track->getStoreId());
        }

        return ($trackingUrl === null)
            ? null
            : preg_replace("/\{\{number\}\}/", $track->getNumber(), $trackingUrl);
    }

    /**
     * Retrieve tracks collection
     *
     * @param integer $shipmentId
     * @return \Magento\Sales\Model\ResourceModel\Order\Shipment\Track\Collection
     */
    public function getTracksCollection($shipmentId)
    {
        if ($this->tracksCollection === null) {
            $this->tracksCollection = $this->trackCollectionFactory->create();
            $this->tracksCollection->setShipmentFilter($shipmentId);
        }
        return $this->tracksCollection;
    }
}
