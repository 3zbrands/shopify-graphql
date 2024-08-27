<?php

namespace Zzz\ShopifyGraphql\Resources;

use Throwable;
use Exception;
use JsonException;
use Saloon\Http\Connector;
use Zzz\ShopifyGraphql\Requests\Cart\GetCart;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Exceptions\Request\RequestException;
use Zzz\ShopifyGraphql\Requests\Cart\UpdateNote;
use Zzz\ShopifyGraphql\Requests\Cart\UpdateAttributes;
use Zzz\ShopifyGraphql\Responses\Cart\CartLineResponse;
use Zzz\ShopifyGraphql\Requests\Cart\CreateCart;
use Zzz\ShopifyGraphql\Responses\Cart\CartLinesResponse;
use Zzz\ShopifyGraphql\Requests\Cart\AddCartLines;
use Zzz\ShopifyGraphql\Exceptions\GraphQlException;
use Saloon\Exceptions\Request\FatalRequestException;
use Zzz\ShopifyGraphql\Requests\Cart\UpdateCartLines;
use Zzz\ShopifyGraphql\Requests\Cart\RemoveCartLines;
use Zzz\ShopifyGraphql\Trait\ValidateGraphQlResponse;
use Zzz\ShopifyGraphql\Responses\Cart\CartResponse as CartResponse;
use Zzz\ShopifyGraphql\Requests\Cart\UpdateCartDiscountCodes;
use Zzz\ShopifyGraphql\Exceptions\SpecificCartDoesNotExistException;
use Zzz\ShopifyGraphql\Requests\Models\CartLineRequest as CartLineRequest;

class Cart
{
    use AlwaysThrowOnErrors;
    use ValidateGraphQlResponse;

    public function __construct(protected Connector $api)
    {
    }

    /**
     * @throws Throwable
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    public function create(array $lines = []): CartResponse
    {
        $response = $this->api->send(new CreateCart($lines));

        $this->validate($response);

        return new CartResponse($response->json('data.cartCreate.cart'));
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws GraphQlException
     * @throws JsonException
     */
    public function get(string|CartResponse $cartId): CartResponse
    {
        if ($cartId instanceof CartResponse) {
            $cartId = $cartId->id();
        }

        $response = $this->api->send(new GetCart($cartId));

        $this->validate($response);

        return new CartResponse($response->json('data.cart') ?: []);
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws GraphQlException
     * @throws JsonException
     */
    public function updateLines(string|CartResponse $cartId, array $lines): CartResponse
    {
        if ($cartId instanceof CartResponse) {
            $cartId = $cartId->id();
        }

        $response = $this->api->send(new UpdateCartLines($cartId, $lines));

        $this->validate($response);

        return new CartResponse($response->json('data.cartLinesUpdate.cart'));
    }

    /**
     * @throws FatalRequestException
     * @throws GraphQlException
     * @throws RequestException
     * @throws JsonException
     */
    public function addLines(string|CartResponse $cartId, array $lines): CartResponse
    {
        if ($cartId instanceof CartResponse) {
            $cartId = $cartId->id();
        }

        $response = $this->api->send(new AddCartLines($cartId, $lines));

        try {
            $this->validate($response, 'data.cartLinesAdd.userErrors');
        } catch (SpecificCartDoesNotExistException) {
            return $this->addLines($this->create(), $lines);
        }

        return new CartResponse($response->json('data.cartLinesAdd.cart'));
    }

    /**
     * @throws FatalRequestException
     * @throws GraphQlException
     * @throws RequestException
     * @throws JsonException
     */
    public function removeLines(string|CartResponse $cartId, array|CartLinesResponse $lines): CartResponse
    {
        if ($cartId instanceof CartResponse) {
            $cartId = $cartId->id();
        }

        if ($lines instanceof CartLinesResponse) {
            $lines = $lines->all->map(function (CartLineResponse $cartLineResponse) {
                return CartLineRequest::fromResponse($cartLineResponse);
            })->toArray();
        }

        $response = $this->api->send(new RemoveCartLines($cartId, $lines));

        $this->validate($response);

        return new CartResponse($response->json('data.cartLinesRemove.cart'));
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws GraphQlException
     * @throws JsonException
     */
    public function updateDiscountCodes(string|CartResponse $cartId, array $discountCodes): CartResponse
    {
        if ($cartId instanceof CartResponse) {
            $cartId = $cartId->id();
        }

        $response = $this->api->send(new UpdateCartDiscountCodes($cartId, $discountCodes));

        $this->validate($response);

        return new CartResponse($response->json('data.cartDiscountCodesUpdate.cart'));
    }

    /**
     * @throws FatalRequestException
     * @throws GraphQlException
     * @throws RequestException
     * @throws JsonException
     * @throws Exception
     */
    public function updateNote(string|CartResponse $cartId, string $note): CartResponse
    {
        if ($cartId instanceof CartResponse) {
            $cartId = $cartId->id();
        }

        $response = $this->api->send(new UpdateNote($cartId, $note));

        $this->validate($response);

        return new CartResponse($response->json('data.cartNoteUpdate.cart'));
    }

    /**
     * @throws FatalRequestException
     * @throws GraphQlException
     * @throws RequestException
     * @throws JsonException
     * @throws Exception
     */
    public function updateAttributes(string|CartResponse $cartId, array $attributes): CartResponse
    {
        if ($cartId instanceof CartResponse) {
            $cartId = $cartId->id();
        }

        $response = $this->api->send(new UpdateAttributes($cartId, $attributes));

        $this->validate($response);

        return new CartResponse($response->json('data.cartAttributesUpdate.cart'));
    }
}
