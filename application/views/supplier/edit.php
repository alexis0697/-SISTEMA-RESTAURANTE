<div class="container container-small">
   <div class="row" style="margin-top:100px;">
      <?php echo form_open_multipart('suppliers/edit/' . $supplier->id); ?>
      <div class="col-md-12 text-center" style="padding: 0;">
         <div class="form-group">
            <a class="btn btn-info" href="#" onclick="history.back(-1)">
               <i class="fa fa-arrow-left"></i> <?= label("Back"); ?>
            </a>
            <button type="submit" class="btn btn-primary"><?= label("Submit"); ?></button>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerTypeDocument"><?= label("CustomerTypeDocument"); ?></label>
               <select class="form-control" name="typedocument" id="CustomerTypeDocument" required>
                  <option value="1" <?= $supplier->typedocument === 1 ? 'selected' : ''; ?>>DNI</option>
                  <option value="2" <?= $supplier->typedocument === 2 ? 'selected' : ''; ?>>CARNET EXTRANJERÍA</option>
                  <option value="3" <?= $supplier->typedocument === 3 ? 'selected' : ''; ?>>RUC</option>
                  <option value="4" <?= $supplier->typedocument === 4 ? 'selected' : ''; ?>>PASAPORTE</option>
               </select>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerDocument"><?= label("CustomerNumberDocument"); ?></label>
               <input required type="number" value="<?= $supplier->document; ?>" name="document" maxlength="30" class="form-control" id="CustomerDocument" placeholder="<?= label("CustomerNumberDocument"); ?>">
            </div>
         </div>
      </div>

      <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierName"><?= label("SupplierName"); ?></label>
              <input type="text" maxlength="50" Required name="name" value="<?= $supplier->name; ?>" class="form-control" id="SupplierName" placeholder="<?= label("SupplierName"); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierPhone"><?= label("SupplierPhone"); ?></label>
              <input type="text" name="phone" maxlength="30" value="<?= $supplier->phone; ?>" class="form-control" id="SupplierPhone" placeholder="<?= label("SupplierPhone"); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierEmail"><?= label("SupplierEmail"); ?></label>
              <input type="email" maxlength="50" name="email" value="<?= $supplier->email; ?>" class="form-control" id="SupplierEmail" placeholder="<?= label("SupplierEmail"); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="SupplierTypeAccount"><?= label("Tipo de Cuenta"); ?></label>
              <select class="form-control" name="typeaccount" id="SupplierTypeAccount">
              <option value="">-- Seleccione --</option>
                <option value="1" <?= $supplier->typeaccount === 1 ? 'selected' : ''; ?>>Débito</option>
                <option value="2" <?= $supplier->typeaccount === 2 ? 'selected' : ''; ?>>Crédito</option>
                <option value="3" <?= $supplier->typeaccount === 3 ? 'selected' : ''; ?>>Efectivo</option>
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
                <option value="1" <?= $supplier->typebank === 1 ? 'selected' : ''; ?>>BCP</option>
                <option value="2" <?= $supplier->typebank === 2 ? 'selected' : ''; ?>>Banco de la nación</option>
                <option value="3" <?= $supplier->typebank === 3 ? 'selected' : ''; ?>>Scotiabank</option>
                <option value="4" <?= $supplier->typebank === 4 ? 'selected' : ''; ?>>Interbank</option>
                <option value="5" <?= $supplier->typebank === 5 ? 'selected' : ''; ?>>Caja Piura</option>
                <option value="6" <?= $supplier->typebank === 6 ? 'selected' : ''; ?>>Caja Sullana</option>
                <option value="7" <?= $supplier->typebank === 7 ? 'selected' : ''; ?>>Caja Arequipa</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="Note"><?= label("Notas"); ?></label>
          <textarea id="summernote" name="note"><?= $supplier->note; ?></textarea>
        </div>

   </div>
   <?php echo form_close(); ?>
</div>