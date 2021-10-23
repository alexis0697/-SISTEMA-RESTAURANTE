<!-- Page Content -->
<div class="container">
  <div class="page-header pr-0">
    <button type="button" class="btn btn-primary btn-pill btn-lg" data-toggle="modal" data-target="#AddStore"><i class="fa fa-plus-circle"></i> <?= label("AddStore"); ?></button>
  </div>
  <div class="card">
    <div class="card-header bg-info">
      <h3 class="my-0">Listado de Tiendas</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><?= label("StoreName"); ?></th>
                  <th><?= label("Email"); ?></th>
                  <th><?= label("StorePhone"); ?></th>
                  <th><?= label("Country"); ?></th>
                  <th><?= label("City"); ?></th>
                  <th><?= label("Action"); ?></th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($Stores as $store) : ?>
                  <tr>
                    <td><?= $store->name; ?></td>
                    <td><?= $store->email; ?></td>
                    <td><?= $store->phone; ?></td>
                    <td><?= $store->country; ?></td>
                    <td><?= $store->city; ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if ($store->id == 1) { ?>
                          <!-- <a class="btn btn-default" disabled="disabled" data-toggle="tooltip" data-placement="top" title="<?= label('Delete'); ?>"><i class="fa fa-times"></i></a> -->
                        <?php } else { ?>
                          <a class="btn btn-default" href="stores/delete/<?= $store->id; ?>" data-toggle="tooltip" data-placement="top" title="<?= label('Delete'); ?>"><i class="fa fa-times"></i></a>
                        <?php } ?>
                        
                        <a class="btn btn-default" href="stores/edit/<?= $store->id; ?>" data-toggle="tooltip" data-placement="top" title="<?= label('Edit'); ?>"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-default" href="stores/editTables/<?= $store->id; ?>" data-toggle="tooltip" data-placement="top" title="<?= label('manageTables'); ?>"><i class="fa fa-list-ol"></i></a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.container -->
<!-- add store Modal -->
<div class="modal fade" id="AddStore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= label("AddStore"); ?></h4>
      </div>
      <?php echo form_open_multipart('stores/add'); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="StoreName"><?= label("StoreName"); ?> *</label>
          <input type="text" name="name" class="form-control" id="StoreName" placeholder="<?= label("StoreName"); ?>" required>
        </div>
        <div class="form-group">
          <label for="email"><?= label("Email"); ?></label>
          <input type="email" name="email" class="form-control" id="email" placeholder="<?= label("Email"); ?>">
        </div>
        <div class="form-group">
          <label for="StorePhone"><?= label("StorePhone"); ?></label>
          <input type="text" name="phone" class="form-control" id="StorePhone" placeholder="<?= label("StorePhone"); ?>">
        </div>
        <div class="form-group">
          <label for="Country"><?= label("Country"); ?></label>
          <input type="text" name="country" class="form-control" id="Country" placeholder="<?= label("Country"); ?>">
        </div>
        <div class="form-group">
          <label for="City"><?= label("City"); ?></label>
          <input type="text" name="city" class="form-control" id="City" placeholder="<?= label("City"); ?>">
        </div>
        <div class="form-group">
          <label for="Adresse"><?= label("Adresse"); ?></label>
          <input type="text" name="adresse" class="form-control" id="Adresse" placeholder="<?= label("Adresse"); ?>">
        </div>
        <div class="form-group">
          <label for="CustomeFooter"><?= label("CustomeFooter"); ?></label>
          <input type="text" name="footer_text" class="form-control" id="CustomeFooter" placeholder="<?= label("CustomeFooter"); ?>">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= label("Close"); ?></button>
        <button type="submit" class="btn btn-add"><?= label("Submit"); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<!-- /.Modal -->