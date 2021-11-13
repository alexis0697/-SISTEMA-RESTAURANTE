<div class="container" style="margin-top: 60px;">

    <div class="page-header pr-0">
      <button type="button" class="btn btn-primary btn-pill btn-lg" data-toggle="modal" data-target="#AddExpence"><i class="fa fa-plus-circle"></i> <?= label("AddExpence"); ?></button>
    </div>

    <div class="card">
      <div class="card-header bg-info">
        <h3 class="my-0">Listado de Gastos</h3>
      </div>
      <div class="card-body">
        <form id="form-filter" class="form-inline float-right hidden-xs hidden-sm">
          <div class="row" style="z-index: 1;margin-bottom: 15px;">
            <div class="col-md-3">
              <div class="form-group">
                <label for="cmbCategoriaFiltro"><?= label("Categoría"); ?>: </label>
                <select class="form-control" name="cmbCategoriaFiltro" id="cmbCategoriaFiltro">
                  <option value=""><?= label("All"); ?></option>
                  <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="cmbTiendaFiltro"><?= label("Store"); ?>: </label>
                <select class="form-control" name="cmbTiendaFiltro" id="cmbTiendaFiltro">
                  <option value=""><?= label("All"); ?></option>
                  <?php foreach ($storesAll as $store) : ?>
                    <option value="<?= $store->id; ?>"><?= $store->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="txtFechaInicio"><?= label("Fecha Inicio"); ?>: </label>
                <input type="text" class="form-control" id="txtFechaInicio" name="txtFechaInicio">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label style="display: block;" for="txtFechaFin"><?= label("Fecha Fin"); ?>: </label>
                <input type="text" id="txtFechaFin" name="txtFechaFin" class="form-control" style="width:65%">
                <button type="button" id="btn-filter" class="btn btn-danger"><i class="fa fa-search"></i></button>
                <button type="button" id="btn-reset" class="btn btn-info"><i class="fa fa-trash-o"></i></button>
              </div>
            </div>
          </div>
        </form>

        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead class="thead-inverse">
                  <tr>
                    <th><?= label('Date'); ?></th>
                    <th><?= label('Reference'); ?></th>
                    <th><?= label('Amount'); ?></th>
                    <th><?= label('Category'); ?></th>
                    <th><?= label('Store'); ?></th>
                    <th><?= label('Createdby'); ?></th>
                    <th><?= label('Action'); ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script type="text/javascript">
    var save_method; //for save method string
    var table;


    $(document).ready(function() {

      $('#Date').datepicker({
        todayHighlight: true,
        setDate: new Date(),
        autoclose: true
      });

      $('#txtFechaInicio').datepicker({
        todayHighlight: true,
        setDate: new Date(),
        autoclose: true,
        format: 'dd/mm/yyyy'
      });

      $('#txtFechaFin').datepicker({
        todayHighlight: true,
        setDate: new Date(),
        autoclose: true,
        format: 'dd/mm/yyyy'
      });

      $("#Date").datepicker("setDate", new Date());

      var date = new Date();
      $("#txtFechaInicio").datepicker("setDate", new Date(date.getFullYear(), date.getMonth(), 1));
      $("#txtFechaFin").datepicker("setDate", new Date(date.getFullYear(), date.getMonth() + 1, 0));

      $('#summernote').summernote({
        height: 200,
        toolbar: [
          // [groupName, [list of button]]
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', []],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['height', ['height']]
        ]
      });

      table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('expences_controller/ajax_list') ?>",
          "type": "POST",
          "data": function(data) {
            data.cmbCategoriaFiltro = $('#cmbCategoriaFiltro').val();
            data.cmbTiendaFiltro = $('#cmbTiendaFiltro').val();
            data.txtFechaInicio = $('#txtFechaInicio').val();
            data.txtFechaFin = $('#txtFechaFin').val();
          }
        },
        "bFilter": false,
        ordering: false,
        //Set column definition initialisation properties.
        "columnDefs": [{
          "targets": [-1], //last column
          "orderable": false, //set not orderable
        }, ],
        "bInfo": false,
        // "fnRowCallback": function(nRow, aData, iDisplayIndex) {
        //     nRow.setAttribute('data-order',aData[4]);
        // }
      });

      $('#btn-filter').click(function() { //button filter event click
        table.ajax.reload(); //just reload table
      });
      $('#btn-reset').click(function() { //button reset event click
        $('#form-filter')[0].reset();
        table.ajax.reload(); //just reload table
      });
      //deleting filter at first load 
      reset_form_expenses();
    });
    function reset_form_expenses(){
      $('#form-filter')[0].reset();
        table.ajax.reload(); //just reload table
    }

    function reload_table() {
      table.ajax.reload(null, false); //reload datatable ajax
    }

    function delete_expences(id) {
      swal({
          title: '<?= label("Areyousure"); ?>',
          text: '<?= label("Deletemessage"); ?>',
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: '<?= label("yesiam"); ?>',
          closeOnConfirm: false
        },
        function() {
          // ajax delete data to database
          $.ajax({
            url: "<?php echo site_url('expences_controller/ajax_delete') ?>/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
              //if success reload ajax table
              $('#modal_form').modal('hide');
              reload_table();
            },
            error: function(jqXHR, textStatus, errorThrown) {
              alert('Error adding / update data');
            }
          });
          swal('<?= label("Deleted"); ?>', '<?= label("Deletedmessage"); ?>', "success");
        });
    }
  </script>


  <!-- Modal -->
  <div class="modal fade" id="AddExpence" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><?= label("AddExpence"); ?></h4>
        </div>
        <?php
        $attributes = array('id' => 'addform');
        echo form_open_multipart('expences/add', $attributes);
        ?>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group controls">
                <label for="Date"><?= label("Date"); ?> *</label>
                <input type="text" maxlength="30" Required name="date" class="form-control" id="Date" placeholder="<?= label("Date"); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group" id="supply">
                <label for="Supplier"><?= label("Supplier"); ?></label>
                <select class="form-control" name="supplier" id="Supplier">
                  <option><?= label("Supplier"); ?></option>
                  <?php foreach ($suppliers as $supplier) : ?>
                    <option value="<?= $supplier->id; ?>"><?= $supplier->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="Reference"><?= label("Reference"); ?> *</label>
                <input type="text" name="reference" maxlength="25" Required class="form-control" id="Reference" placeholder="<?= label("Reference"); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="Category"><?= label("Category"); ?></label>
                <select class="form-control" name="category" id="Category" Required>
                  <option value="0"><?= label("Category"); ?></option>
                  <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="store_id"><?= label("Store"); ?></label>
                <?php if (isset($storeId)) : ?>
                  <input type="text" value="<?= $storeName; ?>" class="form-control" id="store_id" disabled>
                  <input type="hidden" value="<?= $storeId; ?>" name="store_id">
                <?php else : ?>
                  <select class="form-control" name="store_id" id="store_id">
                    <option value="0"><?= label("Store"); ?></option>
                    <?php foreach ($stores as $store) : ?>
                      <option value="<?= $store->id; ?>"><?= $store->name; ?></option>
                    <?php endforeach; ?>
                  </select>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="Amount"><?= label("Monto del gasto "); ?> (<?= $this->setting->currency; ?>) *</label>
                <input type="number" step="any" Required name="amount" class="form-control" id="Amount" placeholder="<?= label("Amount"); ?>">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="exampleInputFile"><?= label("Attachment"); ?></label>
            <input type="file" name="userfile" id="attachment">
            <p class="help-block"><?= label("AttachmentInfos"); ?></p>
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