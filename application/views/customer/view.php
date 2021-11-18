<!-- Page Content -->
<div class="container">
  <div class="page-header pr-0">
    <button type="button" class="btn btn-primary btn-pill btn-lg" data-toggle="modal" data-target="#AddCustomer">
      <i class="fa fa-plus-circle"></i> <?= label("AddCustomer"); ?>
    </button>
  </div>
  <div class="card">
    <div class="card-header bg-info">
      <h3 class="my-0">Listado de Clientes</h3>
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
                  <th><?= label("CustomerName"); ?></th>
                  <th><?= label("CustomerPhone"); ?></th>
                  <th class="hidden-xs"><?= label("CustomerEmail"); ?></th>
                  <th class="hidden-xs"><?= label("CustomerAddress"); ?></th>
                  <th><?= label("Action"); ?></th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($customers as $customer) : ?>
                  <tr>
                    <td>
                      <?php
                      if ($customer->typedocument == 1) echo "DUI";
                      if ($customer->typedocument == 2) echo "CARNET EXTRANJERÍA";
                      if ($customer->typedocument == 3) echo "PASAPORTE";
                      ?>
                    </td>
                    <td><?= $customer->document; ?></td>
                    <td><?= $customer->name; ?> <?= $customer->lastname; ?></td>
                    <td><?= $customer->phone; ?></td>
                    <td class="hidden-xs"><?= $customer->email; ?></td>
                    <td class="hidden-xs"><?= $customer->discount; ?></td>
                    <td>
                      <div class="btn-group">
                        <a class="btn btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="left" data-html="true" title='<?= label("Areyousure"); ?>' data-content='<a class="btn btn-danger" href="customers/delete/<?= $customer->id; ?>"><?= label("yesiam"); ?></a>'><i class="fa fa-times"></i></a>
                        <a class="btn btn-default" href="customers/edit/<?= $customer->id; ?>" data-toggle="tooltip" data-placement="top" title="<?= label('Edit'); ?>"><i class="fa fa-pencil"></i></a>
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
<div class="modal fade" id="AddCustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= label("AddCustomer"); ?></h4>
      </div>
      <?php echo form_open_multipart('customers/add'); ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerType"><?= label("Tipo de Cliente"); ?></label>
              <select class="form-control" name="typecustomer" id="CustomerType" required>
                <option value="1">Jurídico</option>
                <option value="2">Natural</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerNit"><?= label("NIT"); ?></label>
              <input required type="text" maxlength="20" name="nit" class="form-control" id="CustomerNit" placeholder="<?= label("NIT"); ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerTypeDocument"><?= label("CustomerTypeDocument"); ?></label>
              <select class="form-control" name="typedocument" id="CustomerTypeDocument" required>
                <option value="1">DUI</option>
                <option value="2">CARNET EXTRANJERÍA</option>
                <option value="3">PASAPORTE</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerDocument"><?= label("CustomerNumberDocument"); ?></label>
              <input required type="number" maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="document" class="form-control" id="CustomerDocument" placeholder="<?= label("CustomerNumberDocument"); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerName"><?= label("CustomerName"); ?></label>
              <input type="text" name="name" maxlength="50" Required class="form-control" id="CustomerName" placeholder="<?= label("CustomerName"); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerLastName"><?= label("CustomerLastName"); ?></label>
              <input type="text" name="lastname" maxlength="150" Required class="form-control" id="CustomerLastName" placeholder="<?= label("CustomerLastName"); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerPhone"><?= label("CustomerPhone"); ?></label>
              <input type="text" name="phone" maxlength="30" class="form-control" id="CustomerPhone" placeholder="<?= label("CustomerPhone"); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerEmail"><?= label("CustomerEmail"); ?></label>
              <input type="email" maxlength="50" name="email" class="form-control" id="CustomerEmail" placeholder="<?= label("CustomerEmail"); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
            <label for="CustomerDiscount"><?= label("CustomerAddress"); ?></label>
              <textarea name="discount" class="form-control" id="CustomerDiscount" id="exampleFormControlTextarea1" rows="3" placeholder="<?= label("CustomerAddress"); ?>"></textarea>
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