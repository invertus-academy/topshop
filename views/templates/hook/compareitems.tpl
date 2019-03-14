<div class="add">
    <form  action="{$link->getModuleLink('compareitems', 'compare')|escape:'html'}" method="post">
        <button class="btn btn-primary" type="submit" name="saveProduct" value="Submit">
            {$compareButton}
        </button>
    </form>
</div>
