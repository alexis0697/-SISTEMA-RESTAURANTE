<!-- Page Content -->
<div class="container">
  <div class="page-header pr-0">
    <button type="button" class="btn btn-primary btn-pill btn-lg" data-toggle="modal" data-target="#Addunit">
      <i class="fa fa-plus-circle"></i> <?= label("Agregar Unidad de Medida"); ?>
    </button>
  </div>
  <div class="card">
    <div class="card-header bg-info">
      <h3 class="my-0">Listado de Unidades de Medida</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><?= label("Unidad de Medida"); ?></th>
                  <th><?= label("Abreviatura"); ?></th>
                  <th><?= label("Descripción"); ?></th>
                  <th><?= label("Action"); ?></th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($units_measurements as $measurement) : ?>
                  <tr>
                    <td><?= $measurement->name; ?></td>
                    <td><?= $measurement->abbreviation; ?></td>
                    <td><?= $measurement->description; ?></td>
                    <td>
                      <div class="btn-group">
                        <?php if ($this->user->role === "admin") { ?><a class="btn btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="left" data-html="true" title='<?= label("Areyousure"); ?>' data-content='<a class="btn btn-danger" href="unit_measurements/delete/<?= $measurement->id; ?>"><?= label("yesiam"); ?></a>'><i class="fa fa-times"></i></a><?php } ?>
                        <a class="btn btn-default" href="unit_measurements/edit/<?= $measurement->id; ?>" data-toggle="tooltip" data-placement="top" title="<?= label('Edit'); ?>"><i class="fa fa-pencil"></i></a>
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
<div class="modal fade" id="Addunit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= label("Agregar Unidad de Medida"); ?></h4>
      </div>
      <?php echo form_open_multipart('unit_measurements/add'); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="UnitName"><?= label("Nombre"); ?></label>
          <input type="text" maxlength="50" name="name" class="form-control" id="UnitName" placeholder="<?= label("Nombre"); ?>" required>
        </div>
        <div class="form-group">
          <label for="UnitAbreviatura"><?= label("Abreviatura"); ?></label>
          <input type="text" maxlength="50" name="abbreviation" class="form-control" id="UnitAbreviatura" placeholder="<?= label("Abreviatura"); ?>" required>
        </div>
        <div class="form-group">
          <label for="UnitADescription"><?= label("Descripción"); ?></label>
          <input type="text" maxlength="255" name="description" class="form-control" id="UnitADescription" placeholder="<?= label("Descripción"); ?>">
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