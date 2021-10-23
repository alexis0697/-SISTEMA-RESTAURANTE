<!-- Page Content -->
<div class="container">
  <div class="page-header pr-0">
    <button type="button" class="btn btn-primary btn-pill btn-lg" data-toggle="modal" data-target="#AddWaiter">
      <i class="fa fa-plus-circle"></i> <?= label("AddWaiter"); ?>
    </button>
  </div>
  <div class="card">
    <div class="card-header bg-info">
      <h3 class="my-0">Listado de Meseros</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><?= label("CustomerTypeDocument"); ?></th>
                  <th><?= label("CustomerNumberDocument"); ?></th>
                  <th><?= label("WaiterName"); ?></th>
                  <th><?= label("WaiterPhone"); ?></th>
                  <th class="hidden-xs"><?= label("WaiterEmail"); ?></th>
                  <th class="hidden-xs"><?= label("Store"); ?></th>
                  <th><?= label("Action"); ?></th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($waiters as $waiter) : ?>
                  <tr>
                    <td>
                      <?php
                      if ($waiter->typedocument == 1) echo "DNI";
                      if ($waiter->typedocument == 2) echo "CARNET EXTRANJERÍA";
                      if ($waiter->typedocument == 3) echo "RUC";
                      if ($waiter->typedocument == 4) echo "PASAPORTE";
                      ?>
                    </td>
                    <td><?= $waiter->document; ?></td>
                    <td><?= $waiter->name; ?> <?= $waiter->lastname; ?></td>
                    <td><?= $waiter->phone; ?></td>
                    <td class="hidden-xs"><?= $waiter->email; ?></td>
                    <td class="hidden-xs"><?= $strs[$waiter->store_id]; ?></td>
                    <td>
                      <div class="btn-group">
                        <a class="btn btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="left" data-html="true" title='<?= label("Areyousure"); ?>' data-content='<a class="btn btn-danger" href="waiters/delete/<?= $waiter->id; ?>"><?= label("yesiam"); ?></a>'><i class="fa fa-times"></i></a>
                        <a class="btn btn-default" href="waiters/edit/<?= $waiter->id; ?>" data-toggle="tooltip" data-placement="top" title="<?= label('Edit'); ?>"><i class="fa fa-pencil"></i></a>
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

<!-- Modal -->
<div class="modal fade" id="AddWaiter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= label("AddWaiter"); ?></h4>
      </div>
      <?php echo form_open_multipart('waiters/add'); ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerTypeDocument"><?= label("CustomerTypeDocument"); ?></label>
              <select class="form-control" name="typedocument" id="CustomerTypeDocument" required>
                <option value="1">DNI</option>
                <option value="2">CARNET EXTRANJERÍA</option>
                <option value="3">RUC</option>
                <option value="4">PASAPORTE</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerDocument"><?= label("CustomerNumberDocument"); ?></label>
              <input required type="number" name="document" maxlength="30" class="form-control" id="CustomerDocument" placeholder="<?= label("CustomerNumberDocument"); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="WaiterName"><?= label("WaiterName"); ?></label>
              <input type="text" name="name" maxlength="50" Required class="form-control" id="WaiterName" placeholder="<?= label("WaiterName"); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="WaiterLastName"><?= label("WaiterLastName"); ?></label>
              <input type="text" name="lastname" maxlength="150" Required class="form-control" id="WaiterLastName" placeholder="<?= label("WaiterLastName"); ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="WaiterPhone"><?= label("WaiterPhone"); ?></label>
              <input type="text" name="phone" maxlength="30" class="form-control" id="WaiterPhone" placeholder="<?= label("WaiterPhone"); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="WaiterEmail"><?= label("WaiterEmail"); ?></label>
              <input type="email" maxlength="50" name="email" class="form-control" id="WaiterEmail" placeholder="<?= label("WaiterEmail"); ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" id="supply">
              <label for="WaiterStore"><?= label("Store"); ?></label>
              <select class="form-control" name="store_id" id="WaiterStore" Required>
                <!-- <option><?= label("Store"); ?></option> -->
                <?php foreach ($stores as $store) : ?>
                  <option value="<?= $store->id; ?>"><?= $store->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
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