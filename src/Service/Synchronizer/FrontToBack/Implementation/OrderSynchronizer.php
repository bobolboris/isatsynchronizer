<?php

namespace App\Service\Synchronizer\FrontToBack\Implementation;

use App\Entity\Back\OrderGamePost as OrderBack;
use App\Entity\Back\OrderHistory as OrderHistoryBack;
use App\Entity\Front\Order as OrderFront;
use App\Entity\Order;
use App\Event\FrontToBack\NewOrderEvent;
use App\Event\FrontToBack\OrderClearEvent;
use App\Event\FrontToBack\UpdateOrderEvent;
use App\Exception\CustomerFrontNotFoundException;
use App\Exception\OrderFrontNotFoundException;
use App\Exception\OrderFrontToBackSynchronizerException;
use App\Exception\ProductNotFoundException;
use App\Helper\Back\Store as StoreBack;
use App\Helper\ExceptionFormatter;
use App\Helper\Filler;
use App\Helper\Front\Store as StoreFront;
use App\Helper\PaymentConverter;
use App\Helper\ShippingConverter;
use App\Helper\Store;
use App\Repository\Back\BuyersGamePostRepository as CustomerBackRepository;
use App\Repository\Back\CurrencyRepository as CurrencyBackRepository;
use App\Repository\Back\OrderGamePostRepository as OrderBackRepository;
use App\Repository\Back\OrderHistoryRepository as OrderHistoryBackRepository;
use App\Repository\Front\AddressRepository as AddressFrontRepository;
use App\Repository\Front\CategoryDescriptionRepository as CategoryDescriptionFrontRepository;
use App\Repository\Front\CountryRepository as CountryFrontRepository;
use App\Repository\Front\CustomerActivityRepository as CustomerActivityFrontRepository;
use App\Repository\Front\CustomerAffiliateRepository as CustomerAffiliateFrontRepository;
use App\Repository\Front\CustomerApprovalRepository as CustomerApprovalFrontRepository;
use App\Repository\Front\CustomerHistoryRepository as CustomerHistoryFrontRepository;
use App\Repository\Front\CustomerIpRepository as CustomerIpFrontRepository;
use App\Repository\Front\CustomerLoginRepository as CustomerLoginFrontRepository;
use App\Repository\Front\CustomerOnlineRepository as CustomerOnlineFrontRepository;
use App\Repository\Front\CustomerRepository as CustomerFrontRepository;
use App\Repository\Front\CustomerRewardRepository as CustomerRewardFrontRepository;
use App\Repository\Front\CustomerSearchRepository as CustomerSearchFrontRepository;
use App\Repository\Front\CustomerTransactionRepository as CustomerTransactionFrontRepository;
use App\Repository\Front\CustomerWishListRepository as CustomerWishListFrontRepository;
use App\Repository\Front\OrderHistoryRepository as OrderHistoryFrontRepository;
use App\Repository\Front\OrderOptionRepository as OrderOptionFrontRepository;
use App\Repository\Front\OrderProductRepository as OrderProductFrontRepository;
use App\Repository\Front\OrderRecurringRepository as OrderRecurringFrontRepository;
use App\Repository\Front\OrderRecurringTransactionRepository as OrderRecurringTransactionFrontRepository;
use App\Repository\Front\OrderRepository as OrderFrontRepository;
use App\Repository\Front\OrderShipmentRepository as OrderShipmentFrontRepository;
use App\Repository\Front\OrderSimpleFieldsRepository as OrderSimpleFieldsFrontRepository;
use App\Repository\Front\OrderStatusRepository as OrderStatusFrontRepository;
use App\Repository\Front\OrderTotalRepository as OrderTotalFrontRepository;
use App\Repository\Front\OrderVoucherRepository as OrderVoucherFrontRepository;
use App\Repository\Front\ProductCategoryRepository as ProductCategoryFrontRepository;
use App\Repository\Front\ZoneRepository as ZoneFrontRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\Synchronizer\FrontToBack\CustomerSynchronizer as CustomerFrontToBackSynchronizer;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class OrderSynchronizer extends FrontToBackSynchronizer
{
    /** @var LoggerInterface $logger */
    protected $logger;

    /** @var EventDispatcherInterface $eventDispatcher */
    protected $eventDispatcher;

    /** @var StoreFront $storeFront */
    protected $storeFront;

    /** @var StoreBack $storeBack */
    protected $storeBack;

    /** @var CustomerBackRepository $customerBackRepository */
    protected $customerBackRepository;

    /** @var OrderBackRepository $orderBackRepository */
    protected $orderBackRepository;

    /** @var OrderFrontRepository $orderFrontRepository */
    protected $orderFrontRepository;

    /** @var OrderHistoryFrontRepository $orderHistoryRepository */
    protected $orderHistoryRepository;

    /** @var OrderOptionFrontRepository $orderOptionRepository */
    protected $orderOptionRepository;

    /** @var OrderProductFrontRepository $orderProductRepository */
    protected $orderProductRepository;

    /** @var OrderRecurringFrontRepository $orderRecurringFrontRepository */
    protected $orderRecurringFrontRepository;

    /** @var OrderRecurringTransactionFrontRepository $orderRecurringTransactionFrontRepository */
    protected $orderRecurringTransactionFrontRepository;

    /** @var OrderShipmentFrontRepository $orderShipmentFrontRepository */
    protected $orderShipmentFrontRepository;

    /** @var OrderStatusFrontRepository $orderStatusFrontRepository */
    protected $orderStatusFrontRepository;

    /** @var OrderTotalFrontRepository $orderTotalFrontRepository */
    protected $orderTotalFrontRepository;

    /** @var OrderVoucherFrontRepository $orderVoucherFrontRepository */
    protected $orderVoucherFrontRepository;

    /** @var OrderRepository $orderRepository */
    protected $orderRepository;

    /** @var AddressFrontRepository $addressFrontRepository */
    protected $addressFrontRepository;

    /** @var CurrencyBackRepository $currencyBackRepository */
    protected $currencyBackRepository;

    /** @var CategoryDescriptionFrontRepository $categoryDescriptionFrontRepository */
    protected $categoryDescriptionFrontRepository;

    /** @var CustomerFrontRepository $customerFrontRepository */
    protected $customerFrontRepository;

    /** @var CustomerActivityFrontRepository $customerActivityFrontRepository */
    protected $customerActivityFrontRepository;

    /** @var CustomerAffiliateFrontRepository $customerAffiliateFrontRepository */
    protected $customerAffiliateFrontRepository;

    /** @var CustomerApprovalFrontRepository $customerApprovalFrontRepository */
    protected $customerApprovalFrontRepository;

    /** @var CustomerHistoryFrontRepository $customerHistoryFrontRepository */
    protected $customerHistoryFrontRepository;

    /** @var CustomerIpFrontRepository $customerIpFrontRepository */
    protected $customerIpFrontRepository;

    /** @var CustomerLoginFrontRepository $customerLoginFrontRepository */
    protected $customerLoginFrontRepository;

    /** @var CustomerOnlineFrontRepository $customerOnlineFrontRepository */
    protected $customerOnlineFrontRepository;

    /** @var CustomerRewardFrontRepository $customerRewardFrontRepository */
    protected $customerRewardFrontRepository;

    /** @var CustomerSearchFrontRepository $customerSearchFrontRepository */
    protected $customerSearchFrontRepository;

    /** @var CustomerTransactionFrontRepository $customerTransactionFrontRepository */
    protected $customerTransactionFrontRepository;

    /** @var CustomerWishListFrontRepository $customerWishListFrontRepository */
    protected $customerWishListFrontRepository;

    /** @var ProductRepository $productRepository */
    protected $productRepository;

    /** @var ProductCategoryFrontRepository $productCategoryFrontRepository */
    protected $productCategoryFrontRepository;

    /** @var OrderSimpleFieldsFrontRepository $orderSimpleFieldsFrontRepository */
    protected $orderSimpleFieldsFrontRepository;

    /** @var CountryFrontRepository $countryFrontRepository */
    protected $countryFrontRepository;

    /** @var ZoneFrontRepository $zoneFrontRepository */
    protected $zoneFrontRepository;

    /** @var OrderHistoryBackRepository $orderHistoryBackRepository */
    protected $orderHistoryBackRepository;

    /** @var CustomerFrontToBackSynchronizer $customerFrontToBackSynchronizer */
    protected $customerFrontToBackSynchronizer;

    /** @var array $events */
    protected $events = [
        NewOrderEvent::class => 0,
        UpdateOrderEvent::class => 0,
        OrderClearEvent::class => 0,
    ];

    /**
     * OrderSynchronizer constructor.
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $eventDispatcher
     * @param StoreFront $storeFront
     * @param StoreBack $storeBack
     * @param CustomerBackRepository $customerBackRepository
     * @param OrderBackRepository $orderBackRepository
     * @param OrderFrontRepository $orderFrontRepository
     * @param OrderHistoryFrontRepository $orderHistoryRepository
     * @param OrderOptionFrontRepository $orderOptionRepository
     * @param OrderProductFrontRepository $orderProductRepository
     * @param OrderRecurringFrontRepository $orderRecurringFrontRepository
     * @param OrderRecurringTransactionFrontRepository $orderRecurringTransactionFrontRepository
     * @param OrderShipmentFrontRepository $orderShipmentFrontRepository
     * @param OrderStatusFrontRepository $orderStatusFrontRepository
     * @param OrderTotalFrontRepository $orderTotalFrontRepository
     * @param OrderVoucherFrontRepository $orderVoucherFrontRepository
     * @param OrderRepository $orderRepository
     * @param AddressFrontRepository $addressFrontRepository
     * @param CurrencyBackRepository $currencyBackRepository
     * @param CategoryDescriptionFrontRepository $categoryDescriptionFrontRepository
     * @param CustomerFrontRepository $customerFrontRepository
     * @param CustomerActivityFrontRepository $customerActivityFrontRepository
     * @param CustomerAffiliateFrontRepository $customerAffiliateFrontRepository
     * @param CustomerApprovalFrontRepository $customerApprovalFrontRepository
     * @param CustomerHistoryFrontRepository $customerHistoryFrontRepository
     * @param CustomerIpFrontRepository $customerIpFrontRepository
     * @param CustomerLoginFrontRepository $customerLoginFrontRepository
     * @param CustomerOnlineFrontRepository $customerOnlineFrontRepository
     * @param CustomerRewardFrontRepository $customerRewardFrontRepository
     * @param CustomerSearchFrontRepository $customerSearchFrontRepository
     * @param CustomerTransactionFrontRepository $customerTransactionFrontRepository
     * @param CustomerWishListFrontRepository $customerWishListFrontRepository
     * @param ProductRepository $productRepository
     * @param ProductCategoryFrontRepository $productCategoryFrontRepository
     * @param OrderSimpleFieldsFrontRepository $orderSimpleFieldsFrontRepository
     * @param CountryFrontRepository $countryFrontRepository
     * @param ZoneFrontRepository $zoneFrontRepository
     * @param OrderHistoryBackRepository $orderHistoryBackRepository
     * @param CustomerFrontToBackSynchronizer $customerFrontToBackSynchronizer
     */
    public function __construct(
        LoggerInterface $logger,
        EventDispatcherInterface $eventDispatcher,
        StoreFront $storeFront,
        StoreBack $storeBack,
        CustomerBackRepository $customerBackRepository,
        OrderBackRepository $orderBackRepository,
        OrderFrontRepository $orderFrontRepository,
        OrderHistoryFrontRepository $orderHistoryRepository,
        OrderOptionFrontRepository $orderOptionRepository,
        OrderProductFrontRepository $orderProductRepository,
        OrderRecurringFrontRepository $orderRecurringFrontRepository,
        OrderRecurringTransactionFrontRepository $orderRecurringTransactionFrontRepository,
        OrderShipmentFrontRepository $orderShipmentFrontRepository,
        OrderStatusFrontRepository $orderStatusFrontRepository,
        OrderTotalFrontRepository $orderTotalFrontRepository,
        OrderVoucherFrontRepository $orderVoucherFrontRepository,
        OrderRepository $orderRepository,
        AddressFrontRepository $addressFrontRepository,
        CurrencyBackRepository $currencyBackRepository,
        CategoryDescriptionFrontRepository $categoryDescriptionFrontRepository,
        CustomerFrontRepository $customerFrontRepository,
        CustomerActivityFrontRepository $customerActivityFrontRepository,
        CustomerAffiliateFrontRepository $customerAffiliateFrontRepository,
        CustomerApprovalFrontRepository $customerApprovalFrontRepository,
        CustomerHistoryFrontRepository $customerHistoryFrontRepository,
        CustomerIpFrontRepository $customerIpFrontRepository,
        CustomerLoginFrontRepository $customerLoginFrontRepository,
        CustomerOnlineFrontRepository $customerOnlineFrontRepository,
        CustomerRewardFrontRepository $customerRewardFrontRepository,
        CustomerSearchFrontRepository $customerSearchFrontRepository,
        CustomerTransactionFrontRepository $customerTransactionFrontRepository,
        CustomerWishListFrontRepository $customerWishListFrontRepository,
        ProductRepository $productRepository,
        ProductCategoryFrontRepository $productCategoryFrontRepository,
        OrderSimpleFieldsFrontRepository $orderSimpleFieldsFrontRepository,
        CountryFrontRepository $countryFrontRepository,
        ZoneFrontRepository $zoneFrontRepository,
        OrderHistoryBackRepository $orderHistoryBackRepository,
        CustomerFrontToBackSynchronizer $customerFrontToBackSynchronizer
    )
    {
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
        $this->storeFront = $storeFront;
        $this->storeBack = $storeBack;
        $this->customerBackRepository = $customerBackRepository;
        $this->orderBackRepository = $orderBackRepository;
        $this->orderFrontRepository = $orderFrontRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->orderOptionRepository = $orderOptionRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderRecurringFrontRepository = $orderRecurringFrontRepository;
        $this->orderRecurringTransactionFrontRepository = $orderRecurringTransactionFrontRepository;
        $this->orderShipmentFrontRepository = $orderShipmentFrontRepository;
        $this->orderStatusFrontRepository = $orderStatusFrontRepository;
        $this->orderTotalFrontRepository = $orderTotalFrontRepository;
        $this->orderVoucherFrontRepository = $orderVoucherFrontRepository;
        $this->orderRepository = $orderRepository;
        $this->addressFrontRepository = $addressFrontRepository;
        $this->currencyBackRepository = $currencyBackRepository;
        $this->categoryDescriptionFrontRepository = $categoryDescriptionFrontRepository;
        $this->customerFrontRepository = $customerFrontRepository;
        $this->customerActivityFrontRepository = $customerActivityFrontRepository;
        $this->customerAffiliateFrontRepository = $customerAffiliateFrontRepository;
        $this->customerApprovalFrontRepository = $customerApprovalFrontRepository;
        $this->customerHistoryFrontRepository = $customerHistoryFrontRepository;
        $this->customerIpFrontRepository = $customerIpFrontRepository;
        $this->customerLoginFrontRepository = $customerLoginFrontRepository;
        $this->customerOnlineFrontRepository = $customerOnlineFrontRepository;
        $this->customerRewardFrontRepository = $customerRewardFrontRepository;
        $this->customerSearchFrontRepository = $customerSearchFrontRepository;
        $this->customerTransactionFrontRepository = $customerTransactionFrontRepository;
        $this->customerWishListFrontRepository = $customerWishListFrontRepository;
        $this->productRepository = $productRepository;
        $this->productCategoryFrontRepository = $productCategoryFrontRepository;
        $this->orderSimpleFieldsFrontRepository = $orderSimpleFieldsFrontRepository;
        $this->countryFrontRepository = $countryFrontRepository;
        $this->zoneFrontRepository = $zoneFrontRepository;
        $this->orderHistoryBackRepository = $orderHistoryBackRepository;
        $this->customerFrontToBackSynchronizer = $customerFrontToBackSynchronizer;
    }

    /**
     * @param OrderFront $orderFront
     */
    protected function synchronizeOrder(OrderFront $orderFront): void
    {
        $order = $this->orderRepository->findOneByFrontId($orderFront->getOrderId());
        $orderBack = $this->getOrderBackFromOrder($order);

        $this->events[UpdateOrderEvent::class] = 1;
        if (null === $orderBack->getId()) {
            $this->events[NewOrderEvent::class] = 1;
        }

        try {
            $this->updateOrderBackFromOrderFront($orderFront, $orderBack);
        } catch (OrderFrontToBackSynchronizerException $e) {
            $this->logger->error(ExceptionFormatter::e($e));
        }

        $order = $this->createOrUpdateOrder($order, $orderBack->getId(), $orderFront->getOrderId());

        if (1 === $this->events[NewOrderEvent::class]) {
            $this->eventDispatcher->dispatch(new NewOrderEvent($order));
        }

        if (1 === $this->events[UpdateOrderEvent::class]) {
            $this->eventDispatcher->dispatch(new UpdateOrderEvent($order));
        }
    }

    /**
     * @param Order|null $order
     * @return OrderBack
     */
    protected function getOrderBackFromOrder(?Order $order): OrderBack
    {
        if (null === $order) {
            return new OrderBack();
        }

        $orderBack = $this->orderBackRepository->find($order->getBackId());

        if (null === $orderBack) {
            return new OrderBack();
        }

        return $orderBack;
    }

    /**
     * @param OrderFront $orderFront
     * @param OrderBack $orderBack
     * @return OrderBack
     * @throws OrderFrontToBackSynchronizerException
     */
    protected function updateOrderBackFromOrderFront(OrderFront $orderFront, OrderBack $orderBack): OrderBack
    {
        $orderProductsFront = $this->orderProductRepository->findByOrderFrontId($orderFront->getOrderId());
        $currentOrderBack = $orderBack;

        if (0 === count($orderProductsFront)) {
            throw new OrderFrontToBackSynchronizerException(
                "Order without products: {$orderFront->getOrderId()}"
            );
        }

        foreach ($orderProductsFront as $orderProductFront) {
            $product = $this->productRepository->findOneByFrontId($orderProductFront->getProductId());

            try {
                if (null === $product) {
                    throw new ProductNotFoundException(
                        "Product with id: {$orderProductFront->getProductId()} not found"
                    );
                }
            } catch (ProductNotFoundException $e) {
                $this->logger->error(ExceptionFormatter::e($e));
                continue;
            }

            if ($currentOrderBack->getProductId() !== $product->getBackId()) {
                if (null !== $orderBack->getOrderNum() && null !== $product->getBackId()) {
                    $currentOrderBack = $this->orderBackRepository->findOneByOrderNumAndProductBackId(
                        $orderBack->getOrderNum(),
                        $product->getBackId()
                    );
                }
            }

            if (null === $currentOrderBack) {
                $currentOrderBack = new OrderBack();
            }

            $courses = $this->getCurrentCourse();
            $currencyName = Store::convertFrontToBackCurrency($orderFront->getCurrencyCode());

            if (true === key_exists($currencyName, $courses)) {
                $currentCourse = $courses[$currencyName];
            } else {
                $currentCourse = 1.0;
            }

            if (null !== $orderBack->getId()) {
                $orderNum = $orderBack->getId();
            } else {
                $orderNum = 0;
            }

            $price = round($orderProductFront->getPrice() * $currentCourse) / $currentCourse;

            $currentOrderBack->setType('Покупка');
            $currentOrderBack->setProductName(Filler::trim($orderProductFront->getName()));
            $currentOrderBack->setProductId($product->getBackId());
            $currentOrderBack->setPrice($price);
            $currentOrderBack->setAmount($orderProductFront->getQuantity());
            $currentOrderBack->setCurrencyName($currencyName);
            $currentOrderBack->setParentName(
                $this->getMainCategoryNameByProductFrontId($orderProductFront->getProductId())
            );
            $currentOrderBack->setPhone(Filler::trim(Store::normalizePhone($orderFront->getTelephone())));
            $currentOrderBack->setFio(
                Filler::trim("{$orderFront->getLastName()} {$orderFront->getFirstName()}")
            );
            $currentOrderBack->setStreet(Filler::trim($orderFront->getShippingAddress1()));
            $currentOrderBack->setHouse(Filler::trim(null));
            $currentOrderBack->setMail(Filler::trim($orderFront->getEmail()));
            $currentOrderBack->setWhant(Filler::trim($orderFront->getComment()));
            $currentOrderBack->setVipNum(Filler::trim(null));
            $currentOrderBack->setTime(time());

            if (null === $orderFront->getOrderStatusId() || 0 === $orderFront->getOrderStatusId()) {
                $currentOrderBack->setStatus($this->storeBack->getDefaultOrderStatusid());
            } else {
                $currentOrderBack->setStatus($orderFront->getOrderStatusId());
            }

            if (null === $currentOrderBack->getComments()) {
                $currentOrderBack->setComments(Filler::trim(null));
            }

            if (null === $currentOrderBack->getArchive()) {
                $currentOrderBack->setArchive(0);
            }

            $currentOrderBack->setRead(0);
            if (null === $currentOrderBack->getSynchronize()) {
                $currentOrderBack->setSynchronize(false);
            }
            $currentOrderBack->setClientId($this->getClientIdByFrontCustomerPhone($orderFront));

            $paymentId = PaymentConverter::frontToBack(Filler::trim($orderFront->getPaymentCode()));
            $currentOrderBack->setPayment($paymentId);

            $shippingCode = Filler::trim($orderFront->getShippingCode());
            $currentOrderBack->setDelivery(ShippingConverter::frontToBack($shippingCode));

            if ('novaposhta.novaposhta' === $shippingCode) {
                if (true === $this->orderSimpleFieldsFrontRepository->tableExists()) {
                    $orderSimpleFields = $this->orderSimpleFieldsFrontRepository->find($orderFront->getOrderId());
                } else {
                    $orderSimpleFields = null;
                }
            } else {
                $orderSimpleFields = null;
            }

            if (null !== $orderSimpleFields) {
                if (null === $orderSimpleFields->getOblast()) {
                    $currentOrderBack->setRegion(Filler::trim($orderFront->getPaymentCountry()));
                } else {
                    $country = $this->countryFrontRepository->find(Filler::trim($orderSimpleFields->getOblast()));
                    if (null === $country) {
                        $currentOrderBack->setRegion(Filler::trim($orderFront->getPaymentCountry()));
                    } else {
                        $currentOrderBack->setRegion(Filler::trim($country->getName()));
                    }
                }

                if (null === $orderSimpleFields->getGorod()) {
                    $currentOrderBack->setRegion(Filler::trim($orderFront->getPaymentCountry()));
                } else {
                    $zone = $this->zoneFrontRepository->find($orderSimpleFields->getGorod());
                    if (null === $zone) {
                        $currentOrderBack->setRegion(Filler::trim($orderFront->getPaymentCountry()));
                    } else {
                        $currentOrderBack->setCity(Filler::trim($zone->getName()));
                    }
                }

                if (null === $orderSimpleFields->getOtdelenie()) {
                    $currentOrderBack->setWarehouse(Filler::trim($orderFront->getShippingCity()));
                } else {
                    $currentOrderBack->setWarehouse(Filler::trim($orderSimpleFields->getOtdelenie()));
                }

                $currentOrderBack->setStreet(Filler::trim(null));
                $currentOrderBack->setHouse(Filler::trim(null));
            } else {
                $region = Filler::trim($orderFront->getPaymentCountry());
                if (0 === mb_strlen($region)) {
                    $currentOrderBack->setRegion($this->storeBack->getDefaultRegion());
                } else {
                    $currentOrderBack->setRegion($region);
                }

                $city = Filler::trim($orderFront->getPaymentZone());
                if (0 === mb_strlen($city)) {
                    $currentOrderBack->setCity($this->storeBack->getDefaultCity());
                } else {
                    $currentOrderBack->setCity($city);
                }

                $currentOrderBack->setWarehouse(Filler::trim($orderFront->getShippingCity()));
            }

            $currentOrderBack->setOrderNum($orderNum);

            $tracking = Filler::trim($orderFront->getTracking());

            if (0 === mb_strlen($tracking)) {
                $currentOrderBack->setTrackNumber($tracking);
                $currentOrderBack->setTrackNumberDate(new DateTime('0000-00-00 00:00:00'));
            } else {
                $currentOrderBack->setTrackNumber($tracking);
                if (null === $currentOrderBack->getTrackNumberDate()) {
                    $currentOrderBack->setTrackNumberDate(new DateTime());
                }
            }

            if (null === $currentOrderBack->getMoneyGiven()) {
                $currentOrderBack->setMoneyGiven(false);
            }

            if (null === $currentOrderBack->getTrackSent()) {
                $currentOrderBack->setTrackSent(false);
            }

            if (null === $currentOrderBack->getSerialNum()) {
                $currentOrderBack->setSerialNum(Filler::trim(null));
            }

            if (null === $currentOrderBack->getShopId()) {
                $currentOrderBack->setShopId($this->storeBack->getDefaultSiteId());
            }

            if (null === $currentOrderBack->getShopIdCounterparty()) {
                $currentOrderBack->setShopIdCounterparty(0);
            }

            if (null === $currentOrderBack->getPaymentWaitDays()) {
                $currentOrderBack->setPaymentWaitDays(0);
            }

            $currentOrderBack->setPaymentWaitFirstSum(0);

            if (null === $currentOrderBack->getPaymentDate()) {
                $currentOrderBack->setPaymentDate($orderFront->getDateAdded());
            } else {
                $currentOrderBack->setPaymentDate(new DateTime('0000-00-00 00:00:00'));
            }

            $currentOrderBack->setDocumentId(0);

            if (null === $currentOrderBack->getDocumentType()) {
                $currentOrderBack->setDocumentType(2);
            }

            if (null === $currentOrderBack->getInvoiceSent()) {
                $currentOrderBack->setInvoiceSent(new DateTime('0000-00-00 00:00:00'));
            }

            $currentOrderBack->setCurrencyValue($currentCourse);
            $currentOrderBack->setCurrencyValueWhenPurchasing(json_encode($courses));
            $currentOrderBack->setShippingPrice(0);
            $currentOrderBack->setShippingPriceOld(0);

            if (null === $currentOrderBack->getShippingCurrencyName()) {
                $currentOrderBack->setShippingCurrencyName(Filler::trim(null));
            }

            if (null === $currentOrderBack->getShippingCurrencyValue()) {
                $currentOrderBack->setShippingCurrencyValue(0);
            }

            $this->orderBackRepository->persistAndFlush($currentOrderBack);

            if (0 === $orderNum) {
                $currentOrderBack->setOrderNum($orderBack->getId());
                $this->orderBackRepository->persistAndFlush($currentOrderBack);
                $this->createOrderHistory($currentOrderBack);
            }
        }

        $orderFront->setFax((string)$orderBack->getOrderNum());
        $this->orderFrontRepository->persistAndFlush($orderFront);

        return $orderBack;
    }

    /**
     * @param OrderBack $orderBack
     */
    protected function createOrderHistory(OrderBack $orderBack): void
    {
        if (true === $this->orderHistoryBackRepository->existsByOrderNum($orderBack->getId())) {
            return;
        }

        $orderHistoryBack = new OrderHistoryBack();
        $orderHistoryBack->setOrderNum($orderBack->getOrderNum());
        $orderHistoryBack->setCustomerId(0);

        $date = new DateTime();
        $date->setTimestamp($orderBack->getTime());

        $orderHistoryBack->setDate($date);

        $this->orderHistoryBackRepository->persistAndFlush($orderHistoryBack);
    }

    /**
     * @return array
     */
    protected function getCurrentCourse(): array
    {
        return $this->currencyBackRepository->getCurrentCourse();
    }

    /**
     * @param int $frontId
     * @return string
     */
    protected function getMainCategoryNameByProductFrontId(int $frontId): string
    {
        $productCategories = $this->productCategoryFrontRepository->findByProductFrontId($frontId);

        if (0 === count($productCategories)) {
            $error = "ProductCategoryFront not found {$frontId}";
            $this->logger->error(ExceptionFormatter::f($error));

            return '';
        }

        $categoryProduct = $productCategories[0];
        $categoryDescription = $this->categoryDescriptionFrontRepository->findOneByCategoryFrontIdAndLanguageId(
            $categoryProduct->getCategoryId(),
            $this->storeFront->getDefaultLanguageId()
        );

        if (null === $categoryDescription) {
            $error = "Category Description with id: {$categoryProduct->getCategoryId()} does not found";
            $this->logger->error(ExceptionFormatter::f($error));

            return '';
        }

        return $categoryDescription->getName();
    }

    /**
     * @param OrderFront $orderFront
     * @return int
     */
    protected function getClientIdByFrontCustomerPhone(OrderFront $orderFront): int
    {
        $customerBack = $this->customerBackRepository->findOneByTelephone(
            Store::normalizePhone($orderFront->getTelephone())
        );

        if (null !== $customerBack) {
            return $customerBack->getId();
        }

        if ($orderFront->getCustomerId() > 0) {
            try {
                $customerBack = $this->customerFrontToBackSynchronizer->synchronizeOne(
                    $orderFront->getCustomerId(),
                    $this->storeBack->generatePassword()
                );
            } catch (CustomerFrontNotFoundException $e) {
                $customerBack = null;
            }

            if (null !== $customerBack) {
                return $customerBack->getId();
            }
        }

        try {
            $customerBack = $this->customerFrontToBackSynchronizer->synchronizeOneByOrderFrontId($orderFront->getOrderId());
        } catch (OrderFrontNotFoundException $e) {
            $this->logger->error(ExceptionFormatter::e($e));
        }

        return $customerBack->getId();
    }

    /**
     * @param Order $order
     * @param int $backId
     * @param int $frontId
     * @return Order
     */
    protected function createOrUpdateOrder(?Order $order, int $backId, int $frontId): Order
    {
        if (null === $order) {
            $order = new Order();
        }

        $order->setBackId($backId);
        $order->setFrontId($frontId);

        $this->orderRepository->persistAndFlush($order);

        return $order;
    }
}