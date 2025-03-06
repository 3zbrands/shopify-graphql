<?php

namespace Zzz\ShopifyGraphql\Requests\PriceRules;

use Zzz\ShopifyGraphql\Trait\HasEdges;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetDiscountNode extends QueryRequest
{
  use CommonQueryFields;
  use HasEdges;

  public function __construct(protected $priceRuleTitle)
  {
  }

  public function graphQuery(): string
  {
    $titleFilter = sprintf('title:%s', addslashes($this->priceRuleTitle . "*"));

    return <<<QUERY
            codeDiscountNodes(first: 1, query: "$titleFilter") {
              edges {
                node {
                  id
                  codeDiscount {
                    __typename
                    ... on DiscountCodeFreeShipping {
                      title
                      status
                      __typename
                      title
                      startsAt
                      codes(first: 5) {
                        edges {
                          node {
                            id
                            code
                            asyncUsageCount
                          }
                        }
                      }
                      minimumRequirement {
                        __typename
                        ... on DiscountMinimumSubtotal {
                          greaterThanOrEqualToSubtotal {
                            amount
                            currencyCode
                          }
                        }
                        ... on DiscountMinimumQuantity {
                          greaterThanOrEqualToQuantity
                        }
                      }
                      destinationSelection {
                        ... on DiscountCountryAll {
                          allCountries
                        }
                        ... on DiscountCountries {
                          countries
                        }
                      }
                      maximumShippingPrice {
                        amount
                        currencyCode
                      }
                      customerSelection {
                        __typename
                        ... on DiscountCustomerAll {
                          allCustomers
                        }
                        ... on DiscountCustomerSegments {
                          segments {
                            id
                            name
                          }
                        }
                        ... on DiscountCustomers {
                          customers {
                              id
                              displayName
                          }
                        }
                      }
                      endsAt
                      createdAt
                      updatedAt
                      discountClass
                      usageLimit
                      appliesOncePerCustomer
                      combinesWith {
                        productDiscounts
                        orderDiscounts
                        shippingDiscounts
                      }
                    }
                    
                    ... on DiscountCodeBasic {
                      title
                      status
                      __typename
                      startsAt
                      codes(first: 5) {
                        edges {
                          node {
                            id
                            code
                            asyncUsageCount
                          }
                        }
                      }
                      minimumRequirement {
                        __typename
                        ... on DiscountMinimumSubtotal {
                          greaterThanOrEqualToSubtotal {
                            amount
                            currencyCode
                          }
                        }
                        ... on DiscountMinimumQuantity {
                          greaterThanOrEqualToQuantity
                        }
                      }
                      customerGets {
                        items {
                          __typename
                          ... on AllDiscountItems {
                            allItems
                          }
                          ... on DiscountCollections {
                            collections(first: 5) {
                              nodes {
                                id
                              }
                            }
                          }
                          ... on DiscountProducts {
                            products(first: 5) {
                              nodes {
                                id
                              }
                            },
                            productVariants(first: 5) {
                              nodes {
                                id
                              }
                            }
                          }
                        }
                        value {
                          __typename
                          ... on DiscountPercentage {
                            percentage
                          }
                          ... on DiscountAmount {
                            amount {
                              amount
                              currencyCode
                            }
                            appliesOnEachItem
                          }
                        }
                      }
                      customerSelection {
                        __typename
                        ... on DiscountCustomerAll {
                          allCustomers
                        }
                        ... on DiscountCustomerSegments {
                          segments {
                            id
                            name
                          }
                        }
                        ... on DiscountCustomers {
                          customers {
                              id
                              displayName
                          }
                        }
                      }
                      endsAt
                      createdAt
                      updatedAt
                      discountClass
                      usageLimit
                      appliesOncePerCustomer
                      combinesWith {
                        productDiscounts
                        orderDiscounts
                        shippingDiscounts
                      }
                    }
                    
                  }
                }
              }
            }          
        QUERY;
  }
}
