<?php declare(strict_types=1);
/**
 * @package  shopware_dev
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace ProductAlert\SalesChannel\Validation;

use ProductAlert\ProductAlert\ProductAlertEntityDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Validation\EntityExists;
use Shopware\Core\Framework\DataAbstractionLayer\Validation\EntityNotExists;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\NotBlank;
use function _HumbugBox58fd4d9e2a25\Sodium\add;

/**
 * Class ProductAlertValidator
 */
class ProductAlertValidator
{
    /**
     * @var DataValidator
     */
    private $validator;

    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

    /**
     * ProductAlertValidator constructor.
     *
     * @param DataValidator $validator
     * @param EntityRepositoryInterface $entityRepository
     */
    public function __construct(DataValidator $validator, EntityRepositoryInterface $entityRepository)
    {
        $this->validator = $validator;
        $this->entityRepository = $entityRepository;
    }

    /**
     * @param DataBag $data
     * @param Context $context
     *
     * @throws \Exception
     */
    public function validate(DataBag $data, Context $context)
    {
        $salesChannelId = $context->getSource()->getSalesChannelId();
        $definition = $this->getDefinition($data, $context);

        $this->validator->validate(array_merge($data->all(), ['salesChannelId' => $salesChannelId]), $definition);

        $this->validateIsExist($data, $context);
    }

    /**
     * @param DataBag $data
     * @param Context $context
     *
     * @return DataValidationDefinition
     */
    private function getDefinition(DataBag $data, Context $context)
    {
        $definition = new DataValidationDefinition('product_alert.create');

        $definition->add(ProductAlertEntityDefinition::FIELD_EMAIL, new NotBlank());
        $definition->add(
            ProductAlertEntityDefinition::FIELD_PRODUCT_ID,
            new EntityExists(
                [
                    'entity' => ProductDefinition::ENTITY_NAME,
                    'context' => $context,
                ]
            )
        );

        $definition->add(
            ProductAlertEntityDefinition::FIELD_SALES_CHANNEL_ID,
            new EntityExists(
                [
                    'entity' => SalesChannelDefinition::ENTITY_NAME,
                    'context' => $context,
                ]
            )
        );

        return $definition;
    }

    /**
     * @param DataBag $data
     * @param Context $context
     *
     * @return void
     *
     * @throws \Exception
     */
    private function validateIsExist(DataBag $data, Context $context)
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter(ProductAlertEntityDefinition::FIELD_EMAIL, $data->get('email')))
            ->addFilter(new EqualsFilter(ProductAlertEntityDefinition::FIELD_PRODUCT_ID, $data->get('productId')))
            ->addFilter(
                new EqualsFilter(
                    ProductAlertEntityDefinition::FIELD_SALES_CHANNEL_ID,
                    $context->getSource()->getSalesChannelId()
                )
            );

        $result = $this->entityRepository->search($criteria, $context);

        if ($result->count() > 0) {
            throw new \Exception(sprintf('You are already signed to this product.'), 400);
        }
    }
}
