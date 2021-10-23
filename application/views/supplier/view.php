<!-- Page Content -->
<div class="container">
  <div class="page-header pr-0">
    <button type="button" class="btn btn-primary btn-pill btn-lg" data-toggle="modal" data-target="#AddSupplier">
      <i class="fa fa-plus-circle"></i> <?= label("AddSupplier"); ?>
    </button>
  </div>

  <div class="card">
    <div class="card-header bg-info">
      <h3 class="my-0">Listado de Proveedores</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><?= label("SupplierName"); ?></th>
                  <th><?= label("SupplierPhone"); ?></th>
                  <th class="hidden-xs"><?= label("SupplierEmail"); ?></th>
                  <th class="hidden-xs"><?= label("CreatedAt"); ?></th>
                  <th><?= label("Action"); ?></th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($suppliers as $supplier) : ?>
                  <tr>
                    <td><?= $supplier->name; ?></td>
                    <td><?= $supplier->phone; ?></td>
                    <td class="hidden-xs"><?= $supplier->email; ?></td>
                    <td class="hidden-xs"><?= $supplier->created_at; ?></td>
                    <td>
                      <div class="btn-group">
                        <a class="btn btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="left" data-html="true" title='<?= label("Areyousure"); ?>' data-content='<a class="btn btn-danger" href="suppliers/delete/<?= $supplier->id; ?>"><?= label("yesiam"); ?></a>'><i class="fa fa-times"></i></a>
                        <a class="btn btn-default" href="suppliers/edit/<?= $supplier->id; ?>" data-toggle="tooltip" data-placement="top" title="<?= label('Edit'); ?>"><i class="fa fa-pencil"></i></a>
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
<div class="modal fade" id="AddSupplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= label("AddSupplier"); ?></h4>
      </div>
      <?php echo form_open_multipart('suppliers/add'); ?>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerTypeDocument"><?= label("CustomerTypeDocument"); ?></label>
              <select class="form-control" name="typedocument" id="CustomerTypeDocument" required>
                <option value="1">DNI</option>
                <option value="2">CARNET EXTRANJERÍA</option>
                <option value="3" selected>RUC</option>
                <option value="4">PASAPORTE</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerDocument"><?= label("CustomerNumberDocument"); ?></label>
              <input required type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="document" maxlength="11" class="form-control" id="CustomerDocument" placeholder="<?= label("CustomerNumberDocument"); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierName"><?= label("SupplierName"); ?></label>
              <input type="text" name="name" maxlength="50" Required class="form-control" id="SupplierName" placeholder="<?= label("SupplierName"); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierPhone"><?= label("SupplierPhone"); ?></label>
              <input type="text" name="phone" maxlength="30" class="form-control" id="SupplierPhone" placeholder="<?= label("SupplierPhone"); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierEmail"><?= label("SupplierEmail"); ?></label>
              <input type="email" maxlength="50" name="email" class="form-control" id="SupplierEmail" placeholder="<?= label("SupplierEmail"); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierTypeAccount"><?= label("Tipo de Cuenta"); ?></label>
              <select class="form-control" name="typeaccount" id="SupplierTypeAccount">
                <option value="">-- Seleccione --</option>
                <option value="1">Débito</option>
                <option value="2">Crédito</option>
                <option value="3">Efectivo</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierTypeBank"><?= label("Tipo de Banco"); ?></label>
              <select class="form-control" name="typebank" id="SupplierTypeBank">
                <option value="">-- Seleccione --</option>
                <option value="1">BCP</option>
                <option value="2">Banco de la nación</option>
                <option value="3">Scotiabank</option>
                <option value="4">Interbank</option>
                <option value="5">Caja Piura</option>
                <option value="6">Caja Sullana</option>
                <option value="7">Caja Arequipa</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="Note"><?= label("Notas"); ?></label>
          <textarea id="summernote" name="note"></textarea>
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