<?php declare(strict_types=1);
/**
 * @package  shopware_dev
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

/**
 * Class ProductAlertService
 */
class ProductAlertService
{
    /**
     * @throws AddressNotFoundException
     * @throws CustomerNotLoggedInException
     * @throws InvalidUuidException
     * @throws ConstraintViolationException
     */
    public function upsert(DataBag $data, SalesChannelContext $context): string
    {
        $this->validateCustomerIsLoggedIn($context);

        if ($id = $data->get('id')) {
            $this->validateAddressId((string) $id, $context);
            $isCreate = false;
        } else {
            $id = Uuid::randomHex();
            $isCreate = true;
        }

        $accountType = $data->get('accountType', CustomerEntity::ACCOUNT_TYPE_PRIVATE);
        $definition = $this->getValidationDefinition($accountType, $isCreate, $context);
        $this->validator->validate(array_merge(['id' => $id], $data->all()), $definition);

        $addressData = [
            'salutationId' => $data->get('salutationId'),
            'firstName' => $data->get('firstName'),
            'lastName' => $data->get('lastName'),
            'street' => $data->get('street'),
            'city' => $data->get('city'),
            'zipcode' => $data->get('zipcode'),
            'countryId' => $data->get('countryId'),
            'countryStateId' => $data->get('countryStateId') ? $data->get('countryStateId') : null,
            'company' => $data->get('company'),
            'department' => $data->get('department'),
            'title' => $data->get('title'),
            'vatId' => $data->get('vatId'),
            'phoneNumber' => $data->get('phoneNumber'),
            'additionalAddressLine1' => $data->get('additionalAddressLine1'),
            'additionalAddressLine2' => $data->get('additionalAddressLine2'),
        ];

        $mappingEvent = new DataMappingEvent($data, $addressData, $context->getContext());
        $this->eventDispatcher->dispatch($mappingEvent, CustomerEvents::MAPPING_ADDRESS_CREATE);

        $addressData = $mappingEvent->getOutput();
        $addressData['id'] = $id;
        $addressData['customerId'] = $context->getCustomer()->getId();

        $this->customerAddressRepository->upsert([$addressData], $context->getContext());

        return $id;
    }
}