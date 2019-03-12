{extends file='page.tpl'}

{block name="page_content"}
    <h1>Welcome to my Shop!</h1>

    <section>
        <h1>{l s='Product Comparison' mod='compareitems'}</h1>

            {l s='Number of Products: ' mod='multipurpose'}{$number_of_product}

        <div class="products">
            {foreach from=$products item="product"}
                {$product['id_product']}
                {$product['name']}
                Price: {round($product['price'], 2)} Eur
                <br>
{*                {include file="catalog/_partials/miniatures/product.tpl" product=$product}*}
{*                {Image::getImages(1, 1)}*}

{*                    {Image::getImages(1, $product['id_product'])}*}

            {/foreach}
        </div>
    </section>
{/block}
