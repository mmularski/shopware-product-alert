<?php declare(strict_types=1);
/**
 * @package Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace Mularski\ProductAlert\Service\SalesChannel\Validation;

use Mularski\ProductAlert\Service\SalesChannel\Validation\Exception\AlreadySignedException;
use Mularski\ProductAlert\ProductAlert\ProductAlertEntityDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Validation\EntityExists;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     * @return void
     *
     * @throws \Exception
     */
    public function validate(DataBag $data, Context $context): void
    {
        if (!$data->get(ProductAlertEntityDefinition::FIELD_SALES_CHANNEL_ID)) {
            $salesChannelId = $context->getSource()->getSalesChannelId();

            $data->add([ProductAlertEntityDefinition::FIELD_SALES_CHANNEL_ID => $salesChannelId]);
        }

        $definition = $this->getDefinition($data, $context);

        $this->validator->validate($data->all(), $definition);
        $this->validateIsExist($data, $context);
    }

    /**
     * @param DataBag $data
     * @param Context $context
     *
     * @return DataValidationDefinition
     */
    private function getDefinition(DataBag $data, Context $context): DataValidationDefinition
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
    private function validateIsExist(DataBag $data, Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter(ProductAlertEntityDefinition::FIELD_EMAIL, $data->get('email')))
            ->addFilter(new EqualsFilter(ProductAlertEntityDefinition::FIELD_PRODUCT_ID, $data->get('productId')))
            ->addFilter(
                new EqualsFilter(
                    ProductAlertEntityDefinition::FIELD_SALES_CHANNEL_ID,
                    $data->get(ProductAlertEntityDefinition::FIELD_SALES_CHANNEL_ID)
                )
            );

        $result = $this->entityRepository->search($criteria, $context);

        if ($result->count() > 0) {
            throw new AlreadySignedException(sprintf('You are already signed to this product.'), 400);
        }
    }
}
