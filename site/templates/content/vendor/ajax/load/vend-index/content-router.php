<?php
    if ($input->get->q) {
        $function = $input->get->text('function');
        $dplusfunction = substr($input->get->text('function'), 0, 2);
        $page->title = "Searching for '".$input->get->text('q')."'";
    } else {
		$page->title = 'Search for a vendor';
    }

    $page->body = $config->paths->content."vendor/ajax/load/vend-index/search-form.php";




?>
