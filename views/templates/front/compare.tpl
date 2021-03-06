{extends file='page.tpl'}
{block name='page_content'}
    <h1>{l s='Product Comparison' mod='compareitems'}</h1>

    <article class="product-miniature js-product-miniature">
        {$numOfCols = 4}
        {$rowCount = 0}
        {$bootstrapColWidth = 12 / $numOfCols}
        {*{$productIds = Context::getContext()->cookie->productIds}*}

        <div class="row">
            {foreach from=$products item="product"}
                {if in_array({$product['id_product']}, $productIds)}
                    <div class="col-md-{$bootstrapColWidth}">
                        <div class="thumbnail-container">
                            {block name='product_thumbnail'}
                                <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}">
                            {/block}
                            <div class="product-description">
                                {block name='product_name'}
                                    <h3 class="h3 product-title" itemprop="name">{$product['name']}</h3>
                                {/block}
                                {block name='product_price_and_shipping'}
                                    <div class="product-price-and-shipping">
                                        <span class="price">Price: {round($product['price'], 2)} Eur</span>
                                    </div>
                                {/block}
                            </div>
                        </div>
                    </div>
                    {$rowCount = $rowCount + 1}
                    {if $rowCount % $numOfCols == 0}
                        </div><div class="row">
                    {/if}
                {/if}
            {/foreach}
        </div>
    </article>
{/block}
