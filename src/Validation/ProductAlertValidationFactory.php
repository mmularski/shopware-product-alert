<?php declare(strict_types=1);

/**
 * @package  shopware_dev
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Validation\EntityExists;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidationFactoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ProductAlertValidationFactory
 */
class ProductAlertValidationFactory implements DataValidationFactoryInterface
{
    public function create(SalesChannelContext $context): DataValidationDefinition
    {
        $definition = new DataValidationDefinition('product_alert.create');

        $this->buildCommonValidation($definition, $context);

        return $definition;
    }

    public function update(SalesChannelContext $context): DataValidationDefinition
    {
        $definition = new DataValidationDefinition('product_alert.update');

        $this->buildCommonValidation($definition, $context)
            ->add(
                'id',
                new NotBlank(),
                new EntityExists(
                    [
                        'context' => $context->getContext(),
                        'entity' => 'product_alert',
                    ]
                )
            );

        return $definition;
    }

    /**
     * @param SalesChannelContext|Context $context
     */
    private function buildCommonValidation(DataValidationDefinition $definition, $context): DataValidationDefinition
    {
        if ($context instanceof SalesChannelContext) {
            $frameworkContext = $context->getContext();
        } else {
            $frameworkContext = $context;
        }

        $definition
            ->add(
                'salesChannelId',
                new EntityExists(
                    [
                        'entity' => 'sales_channel',
                        'context' => $frameworkContext,
                    ]
                )
            )
            ->add(
                'productId',
                new EntityExists(
                    [
                        'entity' => 'product',
                        'context' => $frameworkContext,
                    ]
                )
            )
            ->add('email', new NotBlank());

        return $definition;
    }
}