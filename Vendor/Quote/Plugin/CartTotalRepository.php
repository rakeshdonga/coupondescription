<?php declare(strict_types=1);

namespace Vendor\Quote\Plugin;

use Magento\Quote\Api\Data\TotalsExtensionFactory;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\SalesRule\Model\Coupon;

/**
 * Class CartTotalRepository
 * @package Magento\SalesRule\Plugin
 */
class CartTotalRepository
{
    /**
     * @var TotalsExtensionFactory
     */
    private $extensionFactory;

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var Coupon
     */
    private $coupon;

    /**
     * CartTotalRepository constructor.
     * @param TotalsExtensionFactory $extensionFactory
     * @param RuleRepositoryInterface $ruleRepository
     * @param Coupon $coupon
     */
    public function __construct(
        TotalsExtensionFactory $extensionFactory,
        RuleRepositoryInterface $ruleRepository,
        Coupon $coupon
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->ruleRepository = $ruleRepository;
        $this->coupon = $coupon;
    }

    /**
     * @param \Magento\Quote\Model\Cart\CartTotalRepository $subject
     * @param TotalsInterface $result
     * @return TotalsInterface
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        \Magento\Quote\Model\Cart\CartTotalRepository $subject,
        TotalsInterface $result
    ) {
        if ($result->getExtensionAttributes() === null) {
            $extensionAttributes = $this->extensionFactory->create();
            $result->setExtensionAttributes($extensionAttributes);
        }

        $extensionAttributes = $result->getExtensionAttributes();
        $couponCode = $result->getCouponCode();

        if (empty($couponCode)) {
            return $result;
        }
        $this->coupon->loadByCode($couponCode);
        $ruleId = $this->coupon->getRuleId();

        if (empty($ruleId)) {
            return $result;
        }
        $rule = $this->ruleRepository->getById($ruleId);
        $extensionAttributes->setCouponDescription($rule->getDescription());
        $result->setExtensionAttributes($extensionAttributes);
        return $result;
    }
}