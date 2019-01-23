<form action="<?php echo $config->pages->ajax."load/vendors/vend-index/"; ?>" method="POST" id="vend-index-search-form">
    <div class="form-group">
        <?php if ($input->get->function) : ?>
        	<input type="hidden" name="function" class="function" value="<?= $input->get->function; ?>">
        <?php endif; ?>
        <input type="text" class="form-control vend-index-search" name="q" placeholder="Type vendor phone, name, ID, contact">
    </div>
    <div>
        <?php
            if ($input->get->q) {
                switch ($dplusfunction) {
                    case 'vi':
                        include $config->paths->content."vendor/ajax/load/vend-index/vi-vend-list.php";
                        break;
                }
            } else {
                include $config->paths->content."vendor/ajax/load/vend-index/vend-search-table.php";
            }
        ?>
    </div>
</form>
