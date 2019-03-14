<div class="add">
    <form action="{$link->getModuleLink('compareitems', 'compare')|escape:'html'}" method="post">
        <input name="id_product_compare" type="hidden" value="{$productId}">
        <button class="btn btn-primary" type="submit" name="saveProduct" value="Submit">
            {$compareButton}
        </button>
    </form>
</div>
