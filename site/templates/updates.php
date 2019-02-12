<?php 
    $template = 'warehouse-function';
    $parent = $pages->get('/warehouse/binr/');
    $binr_pages = array(
        'move-from' => array(
            'name' => 'move-from',
            'title' => 'Move From',
            'summary' => 'Move Single items from same from bin'
        ),
        'move-to' => array(
            'name' => 'move-to',
            'title' => 'Move To',
            'summary' => 'Move Single Items to the same to bin'
        )
    );
    
    foreach ($binr_pages as $pagename => $page) {
        $p = new Page();
        $p->template = $template; // set template
        $p->parent = $parent;
        $p->name = $page['name']; // give it a name used in the url for the page
        $p->title = $page['title']; // set page title (not neccessary but recommended)
        $p->summary = $page['summary'];
        $p->save();
    }
?>
