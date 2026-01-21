<?php

namespace Zzz\ShopifyGraphql\Requests\Cart\Traits;

trait CommonQueryFields
{
    public function commonCartFields(): string
    {
        return <<<GRAPHQL
            id
            attributes {
                {$this->attribute()}
            }
            lines(first: 250) {
                nodes {
                    id
                    ... on ComponentizableCartLine {
                        lineComponents {
                            id
                            quantity
                            attributes {
                                {$this->attribute()}
                            }
                            discountAllocations {
                                discountedAmount {
                                    {$this->moneyV2()}
                                }
                            }
                            cost {
                                amountPerQuantity {
                                    {$this->moneyV2()}
                                }
                                compareAtAmountPerQuantity {
                                    {$this->moneyV2()}
                                }
                                subtotalAmount {
                                    {$this->moneyV2()}
                                }
                                totalAmount {
                                    {$this->moneyV2()}
                                }
                            }
                            merchandise {
                                ... on ProductVariant {
                                    id
                                    title
                                    sku
                                    product {
                                        id
                                        title
                                    }
                                }
                            }
                        }
                    }
                    quantity
                }
                edges {
                    node {
                        {$this->commonCartLineFields()}
                    }
                }
            }
            discountCodes {
                applicable
                code
            }
            discountAllocations {
                discountedAmount {
                    {$this->moneyV2()}
                }
            }
            totalQuantity
            cost {
                subtotalAmount {
                    {$this->moneyV2()}
                }
                totalTaxAmount {
                    {$this->moneyV2()}
                }
                totalAmount {
                    {$this->moneyV2()}
                }
            }
            note
            checkoutUrl
            createdAt
            updatedAt
        GRAPHQL;
    }

    public function commonCartLineFields(): string
    {
        return <<<GRAPHQL
            id
            quantity
            attributes {
                {$this->attribute()}
            }
            cost {
                amountPerQuantity {
                    {$this->moneyV2()}
                }
                compareAtAmountPerQuantity {
                    {$this->moneyV2()}
                }
                subtotalAmount {
                    {$this->moneyV2()}
                }
                totalAmount {
                    {$this->moneyV2()}
                }
            }
            discountAllocations {
                discountedAmount {
                    {$this->moneyV2()}
                }
            }
            merchandise {
                ... on ProductVariant {
                    id
                    title
                    sku
                }
            }
            quantity
        GRAPHQL;
    }

    public function commonProductFields(): string
    {
        return <<<GRAPHQL
            id
            handle
            productType
            status
            title
            vendor
            updatedAt
        GRAPHQL;

    }

    public function commonCollectionFields(): string
    {
        return <<<GRAPHQL
            id
            title
            handle
            updatedAt
        GRAPHQL;

    }

    public function commonUserErrorsFields(): string
    {
        return <<<GRAPHQL
            message
        GRAPHQL;
    }

    private function moneyV2(): string
    {
        return <<<GRAPHQL
            amount
            currencyCode
        GRAPHQL;
    }

    private function attribute(): string
    {
        return <<<GRAPHQL
            key
            value
        GRAPHQL;
    }
}
