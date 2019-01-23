<?php
    $returnpage = \Purl\Url::parse($input->get->text('returnpage'));
    $vendors = getvendors(false);
    echo $returnpage;
?>
<table class="table table-bordered table-condensed" id="vendors-table">
	<thead>
		<tr>
			<th>VendorID</th> <th>Name</th> <th>Address1</th> <th>Address2</th> <th>Address3</th> <th>City, State Zip</th> <th>Country</th> <th>Phone</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($vendors as $vendor) : ?>
			<tr id="tr-<?= $vendor['vendid']; ?>">
				<td>
                    <a href="<?= $returnpage.'&vendorID='.urlencode($vendor['vendid']); ?>" class="btn btn-sm btn-primary load-into-modal" data-modal="#ajax-modal">
                        <?= $vendor['vendid']; ?>
                    </a>
                </td>
				<td class="name"><?= $vendor['name']; ?></td>
				<td><?= $vendor['address1']; ?></td>
				<td><?= $vendor['address2']; ?></td>
                <td><?= $vendor['address3']; ?></td>
				<td><?= $vendor['city'].', '.$vendor['state'].' '.$vendor['zip']; ?></td>
				<td><?= $vendor['country']; ?></td>
				<td><?= $vendor['phone']; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<script>
    $(function() {
        $('#vendors-table').DataTable();
    })
</script>
