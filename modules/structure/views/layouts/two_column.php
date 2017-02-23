<?php
use app\components\CmsHelper;	
?>

<section>
    <div class="col-sm-6">
    <?=CmsHelper::structure_blocks($scheme, $use_blocks, 5, '@left')?>
    </div>
	<div class="col-sm-6">
    <?=CmsHelper::structure_blocks($scheme, $use_blocks, 5, '@right')?>
    </div>
</section>