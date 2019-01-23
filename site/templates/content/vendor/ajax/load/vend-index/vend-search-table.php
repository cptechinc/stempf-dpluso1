<?php 
    $vendlink = new \Purl\Url($page->fullURL);
    $vendlink->path = $config->pages->vendor.'redir/';
    $vendlink->query = '';
    $vendlink->query->set('action', 'vi-vendor');
    
    $q = '';
    $vendors = search_vendorspaged($limit = 10, $page = 1, $q, false);
?>

<div class="table-responsive" id="vend-results">
	<table id="vend-index" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th width="100">VendID</th> <th>Vendor Name</th> <th>Ship-From</th> <th>Address</th> <th>City</th> <th>State</th> <th>Zip</th> <th width="100">Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vendors as $vend) : ?>
                <?php $vendlink->query->set('vendorID', $vend['vendid']); ?>
                    <tr>
                        <td>
                            <a href="<?= $vendlink; ?>">
                                <?= $vend['vendid'];?>
                            </a> &nbsp; <span class="glyphicon glyphicon-share"></span>
                        </td>
                        <td><?= $vend['name']; ?></td>
                        <td><?= $vend['shipfrom']; ?></td>
                        <td>
                            <?= $vend['address1']; ?>
                            <?= $vend['address2']; ?>
                        </td>
                        <td><?= $vend['city']; ?></td>
                        <td><?= $vend['state']; ?></td>
                        <td><?= $vend['zip']; ?></td>
                        <td><a href="tel:<?= $vend['phone']; ?>" title="Click To Call"><?= $vend['phone']; ?></a></td>
                    </tr>
            <?php endforeach; ?>
        </tbody>
	</table>
</div>
