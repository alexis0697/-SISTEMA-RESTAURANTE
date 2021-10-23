<div class="container container-small">
  <div class="row" style="margin-top:100px;">
    <?php
    $attributes = array('id' => 'addform');
    echo form_open_multipart('expences/edit/' . $expence->id, $attributes);
    ?>
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
        <div class="form-group controls">
          <label for="Date"><?= label("Date"); ?> *</label>
          <input type="text" maxlength="30" Required name="date" value="<?= $expence->date->format('m/d/Y'); ?>" class="form-control" id="Date" placeholder="<?= label("Date"); ?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group" id="supply">
          <label for="Supplier"><?= label("Supplier"); ?></label>
          <select class="form-control" name="supplier" id="Supplier">
            <option><?= label("Supplier"); ?></option>
            <?php foreach ($suppliers as $supplier) : ?>
              <option value="<?= $supplier->id; ?>" <?= $expence->supplier_id === $supplier->id ? 'selected' : ''; ?>><?= $supplier->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="Reference"><?= label("Reference"); ?> *</label>
          <input type="text" name="reference" value="<?= $expence->reference; ?>" maxlength="25" Required class="form-control" id="Reference" placeholder="<?= label("Reference"); ?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="Category"><?= label("Category"); ?></label>
          <select class="form-control" name="category" id="Category">
            <option value="0"><?= label("Category"); ?></option>
            <?php foreach ($categories as $category) : ?>
              <option value="<?= $category->id; ?>" <?= $expence->category_id == $category->id ? 'selected' : ''; ?>><?= $category->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="store_id"><?= label("Store"); ?></label>
          <?php if ($this->user->role !== "admin") : ?>
            <input type="text" value="<?= $storeName; ?>" class="form-control" id="store_id" disabled>
            <input type="hidden" value="<?= $expence->store_id; ?>" name="store_id">
          <?php else : ?>
            <select class="form-control" name="store_id" id="store_id">
              <option value="0"><?= label("Store"); ?></option>
              <?php foreach ($stores as $store) : ?>
                <option value="<?= $store->id; ?>" <?= $expence->store_id == $store->id ? 'selected' : ''; ?>><?= $store->name; ?></option>
            <?php endforeach;
            endif; ?>

            </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="Amount"><?= label("Amount"); ?> (<?= $this->setting->currency; ?>) *</label>
          <input type="number" step="any" Required name="amount" value="<?= $expence->amount; ?>" class="form-control" id="Amount" placeholder="<?= label("Amount"); ?>">
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="exampleInputFile"><?= label("Attachment"); ?></label>
      <input type="file" name="userfile" id="attachment">
      <p class="help-block"><?= label("AttachmentInfos"); ?></p>
    </div>
    <div class="form-group">
      <label for="Note"><?= label("Note"); ?></label>
      <textarea id="summernote" name="note"><?= $expence->note; ?></textarea>
    </div>

    <?php echo form_close(); ?>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {

    $('#Date').datepicker({
      todayHighlight: true,
      setDate: new Date(),
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
  });
</script>